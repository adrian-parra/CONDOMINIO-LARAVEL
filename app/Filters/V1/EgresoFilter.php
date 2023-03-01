<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;

class EgresoFilter extends ApiFilter
{
    protected $safeParams = [
        'isVerified' => ['eq'],
        'estatusEgresoId' => ['eq', 'ne'],
        'montoTotal' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'tipoEgresoId' => ['eq'],
        'fraccionamientoId' => ['eq'],
    ];

    //
    protected $columnMap = [
        'isVerified' => 'is_verified',
        'estatusEgresoId' => 'estatus_egreso_id',
        'montoTotal' => 'monto_total',
        'tipoEgresoId' => 'tipo_egreso_id',
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
