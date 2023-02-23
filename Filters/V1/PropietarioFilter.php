<?php

namespace App\Filters\V1;

use Illuminate\Http\Request;
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
        'claveInterfon' => ['eq'],
        'claveInterfonAlt' => ['eq'],
    ];

    protected $columnMap = [
        'celularAlt' => 'celular_alt',
        'telefonoFijo' => 'telefono_fijo',
        'fraccionamientoId' => 'fraccionamiento_id',
        'claveInterfon' => 'clave_interfon',
        'claveInterfonAlt' => 'clave_interfon_alt',
    ];

    protected $operatorMap = [
        'eq' => '=',
    ];
}
