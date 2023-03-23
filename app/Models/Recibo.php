<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recibo extends Model
{
    use HasFactory;

    protected $fillable = [
        'fraccionamiento_id',
        'propiedad_id',
        'fecha_pago',
        'fecha_vencimiento',
        'monto',
        'monto_penalizacion',
        'monto_descuento',
        'estatus'
    ];


    public function comprobarPago($pago)
    {
        if ($pago->monto < $this->monto) {
            $this->monto_pagado = $pago->monto;
            $this->save();
            return;
        }

        $fecha_actual = date('Y-m-d');

        if ($pago->monto < $this->monto) {
            $siguiente_recibo = Recibo::where('fecha_vencimiento', '>', $fecha_actual)->first();

            if (!$siguiente_recibo) {
                //que hago wee
            }

            $siguiente_recibo->monto_pagado = $pago->monto - $this->monto;
            $siguiente_recibo->save();
        }

        //Se establece el recibo seleccionado como pagado.
        $this->estatus = 'PAGADO';
        $this->fecha_pago = $fecha_actual;
        $this->save();
    }
}
