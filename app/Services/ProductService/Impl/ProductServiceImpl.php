<?php

namespace App\Services\ProductService\Impl;

use App\Services\ProductService\ProductService;
use App\Models\Product;

class ProductServiceImpl implements ProductService
{

    /**
     * @param $payload
     * @return void
     */
    public function create($payload): void
    {
        $data = [
            'id' => $payload['id'],
            'default_code' => $payload['default_code'],
            'name' => $payload['name'],
            'qty_available' => $payload['qty_available'],
            'category' => $payload['category'],
            'standard_price' => $payload['standard_price'],
            'uom' => $payload['uom'],
        ];
        Product::updateOrCreate(['id' => $payload['id']], $data);
    }

    /**
     * @return mixed
     */
    public function getLast(): mixed
    {
        return Product::latest('created_at')->first();
    }

    /**
     * @param array|int $id
     * @return bool|array
     */
    public function getById(array|int $id): bool|array
    {
        return Product::findOrFail($id);
    }

    /**
     * @param array $periods
     * @param array $restrictions
     * @return bool|array
     */
    public function getAll(array $periods = [], array $restrictions = []): bool|array
    {
        return Product::query()->get()->toArray();
    }

}
