<?php

namespace App\Services\StockQuantService\Impl;

use App\DTO\Quant\GetAllData;
use App\Services\StockQuantService\StockQuantService;
use App\Models\StockQuant;
use Illuminate\Database\Eloquent\Collection;

class StockQuantServiceImpl implements StockQuantService
{
    public function __construct(
    )
    {
    }

    /**
     * @param $payload
     * @return void
     */
    public function create($payload): void
    {
        $data = [
            'id' => $payload['id'] ?? null,
            'location_id' => $payload['location_id'] ?? null,
            'product_id' => $payload['product_id'] ?? null,
            'quantity' => $payload['quantity'] ?? null
        ];
        StockQuant::updateOrCreate(['id' => $payload['id']], $data);
    }

    /**
     * @return mixed
     */
    public function getLast(): mixed
    {
        return StockQuant::latest('created_at')->first();
    }

    /**
     * @param GetAllData $restriction
     * @return bool|array
     */
    public function getAll(GetAllData $restriction): Collection|array
    {
        return StockQuant::query()->whereName($restriction->name)
            ->whereProductId($restriction->id)
            ->where('quantity','>', 0)
            ->get();
    }
}
