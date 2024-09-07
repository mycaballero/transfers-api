<?php

namespace Database\Seeders;


use App\Enums\User\PermissionEnum;
use App\Enums\User\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = PermissionEnum::cases();
        foreach ($permissions as $permission) {
            $currentPermission = Permission::where('name', $permission->value)->first();
            if (!isset($currentPermission)) {
                Permission::create([
                    'name' => $permission->value,
                    'description' => $permission->description()
                ]);
            }
        }

        $roles = array(
            [
                'name' => RoleEnum::SUPER_ADMINISTRADOR->value,
                'permissions' => RoleEnum::SUPER_ADMINISTRADOR->permissions()
            ],
            [
                'name' => RoleEnum::COMERCIAL->value,
                'permissions' => RoleEnum::COMERCIAL->permissions()
            ],
        );
        foreach ($roles as $roleDescription) {
            $role = Role::where('name', $roleDescription['name'])->first();
            $role->syncPermissions();
            foreach ($roleDescription['permissions'] as $permission) {
                $role->givePermissionTo($permission);
            }
            $role->save();
        }
    }
}

