<?php


namespace Database\Seeders;

use App\Enums\User\RoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Miguel Caballero',
                'first_name' => 'Miguel',
                'last_name' => 'Caballero',
                'email' => 'miguel.caballero@activeone.co',
                'password' => bcrypt('Miguel7466953*'),
                'role' => RoleEnum::SUPER_ADMINISTRADOR->value
            ],
        ];
        foreach ($users as $user) {
            $role = $user['role'];
            unset($user['role']);
            $currentUser = User::updateOrCreate(['email' => $user['email']], $user);
            $currentUser->syncRoles([$role]);
        }
    }
}
