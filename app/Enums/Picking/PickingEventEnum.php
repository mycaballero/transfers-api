<?php

namespace App\Enums\Picking;


use App\Enums\EnumInterface;

enum PickingEventEnum: string implements EnumInterface
{
    case CLAIM_IN_WAREHOUSE = 'REB';
    case NO_CARRIER = 'FTA';
    case NO_FREIHT = 'NFT';
    case NO_STOCK = 'SSK';
    case NO_VALID_PARTNER = 'NVP';
    case CANCELED = 'CLD';
    case NO_VALID_ADDRESS = 'NVA';
    case DELIVERED_TO_WAREHOUSE = 'DTW';
    case PACKED = 'PKD';
    case DISPATCHED = 'DPC';
    case DELIVERED = 'DLD';
    case BLU_FAIL = 'FEB';
    case NO_CREDIT = 'SCD';
    case NO_CITY = 'NCT';

    /**
     * @return string
     */
    public function description(): string
    {
        return match ($this) {
            self::CLAIM_IN_WAREHOUSE => 'Reclama en Bodega',
            self::NO_CARRIER => 'Falta transportadora',
            self::NO_FREIHT => 'Falta flete',
            self::NO_STOCK => 'Sin stock',
            self::NO_VALID_PARTNER => 'Contacto inválido',
            self::CANCELED => 'Cancelado',
            self::NO_VALID_ADDRESS => 'Error dirección',
            self::DELIVERED_TO_WAREHOUSE => 'Entregado a bodega',
            self::PACKED => 'Empacado',
            self::DISPATCHED => 'Despachado',
            self::DELIVERED => 'Entregado',
            self::BLU_FAIL => 'Falla entrega bodega',
            self::NO_CREDIT => 'Sin crédito',
            self::NO_CITY => 'Falta ciudad'
        };
    }

    /**
     * @return int
     */
    public function ranking(): int
    {
        return match ($this) {
            self::NO_CARRIER,
            self::NO_STOCK,
            self::NO_FREIHT,
            self::NO_VALID_PARTNER,
            self::NO_VALID_ADDRESS,
            self::NO_CITY,
            self::NO_CREDIT,
            self::BLU_FAIL => 1,
            self::CLAIM_IN_WAREHOUSE => 2,
            self::DELIVERED_TO_WAREHOUSE=>3,
            self::PACKED => 4,
            self::DISPATCHED=> 5,
            self::DELIVERED => 6,
            self::CANCELED => 7,
        };
    }

    /**
     * @param string|null $code
     * @return bool|null
     */
    public static function getAvailableByCode(string|null $code): bool|null
    {
        return match ($code) {
            self::CLAIM_IN_WAREHOUSE->value,
            self::DELIVERED_TO_WAREHOUSE->value,
            self::DELIVERED->value,
            self::PACKED->value => true,
            self::NO_CREDIT->value,
            self::NO_CARRIER->value,
            self::NO_FREIHT->value,
            self::NO_STOCK->value,
            self::NO_CITY->value,
            self::NO_VALID_PARTNER->value,
            self::CANCELED->value,
            self::NO_VALID_ADDRESS->value,
            self::DISPATCHED->value,
            self::BLU_FAIL->value => false,
            default => null
        };
    }
}
