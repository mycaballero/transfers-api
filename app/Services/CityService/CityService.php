<?php

namespace App\Services\CityService;

use App\Models\City;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface CityService
{
    /**
     * @param $payload
     * @return void
     */
    public function create($payload): void;

    /**
     * @param $id
     * @return Collection|Builder
     */
    public function getById($id): Collection|Builder;

    /**
     * @param string $name
     * @return Model
     */
    public function getByName(string $name): Model;

}
