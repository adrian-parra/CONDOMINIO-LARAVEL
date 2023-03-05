<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;

class ReciboFilter extends ApiFilter
{
    protected $safeParams = [
        'id' => ['eq'],
        'propietarioId' => ['eq'],
        'inquilinoId' => ['eq'],
        'fechaPago' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'fechaVencimiento' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'estatus' => ['eq', 'ne'],
        'fraccionamientoId' => ['eq'],
    ];

    protected $columnMap = [
        'propietarioId' => 'propietario_id',
        'inquilinoId' => 'inquilino_id',
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
