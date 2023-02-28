<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;

class TipoEgresoFilter extends ApiFilter
{
    protected $safeParams = [
        'id' => ['eq'],
    ];

    protected $columnMap = [
    ];

    protected $operatorMap = [
        'eq' => '=',
    ];
}
