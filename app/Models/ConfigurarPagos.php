<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

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

        $propiedades = Propiedad::where('fraccionamiento_id', '=', $this->id_fraccionamiento);

        $recibos = [];
        $mensaje = new mensaje();

        $ultimoRecibo = Recibo::where(
            'configuracion_id',
            '=',
            $this->id
        )->orderByDesc('id')->first();

        if ($ultimoRecibo && $fechaInicio && $fechaInicio < $ultimoRecibo->fecha_vencimiento) {
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

        $propiedades = $propiedades->get();

        for ($i = 0; $i < $cantidad; $i++) {
            $fecha_pago = date('Y-m-d', strtotime($fecha_actual . ' +' . $cantidadPeriodo));
            foreach ($propiedades as $propiedad) {
                $recibo = [
                    'fraccionamiento_id' => $propiedad->fraccionamiento_id,
                    'propiedad_id' => $propiedad->id,
                    'configuracion_id' => $this->id,
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

    // Funcion que crea recibos de la configuracion especificada segun el año
    // p.j. si viene una peticion que genere 2023 se generan todos los meses
    // suponiendo que el periodo de pago es mensual.
    public function crearRecibosAnual($year)
    {
        $cantidadPeriodo = $this->PERIODOS[$this->periodo];

        $propiedades = Propiedad::where('fraccionamiento_id', '=', $this->id_fraccionamiento);

        $recibos = [];
        $mensaje = new mensaje();

        $ultimoRecibo = Recibo::where(
            'configuracion_id',
            '=',
            $this->id
        )->orderByDesc('id')->first();

        $fecha_actual = date('Y-m-d');

        $propiedades = $propiedades->get();

        if ($fecha_actual < $year . '-01-00') {
            $fecha_actual = $year . '-01-00';
            $fecha_actual = date('Y-m-d', strtotime(
                $year . '-01-00' . ' +' . $this->dias_max_pago . ' days'
            ));
        }

        $startDate = \Carbon\Carbon::parse($fecha_actual);
        $endDate = \Carbon\Carbon::parse($year . '-12-31');

        if ($ultimoRecibo && $startDate < $ultimoRecibo->fecha_vencimiento) {
            $mensaje->title = "Error al crear recibos";
            $mensaje->icon = "error";
            $mensaje->body = "Ya han sido generados los recibos para el año especificado";

            return $mensaje;
        }

        //Esta condicion sirve para obtener la diferencia entre las fechas
        //para ejecutar las veces que sea necesarias el crear segun el periodo de pago.
        if ($this->periodo == "SEMANAL") {
            $cantidad = $startDate->diffInWeeks($endDate);
        } else if ($this->periodo == "MENSUAL") {
            $cantidad = $startDate->diffInMonths($endDate);
            // TODO: Que ondas con el pago anual, se tiene que pedir en que fecha se cobrará?
        } else if ($this->periodo == "ANUAL") {
            $cantidad = $startDate->diffInYears($endDate);
            if ($cantidad == 0 && !$ultimoRecibo) {
                $cantidad = 1;
            }
        }


        for ($i = 0; $i < $cantidad; $i++) {
            $fecha_pago = date('Y-m-d', strtotime($fecha_actual . ' +' . $cantidadPeriodo));
            foreach ($propiedades as $propiedad) {
                $recibo = [
                    'fraccionamiento_id' => $propiedad->fraccionamiento_id,
                    'propiedad_id' => $propiedad->id,
                    'configuracion_id' => $this->id,
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
