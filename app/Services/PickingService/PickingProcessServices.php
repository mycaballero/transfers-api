<?php

namespace App\Services\PickingService;

interface PickingProcessServices
{
    /**
     * @param $picking
     * @return array
     */
    public function process($picking): array;

    /**
     * @param $moves
     * @param $cityPartner
     * @param $saleOrder
     * @return array
     */
    public function orderOrCreateMovesForPicking($moves, $cityPartner, $saleOrder): array;
}
