<?php

namespace App\Services\PickingService\Impl;

use App\Enums\Picking\PickingEventEnum;
use App\Enums\Picking\StatusEnum;
use App\Repositories\GeneralParameters\GeneralParametersRepository;
use App\Services\Outbound\OutboundService;
use App\Services\PartnerService\PartnerService;
use App\Services\PickingService\PickingProcessServices;
use App\Services\PickingService\PickingByProcessService;
use App\Services\PickingService\PickingService;
use App\Services\PickingService\PickingUtilityService;
use App\Services\ProductService\ProductService;
use App\Services\SaleOrderService\SaleOrdersService;
use App\Services\StockMoveService\StockMoveService;
use App\Services\StockQuantService\StockQuantService;
use App\Models\Picking;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class PickingByProcessServiceImpl implements PickingByProcessService
{
    /**
     * @param PickingService $pickingService
     * @param PickingProcessServices $processServices
     * @param PickingUtilityService $pickingUtilityService
     * @param PartnerService $partnerService
     * @param StockMoveService $stockMoveService
     * @param ProductService $productService
     * @param SaleOrdersService $saleOrdersService
     * @param StockQuantService $quantService
     * @param GeneralParametersRepository $generalParametersRepository
     * @param OutboundService $outboundService
     */
    public function __construct(
        protected PickingService                $pickingService,
        protected PickingProcessServices        $processServices,
        protected PickingUtilityService         $pickingUtilityService,
        protected PartnerService                $partnerService,
        protected StockMoveService              $stockMoveService,
        protected ProductService                $productService,
        protected SaleOrdersService             $saleOrdersService,
        protected StockQuantService             $quantService,
        protected GeneralParametersRepository   $generalParametersRepository,
        protected OutboundService               $outboundService,
    )
    {
    }

    /**
     * @param $picking
     * @param $locationId
     * @return Picking
     */
    public function duplicate($picking, $locationId): Picking
    {
        $picking->id = null;
        $picking->display_name = null;
        $picking->location_id =  $locationId;
        return $this->pickingService->create($picking->toArray());
    }

    /**
     * @param $pickings
     * @return void
     */
    public function processPickings($pickings): void
    {
        foreach ($pickings as $picking) {
            $changesToDataBase = [
                'moves' => []
            ];
            $pickingByLocations = $this->processServices->process($picking);
            $firstLocation = reset($pickingByLocations['locationsMoves']);
            $picking['event']  = $pickingByLocations['event'];
            $changesToDataBase['pickings'] = [$picking];
            if ($pickingByLocations['canProcess']) {
                foreach ($pickingByLocations['locationsMoves'] as $location) {
                    if ($firstLocation === $location) {
                        $changesToDataBase['pickings'][0] = $picking->toArray();
                        $changesToDataBase['moves'] = array_merge($changesToDataBase['moves'],
                            $this->assignedMoves($location, $picking));
                        $changesToDataBase['pickings'][0]['location_id'] = $location['location_id'];
                        $changesToDataBase['pickings'][0]['event'] = $this->pickingUtilityService->getClaimEvent(
                            $pickingByLocations['event'],
                            $location, $pickingByLocations['modelsToExtract']['partners'][0]);
                    } else {
                        $newPicking = $this->duplicate($picking, $location['location_id']);
                        $changesToDataBase['moves'] = array_merge($changesToDataBase['moves'],
                            $this->assignedMoves($location, $newPicking));
                        $newPicking['event'] = $this->pickingUtilityService->getClaimEvent(
                            $pickingByLocations['event'],
                            $location, $pickingByLocations['modelsToExtract']['partners'][0]);
                        $changesToDataBase['pickings'][] = $newPicking->toArray();
                    }
                }
                $changesToDataBase = [
                    'pickings' => $changesToDataBase['pickings'],
                    'moves' => $changesToDataBase['moves'],
                    'saleOrders' => $pickingByLocations['modelsToExtract']['saleOrders'],
                    'quants' => $pickingByLocations['modelsToExtract']['quants'],
                    'products' => $pickingByLocations['modelsToExtract']['products']->toArray(),
                    'partners' => $pickingByLocations['modelsToExtract']['partners'],
                ];
                $this->extractPickingModels($changesToDataBase);
                $this->outboundService->initializeOutbounds($changesToDataBase);
            }
        }
    }

    /**
     * @param $location
     * @param $picking
     * @return Model|array
     */
    private function assignedMoves($location, $picking): Model|array
    {
        return array_map(function ($move) use ($picking) {
            $move['picking_id'] = $picking['id'];
            return $move;
        },$location['moves']);
    }

    /**
     * @param $models
     * @return void
     */
    public function extractPickingModels($models): void
    {
        foreach ($models as $modelName => $modelItems) {
            $service = $this->getServiceByModelName($modelName);
            foreach ($modelItems as $modelItem) {
                $service->create($modelItem);
            }
        }
    }

    /**
     * @param $modelName
     * @return mixed
     */
    private function getServiceByModelName($modelName): mixed
    {
        return match ($modelName) {
            'partners' => $this->partnerService,
            'products' => $this->productService,
            'moves' => $this->stockMoveService,
            'saleOrders' => $this->saleOrdersService,
            'quants' => $this->quantService,
            'pickings' => $this->pickingService,
            default => throw new \InvalidArgumentException("Service not found for: $modelName"),
        };
    }

    /**
     * @return array|bool
     */
    public function getPickingsWithTimeLimit(): Collection|array
    {
        $originalPickingDate = $this->pickingService->getLast();
        $pickingTimeLimit = $originalPickingDate ? date('Y-m-d H:i:s',
            strtotime($originalPickingDate->created_at . ' +1 second')) : null;

        return Picking::query()->with(['outbound', 'moves.product.quants', 'moves.product.quants.location',
            'saleOrder.partnerShipping.city.locationCovered.location','saleOrder.partnerInvoice'] )->whereIn('status',
                [StatusEnum::ASSIGNED->value, StatusEnum::CONFIRMED->value
            ])->when(isset($pickingTimeLimit), function ($query) use ($pickingTimeLimit) {
            $query->where('created_at','=',$pickingTimeLimit);
            })
            ->get();
    }

    /**
     * @return array|bool
     */
    public function getPickingsByEventLimit(): Collection|array
    {
        $events = [
            PickingEventEnum::NO_STOCK->value,
            PickingEventEnum::NO_CARRIER->value,
            PickingEventEnum::NO_FREIHT->value,
            PickingEventEnum::NO_VALID_PARTNER->value,
            PickingEventEnum::NO_VALID_ADDRESS->value,
            PickingEventEnum::NO_CREDIT->value,
            PickingEventEnum::NO_CITY->value,
        ];
        return Picking::query()
            ->whereNotNull('event')
            ->whereIn('event', $events)
            ->get();
    }
}
