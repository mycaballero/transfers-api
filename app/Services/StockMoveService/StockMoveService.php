<?php

namespace App\Services\StockMoveService;

interface StockMoveService
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
     * @param array $periods
     * @param array $restrictions
     * @return bool|array
     */
    public function getAll(array $periods, array $restrictions): bool|array;

    /**
     * @param $id
     * @param $fields
     * @return bool
     */
    public function update($id, $fields): bool;
}
