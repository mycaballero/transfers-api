<?php

namespace Database\Seeders;

use App\Enums\User\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run(): void
    {
        foreach (RoleEnum::cases() as $role) {
            Role::updateOrCreate(['name' => $role->value], ['name' => $role->value]);
        }
    }
}
