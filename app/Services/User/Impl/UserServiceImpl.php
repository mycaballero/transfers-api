<?php

namespace App\Services\User\Impl;

use App\DTO\User\SaveData;
use App\Enums\User\RoleEnum;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;
use Spatie\Permission\Models\Role;

class UserServiceImpl implements UserService
{
    /**
     * @param SaveData $data
     * @return User
     */
    public function create(SaveData $data): User
    {
        $payload = [
            'email' => $data->email,
            'name' => $data->name,
            'password' => $data->password,
            'terms_and_conditions' => $data->terms_and_conditions,
            'phone' => $data->phone,
        ];
        $user = User::create($payload);
        $role = Role::findByName(RoleEnum::VENTAS->value);
        $user->syncRoles($role);
        return $user;
    }

    /**
     * @param int $id
     * @param SaveData $data
     * @return User
     */
    public function update(int $id, SaveData $data): User
    {
        $payload = [
            'email' => $data->email,
            'name' => $data->name,
            'password' => $data->password,
            'terms_and_conditions' => $data->terms_and_conditions,
            'phone' => $data->phone,
        ];
        $user = User::findOrFail($id);
        $user->update($payload);
        return $user;
    }

    /**
     * @return Collection|Builder
     */
    public function getUsers(): Collection|Builder
    {
        return User::select('id', 'name')->get();
    }
}
