<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;

class PropiedadFilter extends ApiFilter
{
    protected $safeParams = [
        'tipoPropiedadId' => ['eq','ne'],
        'claveCatastral' =>  ['eq'],
        'superficie' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'balance' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'estatusId' => ['eq','ne'],
        'propietarioId' => ['eq'],
        'inquilinoId' => ['eq'],
        'fraccionamientoId' => ['eq'],
    ];

    protected $columnMap = [
        'tipoPropiedadId' => 'tipo_propiedad_id',
        'claveCatastral' =>  'clave_catastral',
        'estatusId' => 'estatus_id',
        'propietarioId' => 'propietario_id',
        'inquilinoId' => 'inquilino_id',
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
