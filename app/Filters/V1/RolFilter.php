<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class RolFilter extends ApiFilter
{
    protected $safeParams = [
        'descripcion' => ['eq','ne'],
        'estatus'=>['eq','ne']
    ];

    protected $operatorMap = [
        'eq' => '=',
        'ne' => '!=',
    ];

    public function applyFilter(Builder $builder, Request $request): Builder
    {
        $builder->whereNotIn('descripcion', ['ADMIN FRACCIONAMIENTO', 'ADMIN GENERAL']);
        return $builder;
    }
}
