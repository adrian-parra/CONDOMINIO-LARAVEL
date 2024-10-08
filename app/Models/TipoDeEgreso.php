<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDeEgreso extends Model
{
    use HasFactory;

    protected $fillable = [
        'descripcion',
        'status',
        'fraccionamiento_id',
        'proveedor_id'
    ];
}
