<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;

class RfdiFilter extends ApiFilter
{
    protected $safeParams = [
        'rfdi' => ['eq'],
        'fraccionamientoId' => ['eq'],
        'propiedadId' => ['eq'],
    ];

    protected $columnMap = [
        'fraccionamientoId' => 'fraccionamiento_id',
        'propiedadId' => 'propiedad_id',
    ];

    protected $operatorMap = [
        'eq' => '=',
    ];
}