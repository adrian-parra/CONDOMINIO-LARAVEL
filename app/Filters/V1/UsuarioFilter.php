<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;

class UsuarioFilter extends ApiFilter
{
    protected $safeParams = [
        'id_fraccionamiento' => ['eq','ne']
    ];

    protected $operatorMap = [
        'eq' => '=',
        'ne' => '!=',
    ];
}
