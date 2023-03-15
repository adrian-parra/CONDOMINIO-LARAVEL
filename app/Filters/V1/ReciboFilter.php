<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;

class ReciboFilter extends ApiFilter
{
    protected $safeParams = [
        'id' => ['eq'],
        'propiedadId' => ['eq'],
        'fechaPago' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'fechaVencimiento' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'estatus' => ['eq', 'ne'],
        'fraccionamientoId' => ['eq'],
    ];

    protected $columnMap = [
        'propiedadId' => 'propiedad_id',
        'fechaPago' => 'fecha_pago',
        'fechaVencimiento' => 'fecha_vencimiento',
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
