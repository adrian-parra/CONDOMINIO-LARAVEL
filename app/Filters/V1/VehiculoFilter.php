<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;

class VehiculoFilter extends ApiFilter
{
    protected $safeParams = [
        'id_propiedad' => ['eq'],
        'id_tipo_vehiculo' => ['eq'],
        'id_fraccionamiento'=>['eq']
    ];

    protected $operatorMap = [
        'eq' => '=',
        'ne' => '!=',
    ];
}
