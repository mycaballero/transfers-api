<?php

namespace Database\Seeders;

use App\Enums\Picking\ParameterTypeEnum;
use App\Models\GeneralParameter;
use Illuminate\Database\Seeder;

class GeneralParameterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parameters = [
            [
                'name' => 'price',
                'description' => 'precio estimado de venta',
                'value' => 1500000,
                'type' => ParameterTypeEnum::PRICE->value
            ],
        ];

        foreach ($parameters as $parameter) {
            GeneralParameter::updateOrcreate(['name' => $parameter['name']],$parameter);
        }
    }
}
