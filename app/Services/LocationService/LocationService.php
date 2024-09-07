<?php

namespace App\Services\LocationService;

use App\Models\Location;
use Illuminate\Database\Eloquent\Model;

interface LocationService
{
    /**
     * @param $payload
     * @return void
     */
    public function create($payload): void;

    /**
     * @param $id
     * @return Model|Location
     */
    public function getById($id): Model|Location;

    /**
     * @param $id
     * @return Model|Location
     */
    public function getByPartnerId($id): Model|Location;
}
