<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;

class PropiedadFilter extends ApiFilter
{
    protected $safeParams = [
        'tipoPropiedadId' => ['eq', 'ne'],
        'claveCatastral' =>  ['eq', 'lk'],
        'superficie' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'balance' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'estatusId' => ['eq', 'ne'],
        'propietarioId' => ['eq'],
        'inquilinoId' => ['eq'],
        'fraccionamientoId' => ['eq'],
        'is_inquilino' => ['eq'],
    ];

    protected $columnMap = [
        'tipoPropiedadId' => 'tipo_propiedad_id',
        'claveCatastral' =>  'clave_catastral',
        'estatusId' => 'estatus_id',
        'propietarioId' => 'propietario_id',
        'inquilinoId' => 'inquilino_id',
        'fraccionamientoId' => 'fraccionamiento_id',
        'isInquilino' => 'is_inquilino',
    ];

    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
        'ne' => '!=',
        'lk' => 'LIKE'
    ];
}
