<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;

class StateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $states = [
                ['id' => 1, 'name' => 'AMAZONAS'],
                ['id' => 2, 'name' => 'ANTIOQUIA'],
                ['id' => 3, 'name' => 'ARAUCA'],
                ['id' => 4, 'name' => 'ATLÁNTICO'],
                ['id' => 5, 'name' => 'BOLÍVAR'],
                ['id' => 6, 'name' => 'BOYACÁ'],
                ['id' => 7, 'name' => 'CALDAS'],
                ['id' => 8, 'name' => 'CAQUETÁ'],
                ['id' => 9, 'name' => 'CASANARE'],
                ['id' => 10, 'name' => 'CAUCA'],
                ['id' => 11, 'name' => 'CESAR'],
                ['id' => 12, 'name' => 'CHOCÓ'],
                ['id' => 13, 'name' => 'CÓRDOBA'],
                ['id' => 14, 'name' => 'CUNDINAMARCA'],
                ['id' => 15, 'name' => 'GUAINÍA'],
                ['id' => 16, 'name' => 'GUAVIARE'],
                ['id' => 17, 'name' => 'GUERRERO'],
                ['id' => 18, 'name' => 'HUILA'],
                ['id' => 19, 'name' => 'LA GUAJIRA'],
                ['id' => 20, 'name' => 'MAGDALENA'],
                ['id' => 21, 'name' => 'META'],
                ['id' => 22, 'name' => 'NARIÑO'],
                ['id' => 23, 'name' => 'NORTE DE SANTANDER'],
                ['id' => 24, 'name' => 'PUTUMAYO'],
                ['id' => 25, 'name' => 'QUINDÍO'],
                ['id' => 26, 'name' => 'RISARALDA'],
                ['id' => 27, 'name' => 'SAN ANDRÉS Y PROVIDENCIA'],
                ['id' => 28, 'name' => 'SANTANDER'],
                ['id' => 29, 'name' => 'SUCRE'],
                ['id' => 30, 'name' => 'TOLIMA'],
                ['id' => 31, 'name' => 'VALLE DEL CAUCA'],
                ['id' => 32, 'name' => 'VAUPÉS'],
                ['id' => 33, 'name' => 'VICHADA'],
        ];

        foreach ($states as $state) {
            State::updateOrCreate(['id' => $state['id']], $state);
        }
    }
}
