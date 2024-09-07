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
     * @param $uid
     * @param int|array $id
     * @return bool|array
     */
    public function getById(int|array $id): bool|array;

    /**
     * @param $uid
     * @param array $periods
     * @param array $restrictions
     * @return bool|array
     */
    public function getAll(array $periods = [], array $restrictions = []): bool|array;


    /**
     * @param array $saleOrder
     * @return bool|null
     */
    public function validateParenthoodPartner(SaleOrder|Collection|null $saleOrder): bool|null;

    /**
     * @param int $uid
     * @param array $saleOrder
     * @return bool|null
     */
    public function validateCreditLimit(SaleOrder|Collection|null $saleOrder): bool|null;

    /**
     * @param Model|null $partner
     * @return bool|null
     */
    public function validateCity(?Model $partner): bool|null;
}
