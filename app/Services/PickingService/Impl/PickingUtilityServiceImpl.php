<?php

namespace App\Services\PickingService\Impl;

use App\Enums\Picking\PickingEventEnum;
use App\Enums\Picking\StatusEnum;
use App\Repositories\GeneralParameters\GeneralParametersRepository;
use App\Services\LocationService\LocationService;
use App\Services\PartnerService\PartnerService;
use App\Services\PickingService\PickingUtilityService;
use App\Services\StockMoveService\StockMoveService;
use App\Services\StockQuantService\StockQuantService;
use App\Services\TccService\TccService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class PickingUtilityServiceImpl implements PickingUtilityService
{
    /**
     * @param PartnerService $partnerService
     * @param StockMoveService $stockMoveService
     * @param StockQuantService $stockQuantService
     * @param LocationService $locationService
     * @param TccService $tccService
     * @param GeneralParametersRepository $generalParametersRepository
     */
    public function __construct(
        protected PartnerService    $partnerService,
        protected StockMoveService  $stockMoveService,
        protected StockQuantService $stockQuantService,
        protected LocationService   $locationService,
        protected TccService        $tccService,
        protected GeneralParametersRepository $generalParametersRepository
    )
    {
    }


    /**
     * @param $locations
     * @return array
     */
    public function createEmptyLocations($locations): array
    {
        $newLocations = [];
        foreach ($locations as $moves) {
            foreach ($moves as $move) {
                $locationId = $move['location_id'];
                if (!isset($newLocations[$locationId])) {
                    $newLocations[$locationId] = ['location_id' => $locationId, 'moves' => []];
                }
            }
        }
        return array_values($newLocations);
    }

    /**
     * @param $quantFiltered
     * @return int
     */
    public function calculateTotalAvailableQuantity($quantFiltered): int
    {
        $totalAvailableQuantity = 0;
        foreach ($quantFiltered as $element) {
            if (isset($element->quantity)) {
                $totalAvailableQuantity += $element->quantity;
            }
        }
        return $totalAvailableQuantity;
    }

    /**
     * @param $locations
     * @param $locationId
     * @return bool|int|string
     */
    public function findLocationIndex($locations, $locationId): bool|int|string
    {
        foreach ($locations as $index => $location) {
            if ($location['location_id'] == $locationId) {
                return $index;
            }
        }
        return false;
    }

    /**
     * @param $locations
     * @return array
     */
    public function filterUniqueMoves($locations): array
    {
        $moveIds = [];
        foreach ($locations as &$location) {
            $location['moves'] = array_map('json_encode', $location['moves']);
            $location['moves'] = array_unique($location['moves']);
            $location['moves'] = array_map(function($move) {
                return json_decode($move, true);
            }, $location['moves']);
        }

        unset($location);
        foreach ($locations as $key => $location) {
            $location['moves'] = array_values($location['moves']);
            foreach ($location['moves'] as $moveId) {
                $moveIdStr = json_encode($moveId);
                if (!in_array($moveIdStr, $moveIds)) {
                    $moveIds[] = $moveIdStr;
                } else {
                    $keyMove = array_search($moveIdStr, array_map('json_encode', $location['moves']));
                    unset($location['moves'][$keyMove]);
                }
            }
            if (empty($location['moves'])) {
                unset($locations[$key]);
            }
        }
        return array_values($locations);
    }

    /**
     * @param $quantFiltered
     * @param $cityPartner
     * @param $value
     * @param $saleOrder
     * @return array
     */
    public function filterQuantLocations($quantFiltered, $cityPartner, $value, $saleOrder): array
    {
        $secondFilter = [];
        foreach ($quantFiltered as $element) {
            $location = $this->locationService->getById($element->location_id);
            if ($location->city?->code) {
                $rate = 100.00;
                    // $this->tccService->settle($location->city->code, $cityPartner->code, $value); #TODO AGREGAR TCC
                $secondFilter[] = [
                    'location_id' => $element->location_id,
                    'available' => $element['quantity'],
                    'rate' => $rate
                ];
            }
        }
        return array_values($secondFilter);
    }

    /**
     * @param $locationsGroups
     * @param $saleOrder
     * @return string|null
     */
    public function claimEventCreator( $locationsGroups, $saleOrder): ?string
    {
            foreach ($locationsGroups as $location) {
                if ($saleOrder->partner_shipping_id ===
                    $this->locationService->getById($location['location_id'])->partner_id) {
                    return PickingEventEnum::CLAIM_IN_WAREHOUSE->value;
                }
            }
            return null;
    }

    /**
     * @param $saleOrder
     * @param $quantFiltered
     * @return Collection|array
     */
    public function quantCondition($saleOrder,$quantFiltered): Collection|array
    {
        $locationPartnersIds = array_map('intval',$this->generalParametersRepository->getValueByName('partners'));
        if (in_array($saleOrder->partner_shipping_id, $locationPartnersIds)) {
            $location = $this->locationService->getByPartnerId($saleOrder->partner_shipping_id);
            $quantFiltered = array_filter($quantFiltered, function ($quant) use ($location) {
                return $quant->location_id === $location->id;
            });
        }
        return $quantFiltered->values();
    }

    /**
     * @param $locationGroup
     * @return int
     */
    public function getLocationByRate($locationGroup): int {
        $minRate = PHP_INT_MAX;
        $locationIdWithMinRate = 0;
        foreach ($locationGroup as $subArray) {
            foreach ($subArray as $item) {
                if ($item['rate'] < $minRate) {
                    $minRate = $item['rate'];
                    $locationIdWithMinRate = $item['location_id'];
                }
            }
        }
        return $locationIdWithMinRate;
    }

    /**
     * @param $quants
     * @return int
     */
    public function countMoves($quants): int
    {
       $totalMoves = 0;
       foreach ($quants as $element) {
           foreach ($element as $subArray) {
               $totalMoves += count($subArray['moves']);
           }
       }
       return $totalMoves;
    }

    /**
     * @param string|null $event
     * @param array $location
     * @param array $partner
     * @return string|null
     */
    public function getClaimEvent(?string $event, array $location, array $partner): ?string
    {
        if ($event != PickingEventEnum::CLAIM_IN_WAREHOUSE->value) {
            $token = array_filter($partner['city']['location_covered'],
            function ($locationCovered) use ($location) {
                return $locationCovered['location_id'] === $location['location_id'];
            });
            if (!empty($token)) {
                return PickingEventEnum::CLAIM_IN_WAREHOUSE->value;
            } else {
                return $event;
            }
        }
        return $event;
    }

    /**
     * @param $saleOrder
     * @param $partners
     * @param $picking
     * @param $moves
     * @param $cityPartner
     * @return array
     */
    public function canProcess($saleOrder, $partners, $picking, $moves, $cityPartner ): array
    {
        $event = null;
        $canProcess = true;
        if ($picking->status === StatusEnum::CANCELED->value) {
            $event = PickingEventEnum::CANCELED->value;
            $canProcess = false;
        }
        if ($canProcess && !$this->partnerService->validateParenthoodPartner($saleOrder)) {
            if (!$this->partnerService->validateCreditLimit($saleOrder)) {

                $event = PickingEventEnum::NO_CREDIT->value;
            } else {
                $event = PickingEventEnum::NO_VALID_ADDRESS->value;
            }
            $canProcess = false;
        }
        if ($canProcess && !$this->partnerService->validateCity($cityPartner)) {
            $event = PickingEventEnum::NO_CITY->value;
            $canProcess = false;
        }
        if ($canProcess) {
            if ($saleOrder->total_cost < intval($this->generalParametersRepository->getValueByName('price'))) {
                if (!$this->hasValidMovesForPickingsLower($moves, $saleOrder)) {
                    $event = PickingEventEnum::NO_STOCK->value;
                    $canProcess = false;
                }
            } else {
                if (!$this->hasValidMovesForPickingsHighest($moves, $saleOrder)) {
                    $event = PickingEventEnum::NO_STOCK->value;
                    $canProcess = false;
                }
            }
        }
        $partnerIds = array_map('intval',$this->generalParametersRepository->getValueByName('partners'));
        if ($canProcess && !in_array($saleOrder->partner_shipping_id, $partnerIds)) {
            if (!$saleOrder->carrier) {
                $event = PickingEventEnum::NO_CARRIER->value;
                $canProcess = false;
            }
            if (!$saleOrder->freight) {
                $event = PickingEventEnum::NO_FREIHT->value;
                $canProcess = false;
            }
        }
        return ['event' => $event, 'canProcess' => $canProcess];
    }

    /**
     * @param Collection $moves
     * @param Model|Collection $saleOrder
     * @return bool
     */
    private function hasValidMovesForPickingsLower (Collection $moves, Model|Collection $saleOrder): bool
    {
        $movesCount = 0;
        foreach ($moves as $move) {
            $quantFiltered = $move->product->quants->filter(function ($quant) use ($move) {
               return $quant->quantity > $move->product_uom_qty && $quant->location->active;
            });
            $quantFiltered = $this->quantCondition($saleOrder,$quantFiltered);
            if (!empty($quantFiltered)) {
                $movesCount += 1;
            }
        }
        if ($movesCount < count($moves)) {
            return $this->hasValidMovesForPickingsHighest($moves, $saleOrder);
        }
        return true;
    }

    /**
     * @param Collection $moves
     * @param Model|Collection $saleOrder
     * @return bool
     */
    private function hasValidMovesForPickingsHighest(Collection $moves, Model|Collection $saleOrder): bool
    {
        $movesCount = 0;
        foreach ($moves as $move) {
            $quantFiltered = $move->product->quants->filter(function ($quant) use ($move) {
                return $quant->quantity > $move->product_uom_qty && $quant->location->active;
            });
            $quantFiltered = $this->quantCondition($saleOrder,$quantFiltered);
            $totalAvailableQuantity = $this->calculateTotalAvailableQuantity($quantFiltered);
            if ($totalAvailableQuantity >= $move->product_uom_qty) {
                $movesCount += 1;
            }
        }
        if ($movesCount < count($moves)) {
            return false;
        }
        return true;
    }
}
