<?php

namespace App\Services\Outbound;

use App\DTO\Outbound\CreateData;
use App\Models\Location;
use App\Models\Outbound;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface OutboundService
{

    /**
     * @param CreateData $payload
     * @return array|Outbound|Model|Collection
     */
    public function createOrUpdate(CreateData $payload): array|Outbound|Model|Collection;

    /**
     * @param int $id
     * @return Model
     */
    public function getByPickingId(int $id): Model;

    /**
     * @param array $filters
     * @return Collection|array
     */
    public function getByLimits(array $filters): Collection|array;

    /**
     * @param array|Collection $models
     * @return Collection|array
     */
    public function initializeOutbounds(array|Collection $models): Collection|array;

    /**
     * @param int $locationId
     * @return Location|array
     */
    public function getWarehouse(int $locationId): Location|array;

}
