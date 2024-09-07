<?php

namespace App\Enums\Picking;


use App\Enums\EnumInterface;

enum CarrierEnum: string implements EnumInterface
{
    case EXXE = 'EXX';
    case TCC = 'TCC';
    case ENVIA = 'ENV';
    case TRANSPRENSA = 'TRA';
    case LOGIFUTURO = 'LOG';
    case ROA = 'ROA';
    case HEAVY = 'HVY';


    public function description(): string
    {
        return match ($this) {
            self::EXXE => 'EXXE',
            self::TCC => 'TCC',
            self::ENVIA => 'ENVIA',
            self::TRANSPRENSA => 'TRANSEPRENSA',
            self::LOGIFUTURO => 'LOGIFUTURO',
            self::ROA => 'ROA',
            self::HEAVY => 'HEAVY',
        };
    }

}
