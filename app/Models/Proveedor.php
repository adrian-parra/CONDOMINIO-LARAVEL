<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'rfc',
        'nombre_contacto',
        'correo_contacto',
        'notas',
        'metodo_de_pago_id',
        'fraccionamiento_id',
    ];
}
