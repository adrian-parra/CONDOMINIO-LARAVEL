<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleEgreso extends Model
{
    use HasFactory;

    protected $fillable = [
        'egreso_id',
        'producto_id',
        'fraccionamiento_id',
        'descripcion',
        'cantidad',
        'precio_unitario'
    ];

    public function egreso()
    {
        return $this->belongsTo(Egreso::class);
    }
}
