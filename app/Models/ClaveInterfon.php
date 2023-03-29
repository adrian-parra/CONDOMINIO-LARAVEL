<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaveInterfon extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_interfon',
        'codigo_interfon',
        'propiedad_id',
        'fraccionamiento_id',
        'estatus'
    ];

    public function propiedad()
    {
        return $this->belongsTo(Propiedad::class);
    }
}
