<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;

class ReciboComprobanteFilter extends ApiFilter
{
    protected $safeParams = [
        'id' => ['eq'],
        'monto' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'estatus' => ['eq', 'ne'],
        'propiedadId' => ['eq'],
        'fraccionamientoId' => ['eq'],
        'tipoPago' => ['eq', 'ne'],
    ];

    protected $columnMap = [
        'propiedadId' => 'propiedad_id',
        'fraccionamientoId' => 'fraccionamiento_id',
        'tipoPago' => 'tipo_pago',
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
