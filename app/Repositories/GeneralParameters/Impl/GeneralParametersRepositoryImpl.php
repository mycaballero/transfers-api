<?php

namespace App\Repositories\GeneralParameters\Impl;

use App\Enums\Picking\ParameterTypeEnum;
use App\Repositories\GeneralParameters\GeneralParametersRepository;
use App\Models\GeneralParameter;

class GeneralParametersRepositoryImpl implements GeneralParametersRepository
{
    /**
     * @param string $parameterName
     * @return array|int|string|null
     */
    public function getValueByName(string $parameterName): array|int|string|null
    {
        $parameter = GeneralParameter::where('name', $parameterName)->first();
        if ($parameter->type === ParameterTypeEnum::GROUP->value) {
            $parameter->value =  explode(", ", $parameter->value);
        }
        return $parameter->value;
    }
}
