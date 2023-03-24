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
        'fraccionamiento_id',
    ];

    public function propiedad()
    {
        return $this->hasMany(Propiedad::class);
    }

    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class);
    }
}
