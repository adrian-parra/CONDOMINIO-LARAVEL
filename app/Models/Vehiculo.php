<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;

    protected $fillable = [
        'propietario_id'
    ];

    public function propietario()
    {
        return $this->belongsTo(Propietario::class);
    }
}
