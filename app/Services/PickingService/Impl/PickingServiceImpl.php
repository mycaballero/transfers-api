<?php

namespace App\Services\PickingService\Impl;

use App\DTO\Picking\GetAllData;
use App\Helpers\PartnerHelper;
use App\Services\PickingService\PickingService;
use App\Models\Picking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class PickingServiceImpl implements PickingService
{
    /**
     * @param $payload
     * @return Picking
     */
    public function create($payload): Picking
    {

        $data = [
            'id' => $payload['id'] ?? null,
            'name' => PartnerHelper::generateOtuString(),
            'sale_id' => $payload['sale_id'] ?? null,
            'event' => $payload['event'] ?? null,
            'status' => $payload['status'] ?? null,
            'location_id' => $payload['location_id'] ?? null,
        ];
        return Picking::updateOrCreate(['id' => $payload['id']], $data);
    }

    /**
     * @param GetAllData $data
     * @return LengthAwarePaginator|Collection
     */
    public function getAll(GetAllData $data): LengthAwarePaginator|Collection
    {
        $q = Picking::query()
            ->with(['saleOrder.partnerInvoice', 'saleOrder.partnerShipping.city', 'location', 'saleOrder.picking', 'outbound', 'notes'])
            ->whereName($data->name)
            ->orderBy($data->sortBy ?: 'id', $data->sort ?: 'desc');
        $results = $q->get();
        $results = $results->sortBy('eventRanking');
        $perPage = $data->perPage ?? 20;
        $currentPage = Paginator::resolveCurrentPage();
        $total = count($results);
        $items = $results->slice(($currentPage - 1) * $perPage, $perPage)->values();
        return new LengthAwarePaginator($items, $total, $perPage, $currentPage, [
            'path' => Paginator::resolveCurrentPath(),
        ]);
    }

    /**
     * @return mixed
     */
    public function getLast(): mixed
    {
        return Picking::latest('created_at')->where('event','!=', null)->first();
    }

    /**
     * @param string $pickingName
     * @return Model|null
     */
    public function getByName(string $pickingName): null|Model
    {
        return Picking::query()->where('name', '=', $pickingName)->first();
    }

    /**
     * @param array $payload
     * @return void
     */
    public function updateEvent(array $payload): void
    {
        if ($payload['id']) {
            Picking::updateOrCreate(['id' => $payload['id']], $payload);
        }
    }
}
