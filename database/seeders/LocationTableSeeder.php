<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            [
                'id' => 8,
                'warehouse_id' => 1,
                'city_id' => 64,
                'name' => 'BM/Stock'
            ],
            [
                'id' => 21,
                'warehouse_id' => 2,
                'city_id' => 150,
                'name' => 'BB/Stock'
            ],
            [
                'id' => 29,
                'warehouse_id' => 3,
                'city_id' => 1009,
                'name' => 'BC/Stock',
            ],
        ];

        foreach ( $locations as $location) {
            Location::updateOrCreate(['id' => $location['id']], $location);
        }
    }
}
