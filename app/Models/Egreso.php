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

    public function isVerified($method)
    {
        if (!$this->is_verified) return;

        $id = $this->fraccionamiento_id; //get fraccionamiento id from egreso

        $fraccionamiento = fraccionamiento::find($id);

        if ($fraccionamiento->getEgresosNeedToBeAuthorize()) {
            $fraccionamiento->sendEmailToUser($method);
        }
    }
}
