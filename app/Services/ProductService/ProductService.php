<?php

namespace App\Services\ProductService;

interface ProductService
{
    /**
     * @param $payload
     * @return void
     */
    public function create($payload): void;

    /**
     * @return mixed
     */
    public function getLast(): mixed;

    /**
     * @param int|array $id
     * @return bool|array
     */
    public function getById(int|array $id): bool|array;

    /**
     * @param array $periods
     * @param array $restrictions
     * @return bool|array
     */
    public function getAll(array $periods = [], array $restrictions = []): bool|array;
}
