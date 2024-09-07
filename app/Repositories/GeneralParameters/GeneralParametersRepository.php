<?php

namespace App\Repositories\GeneralParameters;

interface GeneralParametersRepository
{
    /**
     * @param string $parameterName
     * @return array|int|string|null
     */
    public function getValueByName(string $parameterName): array|int|string|null;
}
