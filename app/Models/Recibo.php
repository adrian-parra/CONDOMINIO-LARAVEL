<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

use App\Utils\Meses;


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

    //this = recibo
    public function comprobarPago($cantidad_pagada, $pago, $excedente = 0)
    {
        //Se inicializa la variable que contendra el total que se debe
        //pagar en el recibo
        $monto_a_pagar = $this->getMontoAPagar();

        //Primero comprubea si la cantidad pagada es mayor que monto
        //Sino solo se agrega la cantidad pagada a monto_pagado y se
        //regresa
        if ($cantidad_pagada < $monto_a_pagar) {
            $this->monto_pagado = $cantidad_pagada;
            $this->save();
            return;
        }

        // $fecha_actual = date('Y-m-d');
        $fecha_actual = $this->fecha_vencimiento;

        //compara si la cantidad a pagar es menor que la cantidad pagada
        //para asi seguir con el siguiente recibo
        if ($monto_a_pagar < $cantidad_pagada) {
            //Se establece el recibo seleccionado como pagado.
            $this->estatus = 'PAGADO';
            $this->fecha_pago = date('Y-m-d');
            $this->monto_pagado = $this->monto;
            $this->save();

            //Excedente es un 'booleano', que determina si ya se llenó la anterior configuracion de pagos
            //de ser asi ya no buscara por configuracion si no por deuda mas antigua.
            $siguiente_recibo = null;

            if ($excedente == 0) {
                $siguiente_recibo = Recibo::where('fecha_vencimiento', '>', $fecha_actual)
                    ->where('fecha_pago', null)
                    ->where('propiedad_id', $this->propiedad_id)
                    ->where('configuracion_id', $this->configuracion_id)
                    ->orderBy('fecha_vencimiento', 'asc')
                    ->first();

                $excedente = $siguiente_recibo ? 0 : 1;
            }

            if ($excedente == 1 and !$siguiente_recibo) {
                //Se busca otro recibo aunque no sea de la misma configuracion
                //para asi asegurarse que no tenga deuda antes de guardar en
                //balance 
                $siguiente_recibo = Recibo::where('propiedad_id', $this->propiedad_id)
                    ->where('fecha_pago', null)
                    ->where('id', '!=', $this->id)
                    ->orderBy('fecha_vencimiento', 'asc')
                    ->first();
                Log::debug($siguiente_recibo);
            }

            //Se reduce el pago del recibo que ya se confirmo
            $cantidad_pagada = $cantidad_pagada - $monto_a_pagar;

            //Si no hay siguiente recibo procede a guardar
            //el saldo que sobra en la cacilla balance a favor.

            if (!$siguiente_recibo) {
                // Log::debug('Monto a guardar: ' . $cantidad_pagada);
                $propiedad = Propiedad::find($this->propiedad_id);
                $propiedad->balance_favor = $cantidad_pagada;
                $propiedad->save();
                //TODO: no llegar a esta parte hasta que se confirme que no tiene
                //ninguna otra deuda de otro recibo o configuracion.
                HistoricoBalance::create([
                    'monto' => $cantidad_pagada,
                    'tipo' => 'INCREMENTO',
                    'propiedad_id' => $this->propiedad_id,
                    'pago_id' => $pago->id
                ]);
            } else {
                //Se hace recursividad.
                $siguiente_recibo->comprobarPago($cantidad_pagada, $pago, $excedente);
            }
        }
    }

    public function getMontoAPagar()
    {
        return $this->monto - $this->monto_pagado;
    }

    public function getMesPago($string = 0)
    {
        $date = new DateTime($this->fecha_pago);
        $mes = intval($date->format('m'));

        if ($string == 1) {
            return Meses::obtenerMesString($mes);
        }

        return $mes;
    }
}
