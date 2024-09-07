<?php

namespace App\Services\PickingService;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface PickingUtilityService
{

    /**
     * @param $locations
     * @return array
     */
    public function createEmptyLocations($locations): array;

    /**
     * @param $quantFiltered
     * @return int
     */
    public function calculateTotalAvailableQuantity($quantFiltered): int;

    /**
     * @param $locations
     * @param $locationId
     * @return bool|int|string
     */
    public function findLocationIndex($locations, $locationId): bool|int|string;

    /**
     * @param $locations
     * @return array
     */
    public function filterUniqueMoves($locations): array;

    /**
     * @param $quantFiltered
     * @param $cityPartner
     * @param $value
     * @param $saleOrder
     * @return array
     */
    public function filterQuantLocations($quantFiltered, $cityPartner, $value, $saleOrder): array;

    /**
     * @param $locationsGroups
     * @param $saleOrder
     * @return string|null
     */
    public function claimEventCreator($locationsGroups, $saleOrder): ?string;

    /**
     * @param $saleOrder
     * @param $quantFiltered
     * @return mixed
     */
    public function quantCondition($saleOrder,$quantFiltered): Collection|array;

    /**
     * @param $locationGroup
     * @return int
     */
    public function getLocationByRate($locationGroup): int;

    /**
     * @param $quants
     * @return int
     */
    public function countMoves($quants): int;

    /**
     * @param string $event
     * @param array $location
     * @param array $partner
     * @return string|null
     */
    public function getClaimEvent(string $event, array $location, array $partner): ?string;

    /**
     * @param $saleOrder
     * @param $partners
     * @param $picking
     * @param $moves
     * @param $cityPartner
     * @return mixed
     */
    public function canProcess($saleOrder, $partners, $picking, $moves, $cityPartner): mixed;
}
