<?php

namespace App\Services\PickingService;



use App\Models\Picking;
use Illuminate\Database\Eloquent\Collection;

interface PickingByProcessService
{

    /**
     * @param $picking
     * @param $locationId
     * @return Picking
     */
    public function duplicate($picking, $locationId): Picking;

    /**
     * @param $pickings
     * @return void
     */
    public function processPickingsFormOdoo($pickings): void;

    /**
     * @return array|bool
     */
    public function getPickingsWithTimeLimit(): Collection|array;

    /**
     * @return array|bool
     */
    public function getPickingsByEventLimit(): Collection|array;
}
