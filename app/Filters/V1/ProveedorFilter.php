<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;

class ProveedorFilter extends ApiFilter
{
    protected $safeParams = [
        'nombre' => ['eq'],
        'rfc' => ['eq'],
        'metodoDePagoId' => ['eq', 'ne'],
        'fraccionamientoId' => ['eq'],
    ];

    protected $columnMap = [
        'metodoDePagoId' => 'metodo_de_pago_id',
        'fraccionamientoId' => 'fraccionamiento_id',
    ];

    protected $operatorMap = [
        'eq' => '=',
        'ne' => '!=',
    ];
}
