<?php

namespace App\Filters;

use Illuminate\Http\Request;

class ApiFilter
{
    // Son las tipos de comparaciones que se pueden usar
    protected $safeParams = [];
    // Aqui van las columnas que se necesitan transformar a 
    // nombre de la coulmna en la base de datos
    protected $columnMap = [];
    //Traduccion de operaciones
    protected $operatorMap = [];

    public function transform(Request $request)
    {
        $eloQuery = [];

        foreach ($this->safeParams as $parm => $operators) {
            $query = $request->query($parm);

            if (!isset($query)) {
                continue;
            }

            $column = $this->columnMap[$parm] ?? $parm;

            foreach ($operators as $operator) {
                if (isset($query[$operator])) {
                    $eloQuery[] = [$column, $this->operatorMap[$operator], $query[$operator]];
                }
            }
        }

        return $eloQuery;
    }
}
