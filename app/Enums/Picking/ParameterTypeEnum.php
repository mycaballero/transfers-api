<?php

namespace App\Enums\Picking;

use App\Enums\EnumInterface;

enum ParameterTypeEnum: string implements EnumInterface
{

    case GROUP = 'GRP';
    case QUANT = 'QAT';
    case PRICE = 'PRC';


    /**
     * @inheritDoc
     */
    public function description(): string
    {
        return match ($this) {
            self::GROUP => 'grupo',
            self::QUANT => 'cantidad',
            self::PRICE => 'precio',
        };
    }
}
