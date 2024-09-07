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
     * @return Outbound|array
     */
    public function createOrUpdate(CreateData $payload): Outbound|array;

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

    public function initializeOutbounds(array|Collection $models): Collection|array;

    public function getWarehouse(int $locationId): Location|array;

}
