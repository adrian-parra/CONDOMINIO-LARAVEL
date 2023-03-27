<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricoBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'monto',
        'tipo',
        'propiedad_id',
        'pago_id',
        'recibo_id'
    ];
}
