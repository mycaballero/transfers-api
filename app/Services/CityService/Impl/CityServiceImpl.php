<?php

namespace App\Services\CityService\Impl;

use App\Services\CityService\CityService;
use App\Models\City;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CityServiceImpl implements CityService
{

    public function __construct()
    {
    }

    /**
     * @param $payload
     * @return void
     */
    public function create($payload): void
    {
        $data = [
            'id' => $payload['id'],
            'name' => $payload['name']
        ];
        City::updateOrCreate($data);
    }

    /**
     * @param $id
     * @return City
     */
    public function getById($id): Collection|Builder
    {
        return City::findOrFail($id)->with('state')->get();
    }

    /**
     * @param string $name
     * @return Model
     */
    public function getByName(string $name): Model
    {
        return City::query()->where('name', 'LIKE', '%' . $name . '%')->first();
    }

}
