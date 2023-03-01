<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Egreso extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo_egreso_id',
        'descripcion',
        'is_verified',
        'estatus_egreso_id',
        'monto_total',
        'comprobante_url',
        'fraccionamiento_id'
    ];

    public function detalleEgreso()
    {
        return $this->hasMany(DetalleEgreso::class);
    }

    public function isVerified()
    {
        return $this->is_verified;
    }

    public function getFraccionamiento()
    {
        return $this->fraccionamiento_id;
    }
}
