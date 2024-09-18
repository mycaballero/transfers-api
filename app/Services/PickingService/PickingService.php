<?php

namespace App\Services\PickingService;

use App\DTO\Picking\GetAllData;
use App\Models\Picking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface PickingService
{
    /**
     * @param $payload
     * @return Picking
     */
    public function create($payload): Picking;

    /**
     * @param GetAllData $data
     * @return LengthAwarePaginator|Collection
     */
    public function getAll(GetAllData $data): LengthAwarePaginator|Collection;

    /**
     * @return mixed
     */
    public function getLast(): mixed;

    /**
     * @param string $pickingName
     * @return Model|null
     */
    public function getByName(string $pickingName): null|Model;

    /**
     * @param array $payload
     * @return void
     */
    public function updateEvent(array $payload): void;
}
