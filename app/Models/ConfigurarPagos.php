<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;

class ConfigurarPagos extends Model
{
    use HasFactory;

    protected $PERIODOS = [
        "UNICO" => '1 days',
        "SEMANAL" => '7 days',
        "MENSUAL" => '1 month',
        "ANUAL" => '1 year',
    ];

    public function crearRecibos($cantidad, $fechaInicio = null)
    {
        $cantidadPeriodo = $this->PERIODOS[$this->periodo];

        $propiedades = Propiedad::all();

        $recibos = [];
        $mensaje = new mensaje();

        $ultimoRecibo = Recibo::where(
            'configuracion_id',
            '=',
            $this->id
        )->orderByDesc('id')->first();

        if ($ultimoRecibo && $fechaInicio < $ultimoRecibo->fecha_vencimiento) {
            $mensaje->title = "Error al crear recibos";
            $mensaje->icon = "error";
            $mensaje->body = "La fecha a iniciar es menor que la ultima generada";

            return $mensaje;
        }

        $fecha_actual = date('Y-m-d');
        // Saca la ultima fecha del ultimo recibo generado y la pone como actual.
        if ($fechaInicio) {
            $fecha_actual = $fechaInicio;
        }

        //Comprueba que no exista una fecha proporcionada por el usuario y si hay algun registro de anteriores recibos
        if (!$fechaInicio && $ultimoRecibo) {
            $fecha_actual = $ultimoRecibo->fecha_vencimiento;
        }

        for ($i = 0; $i < $cantidad; $i++) {
            $fecha_pago = date('Y-m-d', strtotime($fecha_actual . ' +' . $cantidadPeriodo));
            foreach ($propiedades as $propiedad) {
                $recibo = [
                    'fraccionamiento_id' => $propiedad->fraccionamiento_id,
                    'propietario_id' => $propiedad->propietario_id,
                    'configuracion_id' => $this->id,
                    'inquilino_id' => $propiedad->inquilino_id,
                    'fecha_vencimiento' => $fecha_pago,
                    'monto' => $this->monto,
                    'estatus' => 'POR_PAGAR'
                ];

                $recibos[] = $recibo;
            }
            $fecha_actual = $fecha_pago;
        }

        Recibo::insert($recibos);

        $mensaje->title = "Recibos credos exitosamente";
        $mensaje->icon = "success";

        return $mensaje;
    }
}
