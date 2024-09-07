<?php

namespace App\Services\StockQuantService;

use App\DTO\Quant\GetAllData;
use Illuminate\Database\Eloquent\Collection;

interface StockQuantService
{
    /**
     * @param $payload
     * @return void
     */
    public function create($payload): void;

    /**
     * @return mixed
     */
    public function getLast(): mixed;

    /**
     * @param GetAllData $restriction
     * @return bool|array
     */
    public function getAll(GetAllData $restriction ): Collection|array;
}
