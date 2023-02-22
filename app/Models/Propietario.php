<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Propietario extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'apellidos',
        'correo',
        'celular',
        'celular_alt',
        'telefono_fijo',
        'identificacion_url',
        'is_inquilino',
        'fracc_id',
        'clave_interfon',
        'clave_interfon_alt'
    ];

    public function propiedad()
    {
        return $this->hasMany(Propiedad::class);
    }
}
