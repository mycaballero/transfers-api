<?php

namespace App\Enums\Picking;

use App\Enums\EnumInterface;

enum StatusEnum: String implements EnumInterface
{

    case ASSIGNED = 'assigned';

    case CONFIRMED = 'confirmed';

    case CANCELED = 'canceled';

    /**
     * @inheritDoc
     */
    public function description(): string
    {
        return match ($this) {
          self::ASSIGNED => 'Asignado',
          self::CONFIRMED => 'Confirmado',
          self::CANCELED => 'Cancelado',
        };
    }
}
