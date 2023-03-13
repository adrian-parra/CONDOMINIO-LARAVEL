<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;

class ConfiguracionDePagosFilter extends ApiFilter
{
    protected $safeParams = [
        'periodo' => ['eq'],
        'fechaInicial' => ['eq', 'gt', 'gte'],
        'monto' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'tipoPago' => ['eq'],
        'estatus' => ['eq'],
        'fraccionamientoId' => ['eq'],
    ];

    //
    protected $columnMap = [
        'tipoPago' => 'tipo_pago',
        'fechaInicial' => 'fecha_inicial',
        'fraccionamientoId' => 'fraccionamiento_id',
    ];

    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
    ];
}
