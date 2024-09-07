<?php

namespace App\Services\User;

use App\DTO\User\SaveData;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;

interface UserService
{
    /**
     * @param SaveData $data
     * @return User
     */
    public function create(SaveData $data): User;

    /**
     * @param int $id
     * @param SaveData $data
     * @return User
     */
    public function update(int $id, SaveData $data): User;

    /**
     * @return Collection|Builder
     */
    public function getUsers(): Collection|Builder;

}
