<?php

namespace App\Services\PickingService\Impl;

use App\DTO\Quant\GetAllData;
use App\Enums\Picking\PickingEventEnum;
use App\Models\Move;
use App\Repositories\GeneralParameters\GeneralParametersRepository;
use App\Services\CityService\CityService;
use App\Services\PickingService\PickingProcessServices;
use App\Services\PickingService\PickingUtilityService;
use App\Services\StockQuantService\StockQuantService;
use Illuminate\Database\Eloquent\Collection;

class PickingProcessServicesImpl implements PickingProcessServices
{
    /**
     * @param StockQuantService $quantService
     * @param CityService $cityService
     * @param PickingUtilityService $pickingUtilityService
     * @param GeneralParametersRepository $generalParametersRepository
     */
    public function __construct(
        protected StockQuantService     $quantService,
        protected CityService           $cityService,
        protected PickingUtilityService $pickingUtilityService,
        protected GeneralParametersRepository $generalParametersRepository,
    )
    {
    }

    /**
     * @param $move
     * @param $movesNumber
     * @param $acc
     * @param $secondFilter
     * @param $movesToExtract
     * @return void
     */
    private function createNewMoves($move, &$movesNumber, &$acc, &$secondFilter, &$movesToExtract): void
    {
        $oldMove = $move;
        foreach ($secondFilter as $index => $item) {
            if ($acc > 0) {
                $missingAmount = min($item['available'], $acc);
                if ($index == 0) {
                    $move->product_uom_qty = $missingAmount;
                    $secondFilter[$index]['moves'][] = $move;
                } else {
                    $newMove = new Move($oldMove->toArray());
                    $newMove->id = null;
                    $secondFilter[$index]['moves'][] = $newMove;
                }
                $movesToExtract['quants']->transform(function ($quant) use ($item, $missingAmount) {
                    if ($quant['location_id'] === $item['location_id']) {
                        $quant['quantity'] = $quant['quantity'] - $missingAmount;
                    }
                    return $quant;
                });
                $movesNumber += 1;
                $acc -= $missingAmount;
            } else {
                unset($secondFilter[$index]);
            }
        }
    }

    /**
     * @param Collection|array $moves
     * @param $cityPartner
     * @param $saleOrder
     * @return array
     */
    private function orderMovesForPicking(Collection|array $moves, $cityPartner, $saleOrder): array
    {
        $movesToExtract = [
            'moves' => [],
            'quants' => [],
        ];
        $movesToExtract['products'] = $moves->map(function ($move) {
            return $move->product;
        });
        $baseQuants = [];
        $baseQuantsIds = [];
        foreach ($moves as $move) {
            $movesToExtract['moves'][] = $move;
            $quantFiltered = $move->product->quants->filter(function ($quant) use ($move) {
                return $quant->quantity > $move->product_uom_qty && $quant->location->active;
            });
            $quantFiltered = $this->pickingUtilityService->quantCondition($saleOrder,$quantFiltered);
            $movesToExtract['quants'] = $quantFiltered->concat($movesToExtract['quants']);
            $totalAvailableQuantity = $this->pickingUtilityService->calculateTotalAvailableQuantity($quantFiltered);
            if ($totalAvailableQuantity >= $move->product_uom_qty) {
                $secondFilter = $this->pickingUtilityService->filterQuantLocations(
                    $quantFiltered, $cityPartner, $move->value_unit * $move->product_uom_qty, $saleOrder);
                foreach ($secondFilter as &$location) {
                    $location['moves'] = [];
                    $location['moves'][] = $move;
                    $baseQuantsIds[] = $location['location_id'];
                }
                $baseQuants[] = $secondFilter;
            }
        }
        $repeatedValue = [];
        $filteredArrayQuants = [];
        foreach (array_count_values($baseQuantsIds) as $value => $count) {
            if ($count === count($moves)) {
                $repeatedValue[] = $value;
            }
        }
        foreach ($baseQuants as $quant) {
            $filteredArray = array_filter($quant, function ($subArray) use ($repeatedValue) {
                return in_array($subArray['location_id'], $repeatedValue);
            });
            $filteredArrayQuants[] = array_values($filteredArray);
        }
        $movesNumber = $this->pickingUtilityService->countMoves($filteredArrayQuants);
        if ($movesNumber > 0) {
            $locationId = $this->pickingUtilityService->getLocationByRate($filteredArrayQuants);
            $moveLocation[] = [[
                'location_id' => $locationId,
                'moves' => $moves
            ]];
            foreach ($moves as $move) {
                $movesToExtract['quants']->transform(function ($quant) use ($locationId, $move) {
                    if ($quant['location_id'] === $locationId && $quant['product_id'] === $move['product_id']) {
                        $quant['quantity'] = $quant['quantity'] - $move->product_uom_qty;
                    }
                    return $quant;
                });
            }
        } else {
            $secondTriying = $this->orderOrCreateMovesForPicking($moves, $cityPartner, $saleOrder);
            $moveLocation = $secondTriying['moveLocation'];
            $movesNumber = $secondTriying['movesNumber'];
            $movesToExtract= $secondTriying['movesToExtract'];
        }
        return ['moveLocation' => $moveLocation, 'movesNumber' => $movesNumber, 'movesToExtract' => $movesToExtract];
    }

    /**
     * @param $moves
     * @param $cityPartner
     * @param $saleOrder
     * @return array
     */
    public function orderOrCreateMovesForPicking($moves, $cityPartner, $saleOrder): array
    {
        $movesToExtract = [
            'moves' => [],
            'quants' => [],
        ];
        $moveLocation = [];
        $movesNumber = count($moves);
        $movesToExtract['products'] = $moves->map(function ($move) {
            return $move->product;
        });
        foreach ($moves as $move) {
            $quantFiltered = $this->quantService->getAll(GetAllData::from(['id'=> $move->product_id]));
            $quantFiltered = $this->pickingUtilityService->quantCondition($saleOrder,$quantFiltered);
            $movesToExtract['quants'] = $quantFiltered->concat($movesToExtract['quants']);
            $totalAvailableQuantity = $this->pickingUtilityService->calculateTotalAvailableQuantity($quantFiltered);
            if ($totalAvailableQuantity >= $move->product_uom_qty) {
                $secondFilter = $this->pickingUtilityService->filterQuantLocations(
                    $quantFiltered, $cityPartner, $move->value_unit * $move->product_uom_qty, $saleOrder);
                if (count($secondFilter) > 1) {
                    usort($secondFilter, function ($a, $b) {
                        return $a['rate'] - $b['rate'];
                    });
                    $acc = $move->product_uom_qty;
                    $this->createNewMoves(
                        $move, $movesNumber, $acc, $secondFilter, $movesToExtract);
                    $movesNumber -= 1;
                } else if (count($secondFilter) === 1) {
                    $movesToExtract['moves'][] = $move;
                    $secondFilter[0]['moves'][] = $move;
                    $movesToExtract['quants']->transform(function ($quant) use ($secondFilter, $move) {
                        if ($quant['location_id'] === $secondFilter[0]['location_id']) {
                            $quant['quantity'] = $quant['quantity'] - $move->product_uom_qty;
                        }
                        return $quant;
                    });
                } else {
                    $movesNumber -= 1;
                }
                $moveLocation[] = $secondFilter;
            } else {
                $movesNumber -= 1;
            }
        }
        return ['moveLocation' => $moveLocation, 'movesNumber' => $movesNumber, 'movesToExtract' => $movesToExtract];
    }

    /**
     * @param $picking
     * @return array
     */
    public function process($picking): array
    {
        $modelsToExtract = [
            'saleOrders' => [],
            'quants' => [],
            'products' => []
        ];
        $moves = $picking->moves;
        $partner = $picking->saleOrder->partnerShipping;
        $modelsToExtract['partners'] = [$partner->toArray()];
        $saleOrder = $picking->saleOrder;
        if ($picking->saleOrder->partner_invoice_id !== $picking->saleOrder->partner_shipping_id) {
            $modelsToExtract['partners'][] = $picking->saleOrder->partnerInvoice->toArray();
        }
        $modelsToExtract['saleOrders'][] = $saleOrder->toArray();
        $cityPartner = $partner->city;
        $canProcess = $this->pickingUtilityService->canProcess($saleOrder,$modelsToExtract['partners'],$picking,$moves,$cityPartner);
        if ($canProcess['canProcess']) {
            if ($saleOrder->total_cost < intval($this->generalParametersRepository->getValueByName('price'))) {
                $moveLocation = $this->orderMovesForPicking($moves, $cityPartner, $saleOrder);
            } else {
                $moveLocation = $this->orderOrCreateMovesForPicking($moves, $cityPartner, $saleOrder);
            }
            $modelsToExtract['moves'] = $moveLocation['movesToExtract']['moves'];
            $modelsToExtract['quants'] = $moveLocation['movesToExtract']['quants'];
            $modelsToExtract['products'] = $moveLocation['movesToExtract']['products'];
            $disposedMoves = $this->pickingUtilityService->createEmptyLocations($moveLocation['moveLocation']);
            foreach ($moveLocation['moveLocation'] as $locationMove) {
                foreach ($locationMove as $move) {
                    $location = $this->pickingUtilityService->findLocationIndex($disposedMoves, $move['location_id']);
                    array_push($disposedMoves[$location]['moves'], ...$move['moves']);
                }
            }
            $event = $this->pickingUtilityService->claimEventCreator($disposedMoves, $saleOrder);
            if (count($disposedMoves) > 1) {
                return [
                    'locationsMoves' => $this->pickingUtilityService->filterUniqueMoves($disposedMoves),
                    'canProcess' => $canProcess['canProcess'],
                    'event' => $event,
                    'modelsToExtract' => $modelsToExtract
                ];
            } else {
                return [
                    'locationsMoves' => $disposedMoves,
                    'canProcess' => $canProcess['canProcess'],
                    'event' => $event,
                    'modelsToExtract' => $modelsToExtract
                ];
            }
        } else {
            return [
                'locationsMoves' => [],
                'canProcess' => $canProcess['canProcess'],
                'event' => $canProcess['event'],
                'modelsToExtract' => $modelsToExtract
            ];
        }
    }
}
