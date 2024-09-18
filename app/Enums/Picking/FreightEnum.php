<?php

namespace App\Enums\Picking;


use App\Enums\EnumInterface;

enum FreightEnum: string implements EnumInterface
{
    case FREIGHT_HEAVY = 'FHY';
    case FREIGHT_CLIENT = 'FCT';

    /**
     * @return string
     */
    public function description(): string
    {
        return match ($this) {
            self::FREIGHT_HEAVY => 'Flete Pago x Heavy',
            self::FREIGHT_CLIENT => 'Flete Pago x Cliente',
        };
    }

}
