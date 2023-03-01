<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Propiedad extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo_propiedad_id',
        'predial_url',
        'clave_catastral',
        'descripcion',
        'superficie',
        'balance',
        'estatus_id',
        'propietario_id',
        'inquilino_id',
        'fraccionamiento_id',
    ];

    public function propietario()
    {
        return $this->belongsTo(Propietario::class);
    }
}
