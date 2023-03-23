<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rfdi extends Model
{
    use HasFactory;

    protected $fillable = [
        'rfdi',
        'tipo',
        'propiedad_id',
        'fraccionamiento_id'
    ];

    public function propiedad()
    {
        return $this->belongsTo(Propiedad::class);
    }
}
