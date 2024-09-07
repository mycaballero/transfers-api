<?php

namespace App\Services\PartnerService\Impl;

use App\Models\SaleOrder;
use App\Repositories\GeneralParameters\GeneralParametersRepository;
use App\Services\CityService\CityService;
use App\Services\PartnerService\PartnerService;
use App\Models\Partner;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\AssignOp\Mod;
use Ramsey\Collection\Collection;

class PartnerServiceImpl implements PartnerService
{
    private mixed $models;

    /**
     * @param GeneralParametersRepository $generalParametersRepository
     * @param CityService $cityService
     */
    public function __construct(
        protected GeneralParametersRepository $generalParametersRepository,
        protected CityService $cityService
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
            'id' => $payload['id'],
            'address' => $payload['address'],
            'city_id' => $payload['city_id'],
            'credit' => $payload['credit'] ?? null,
            'credit_limit' => $payload['credit_limit'] ?? null,
            'display_name' => $payload['display_name'] ?? null,
            'email' => $payload['email'] ?? null,
            'mobile' => $payload['mobile'] ?? null,
            'parenthood_id' => $payload['parenthood_id'] ?? null,
            'phone' => $payload['phone'] ?? null,
            'state_id' => $payload['state_id'] ?? null,
            'use_partner_credit_limit' => $payload['use_partner_credit_limit'] ?? null,
            'vat' => $payload['vat'] ?? null,
        ];
        Partner::updateOrCreate(['id' => $payload['id']], $data);
    }

    /**
     * @param array|int $id
     * @return bool|array
     */
    public function getById(array|int $id): bool|array
    {
        return Partner::firstOrFail(['id' => $id]);
    }

    /**
     * @param array $periods
     * @param array $restrictions
     * @return bool|array
     */
    public function getAll(array $periods = [], array $restrictions = []): bool|array
    {
        return Partner::query()->get()->toArray();
    }


    /**
     * @param SaleOrder|Collection|null $saleOrder
     * @return bool|null
     */
    public function validateParenthoodPartner(SaleOrder|Collection|null $saleOrder): bool|null
    {
        $partner = $saleOrder->partnerShipping;
        $shippingPartnerId = $saleOrder->partner_shipping_id;
        $invoicePartnerId = $saleOrder->partner_invoice_id;
        if ($shippingPartnerId !== $invoicePartnerId
            && $partner->parenthood_id !== $invoicePartnerId
            && !in_array($shippingPartnerId, $this->generalParametersRepository->getValueByName('partners'))) {
            return false;
        }
        if (!$this->validateCreditLimit($saleOrder)) {
            return false;
        }
        return true;
    }

    /**
     * @param SaleOrder|Collection|null $saleOrder
     * @return bool|null
     */
    public function validateCreditLimit(SaleOrder|Collection|null $saleOrder): bool|null
    {
        $partner = $saleOrder->partnerInvoice;
        if ($partner->credit_limit - ($partner->credit + $saleOrder->total_cost) > -5000)
        {
            return true;
        }
        return false;
    }

    /**
     * @param Model|null $partner
     * @return bool|null
     */
    public function validateCity(?Model $partner): bool|null
    {
       if ($partner && $partner->code) {
            return true;
        }
        return false;
    }
}
