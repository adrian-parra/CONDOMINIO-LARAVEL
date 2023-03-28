<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;

class TipoEgresoFilter extends ApiFilter
{
    protected $safeParams = [
        'id' => ['eq'],
        'fraccionamientoId' => ['eq'],
    ];

    protected $columnMap = [
        'fraccionamientoId' => 'fraccionamiento_id'
    ];

    protected $operatorMap = [
        'eq' => '=',
    ];
}
