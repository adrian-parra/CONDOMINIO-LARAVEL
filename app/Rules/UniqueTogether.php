<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UniqueTogether implements Rule
{
    protected $table;
    protected $columns;
    protected $excludeId;
    protected $mensaje;
    protected $excludeName;

    public function __construct($table, array $columns, $mensaje, $excludeId = null, $excludeName = 'id')
    {
        $this->table = $table;
        $this->columns = $columns;
        $this->excludeId = $excludeId;
        $this->mensaje = $mensaje;
        $this->excludeName = $excludeName;
    }

    protected $columns_json = [
        'fraccionamiento_id' => 'fraccionamientoId',
    ];

    public function passes($attribute, $value)
    {
        $query = DB::table($this->table)->where(function ($query) {
            foreach ($this->columns as $column) {

                if (array_key_exists($column, $this->columns_json)) {
                    $value = request($this->columns_json[$column]);
                } else {
                    $value = request($column);
                }

                $query->where($column, $value);
            }
        });

        if ($this->excludeId) {
            $query->where($this->excludeName, '!=', $this->excludeId);
        }

        return $query->count() === 0;
    }

    public function message()
    {
        return $this->mensaje;
    }
}
