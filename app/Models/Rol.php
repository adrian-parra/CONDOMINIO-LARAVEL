<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'rol';

    public function users()
    {
        return $this->belongsToMany(usuario::class, 'rol_por_usuario' ,'id_rol','id_usuario');
    }
}
