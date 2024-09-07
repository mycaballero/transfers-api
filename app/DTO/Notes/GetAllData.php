<?php

namespace App\DTO\Notes;

use Spatie\LaravelData\Data;

class GetAllData extends Data
{
    /**
     * @param int|null $id
     * @param int|null $userId
     * @param int|null $pickingId
     * @param string|null $createdAt
     */
    public function __construct(
        public ?int    $id,
        public ?int    $userId,
        public ?int    $pickingId,
        public ?string $createdAt
    )
    {
    }
}
