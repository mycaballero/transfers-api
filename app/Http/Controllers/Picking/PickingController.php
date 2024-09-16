<?php

namespace App\Http\Controllers\Picking;

use App\Http\Controllers\Controller;
use App\DTO\Picking\GetAllData;
use App\Resources\Picking\PickingResource;
use App\Services\Outbound\OutboundService;
use App\Services\PickingService\PickingByProcessService;
use App\Services\PickingService\PickingService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PickingController extends Controller
{
    public function __construct(protected PickingService $pickingService,
    protected PickingByProcessService  $pickingByProcessService,
    protected OutboundService $outboundService,
    )
    {
    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function getPickings(Request $request): mixed #AnonymousResourceCollection
    {
        $pickings = $this->pickingByProcessService->getPickingsWithTimeLimit();
        return $this->pickingByProcessService->processPickings($pickings);
        #return PickingResource::collection($this->pickingService->getAll(GetAllData::from($request)));
    }
}
