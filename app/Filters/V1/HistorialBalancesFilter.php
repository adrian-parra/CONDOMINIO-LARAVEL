<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;

class HistorialBalancesFilter extends ApiFilter
{
    protected $safeParams = [
        'monto' => ['eq', 'gt', 'gte', 'lt', 'lte'],
        'tipo' => ['eq', 'ne'],
        'propiedadId' => ['eq', 'ne'],
        'fraccionamientoId' => ['eq'],
    ];

    protected $columnMap = [
        'propiedadId' => 'propiedad_id',
        'fraccionamientoId' => 'fraccionamiento_id',
    ];

    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
        'ne' => '!='
    ];
}
