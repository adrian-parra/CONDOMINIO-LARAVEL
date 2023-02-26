<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;

class ProductoFilter extends ApiFilter
{
    protected $safeParams = [
        'identificadorInterno' => ['eq'],
        'proveedorId' => ['eq', 'ne'],
        'fraccionamientoId' => ['eq'],
    ];

    protected $columnMap = [
        'identificadorInterno' => 'identificador_interno',
        'proveedorId' => 'proveedor_id',
        'fraccionamientoId' => 'fraccionamiento_id',
    ];

    protected $operatorMap = [
        'eq' => '=',
    ];
}
