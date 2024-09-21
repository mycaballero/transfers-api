<?php

namespace App\Http\Controllers\Picking;

use App\Http\Controllers\Controller;
use App\DTO\Picking\GetAllData;
use App\Http\Resources\Picking\PickingResource;
use App\Services\PickingService\PickingService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PickingController extends Controller
{
    public function __construct(protected PickingService $pickingService,
    )
    {
    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function getPickings(Request $request): AnonymousResourceCollection
    {
        return PickingResource::collection($this->pickingService->getAll(GetAllData::from($request)));
    }
}
