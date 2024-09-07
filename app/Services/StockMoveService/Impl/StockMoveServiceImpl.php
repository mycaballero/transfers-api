<?php

namespace App\Services\StockMoveService\Impl;

use App\Services\StockMoveService\StockMoveService;
use App\Models\Move;

class StockMoveServiceImpl implements StockMoveService
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
            'product_id' => $payload['product_id'] ?? null,
            'picking_id' => $payload['picking_id'] ?? null,
            'product_uom_qty' => $payload['product_uom_qty'] ?? null,
            'value_unit' => $payload['value_unit'] ?? null
        ];
        Move::updateOrCreate(['id' => $payload['id']], $data);
    }

    /**
     * @return mixed
     */
    public function getLast(): mixed
    {
        return Move::latest('created_at')->first();
    }

    /**
     * @param array $periods
     * @param array $restrictions
     * @return bool|array
     */
    public function getAll(array $periods, array $restrictions): bool|array
    {
        return Move::query()->get()->toArray();
    }

    /**
     * @param $id
     * @param $fields
     * @return bool
     */
    public function update($id, $fields): bool
    {
        return true;
    }
}
