<?php

namespace App\Services\LocationService\Impl;

use App\Services\LocationService\LocationService;
use App\Models\Location;
use Illuminate\Database\Eloquent\Model;

class LocationServiceImpl implements LocationService
{
    /**
     * @param $payload
     * @return void
     */
    public function create($payload): void
    {
        $data = [
            'id' => $payload['id'],
            'warehouse_id' => $payload['warehouse_id'][0] ?? null,
            'name' => $payload['complete_name']
        ];
        Location::updateOrCreate(['id' => $payload['id']], $data);
    }

    /**
     * @param $id
     * @return Model|Location
     */
    public function getById($id): Model|Location
    {
        return Location::with('city')->findOrFail($id);
    }

    /**
     * @param $id
     * @return Model|Location
     */
    public function getByPartnerId($id): Model|Location
    {
        return Location::with('city')
            ->where('partner_id', '=', $id)
            ->first();
    }
}
