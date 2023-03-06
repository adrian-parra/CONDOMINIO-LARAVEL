<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recibo extends Model
{
    use HasFactory;

    protected $fillable = [
        'fraccionamiento_id',
        'propiedad_id',
        'fecha_pago',
        'fecha_vencimiento',
        'monto',
        'monto_penalizacion',
        'monto_descuento',
        'estatus'
    ];
}
