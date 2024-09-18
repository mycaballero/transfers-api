<?php

namespace App\Services\PartnerService;

use App\Models\SaleOrder;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Collection\Collection;

interface PartnerService
{
    /**
     * @param $payload
     * @return void
     */
    public function create($payload): void;

    /**
     * @param int|array $id
     * @return bool|array
     */
    public function getById(int|array $id): bool|array;

    /**
     * @param array $periods
     * @param array $restrictions
     * @return bool|array
     */
    public function getAll(array $periods = [], array $restrictions = []): bool|array;


    /**
     * @param SaleOrder|Collection|null $saleOrder
     * @return bool|null
     */
    public function validateParenthoodPartner(SaleOrder|Collection|null $saleOrder): bool|null;

    /**
     * @param SaleOrder|Collection|null $saleOrder
     * @return bool|null
     */
    public function validateCreditLimit(SaleOrder|Collection|null $saleOrder): bool|null;

    /**
     * @param Model|null $partner
     * @return bool|null
     */
    public function validateCity(?Model $partner): bool|null;
}
