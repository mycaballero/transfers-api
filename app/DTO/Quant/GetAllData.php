<?php

namespace App\DTO\Quant;

use Spatie\LaravelData\Data;

class GetAllData extends Data
{
    /**
     * @param string|null $name
     * @param int|null $id
     */
    public function __construct(
        public ?string $name,
        public ?int    $id,
    )
    {
    }
}
