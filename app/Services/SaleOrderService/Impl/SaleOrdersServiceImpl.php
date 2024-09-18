<?php

namespace App\Services\SaleOrderService\Impl;

use App\Services\SaleOrderService\SaleOrdersService;
use App\Models\SaleOrder;

class SaleOrdersServiceImpl implements SaleOrdersService
{

    /**
     * @param $payload
     * @return void
     */
    public function create($payload): void
    {
        $data = [
            'id' => $payload['id'],
            'partner_invoice_id' => $payload['partner_invoice_id']?? null,
            'partner_shipping_id' => $payload['partner_shipping_id']?? null,
            'total_cost' => $payload['total_cost']?? null,
            'freight' => $payload['freight'] ?? null,
            'carrier' => $payload['carrier'] ?? null,
            'name' => $payload['name'] ?? null,
            'packed' => $payload['packed'] ?? false,
        ];
        $data = array_filter($data, function ($value) {
            return $value !== null;
        });
        SaleOrder::updateOrCreate(['id' => $payload['id']], $data);
    }

    /**
     * @param array $periods
     * @param array $restrictions
     * @return array
     */
    public function getAll(array $periods = [], array $restrictions = []): array
    {
        return SaleOrder::query()->get()->toArray();
    }
}
