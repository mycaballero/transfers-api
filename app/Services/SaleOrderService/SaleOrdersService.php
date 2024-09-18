<?php

namespace App\Services\SaleOrderService;

interface SaleOrdersService
{
    /**
     * @param $payload
     * @return void
     */
    public function create($payload): void;

    /**
     * @param array $periods
     * @param array $restrictions
     * @return bool|array
     */
    public function getAll(array $periods, array $restrictions): bool|array;
}
