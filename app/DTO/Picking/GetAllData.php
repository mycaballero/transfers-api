<?php

namespace App\DTO\Picking;

use Spatie\LaravelData\Data;

class GetAllData extends Data
{
    /**
     * @param string|null $name
     * @param int|null $page
     * @param int|null $perPage
     * @param string|null $sort
     * @param string|null $sortBy
     */
    public function __construct(
        public ?string $name,
        public ?int    $page,
        public ?int    $perPage,
        public ?string $sort,
        public ?string $sortBy
    )
    {
    }
}
