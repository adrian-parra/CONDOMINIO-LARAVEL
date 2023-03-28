<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;

class PropietarioFilter extends ApiFilter
{
    protected $safeParams = [
        'nombre' => ['eq'],
        'apellidos' => ['eq'],
        'correo' => ['eq'],
        'celular' => ['eq'],
        'celularAlt' => ['eq'],
        'telefonoFijo' => ['eq'],
        'fraccionamientoId' => ['eq'],
        'isInquilino' => ['eq'],
    ];

    protected $columnMap = [
        'celularAlt' => 'celular_alt',
        'telefonoFijo' => 'telefono_fijo',
        'fraccionamientoId' => 'fraccionamiento_id',
        'isInquilino' => 'is_inquilino',
    ];

    protected $operatorMap = [
        'eq' => '=',
    ];
}
