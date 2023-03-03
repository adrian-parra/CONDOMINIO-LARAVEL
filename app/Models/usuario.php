<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class usuario extends Model
{
    use HasFactory;

    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'rol_por_usuario' ,'id_usuario','id_rol');
    }
}
