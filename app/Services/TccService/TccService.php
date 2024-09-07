<?php

namespace App\Services\TccService;

interface TccService
{
    /**
     * @param $origin
     * @param $destination
     * @param $value
     * @return int
     */
    public function settle($origin, $destination, $value): int;
}
