<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ConfigurarPagos extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_fraccionamiento',
        'descripcion',
        'tipo_pago',
        'monto',
        'fecha_inicial',
        'periodo',
        'dias_max_pago',
        'dias_max_descuento',
        'porcentaje_penalizacion',
        'porcentaje_descuento',
        'estatus',
    ];

    protected $PERIODOS = [
        "UNICO" => '0 days',
        "SEMANAL" => '7 days',
        "MENSUAL" => '1 month',
        "ANUAL" => '1 year',
    ];

    public function crearRecibos($cantidad, $fechaInicio = null)
    {
        $mensaje = new mensaje();

        if ($this->periodo == 'UNICO' and !$fechaInicio) {
            $mensaje->title = "Error al crear recibos";
            $mensaje->icon = "error";
            $mensaje->body = "Los recibos de pago unico no pueden ser regenerados";

            return $mensaje;
        }

        $cantidadPeriodo = $this->PERIODOS[$this->periodo];

        $propiedades = Propiedad::where('fraccionamiento_id', '=', $this->id_fraccionamiento);

        $recibos = [];

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
                $monto_pagado = 0;
                $recibo_pagado = false;

                if ($propiedad->balance_favor > 0) {
                    $monto_favor = $propiedad->balance_favor;

                    if ($monto_favor >= $this->monto) {
                        $monto_pagado = $this->monto;
                        $propiedad->balance_favor = $monto_favor - $this->monto;
                        $recibo_pagado = true;
                    } else if ($monto_favor < $this->monto) {
                        $monto_pagado = $monto_favor;
                        $propiedad->balance_favor = 0;
                    }

                    $propiedad->save();
                }

                $recibo = [
                    'fraccionamiento_id' => $propiedad->fraccionamiento_id,
                    'propiedad_id' => $propiedad->id,
                    'configuracion_id' => $this->id,
                    'fecha_vencimiento' => $fecha_pago,
                    'monto' => $this->monto,
                    'monto_pagado' => $monto_pagado,
                    'estatus' => $recibo_pagado ? 'PAGADO' : 'POR_PAGAR',
                    'fecha_pago' => $recibo_pagado ? date('Y-m-d') : null
                ];

                $recibos[] = $recibo;
            }
            $fecha_actual = $fecha_pago;
        }

        Recibo::insert($recibos);

        $recibos = Recibo::latest()->limit(count($recibos))->where('monto_pagado', '>', 0)->get();

        $historicos = [];

        foreach ($recibos as $recibo) {
            $historicos[] = [
                'monto' => $recibo->monto_pagado,
                'tipo' => 'DECREMENTO',
                'propiedad_id' => $recibo->propiedad_id,
                'pago_id' => $recibo->id
            ];
        }

        HistoricoBalance::insert($historicos);

        $mensaje->title = "Recibos credos exitosamente";
        $mensaje->icon = "success";

        return $mensaje;
    }

    // Funcion que crea recibos de la configuracion especificada segun el año
    // p.j. si viene una peticion que genere 2023 se generan todos los meses
    // suponiendo que el periodo de pago es mensual.
    public function crearRecibosAnual($year)
    {

        $mensaje = new mensaje();

        if ($this->periodo == 'UNICO') {
            $mensaje->title = "Error al crear recibos";
            $mensaje->icon = "error";
            $mensaje->body = "Los recibos de pago unico no pueden ser regenerados";

            return $mensaje;
        }

        $cantidadPeriodo = $this->PERIODOS[$this->periodo];

        $propiedades = Propiedad::where('fraccionamiento_id', '=', $this->id_fraccionamiento);

        $recibos = [];

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
            $fecha_actual = date('Y-m-d', strtotime($fecha_actual . ' -' . $cantidadPeriodo));
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
        } else if ($this->periodo == "ANUAL") {
            $cantidad = $startDate->diffInYears($endDate);
            if ($cantidad == 0 && !$ultimoRecibo) {
                $cantidad = 1;
            }
        }


        $recibos = $this->generar($cantidad, $propiedades, $fecha_actual, $cantidadPeriodo);

        Recibo::insert($recibos);

        $recibos = Recibo::where('monto_pagado', '>', 0)
            ->whereYear('fecha_vencimiento', $year)
            ->orderBy('id')
            ->take(count($recibos))
            ->get();

        $historicos = [];

        foreach ($recibos as $recibo) {
            $historicos[] = [
                'monto' => $recibo->monto_pagado,
                'tipo' => 'DECREMENTO',
                'propiedad_id' => $recibo->propiedad_id,
                'recibo_id' => $recibo->id,
                'created_at' => date('Y-m-d')
            ];
        }

        HistoricoBalance::insert($historicos);

        $mensaje->title = "Recibos credos exitosamente";
        $mensaje->icon = "success";

        return $mensaje;
    }


    protected function generar($cantidad, $propiedades, $fecha_actual, $cantidadPeriodo)
    {
        // Log::debug('=================================================');
        $recibos = [];

        for ($i = 0; $i < $cantidad; $i++) {
            $fecha_pago = date('Y-m-d', strtotime($fecha_actual . ' +' . $cantidadPeriodo));
            foreach ($propiedades as $propiedad) {
                $monto_pagado = 0;
                $recibo_pagado = false;

                if ($propiedad->balance_favor > 0) {
                    $monto_favor = $propiedad->balance_favor;

                    if ($monto_favor >= $this->monto) {
                        $monto_pagado = $this->monto;
                        $propiedad->balance_favor = $monto_favor - $this->monto;
                        $recibo_pagado = true;
                    } else if ($monto_favor < $this->monto) {
                        $monto_pagado = $monto_favor;
                        $propiedad->balance_favor = 0;
                    }

                    $propiedad->save();
                }

                $recibo = [
                    'fraccionamiento_id' => $propiedad->fraccionamiento_id,
                    'propiedad_id' => $propiedad->id,
                    'configuracion_id' => $this->id,
                    'fecha_vencimiento' => $fecha_pago,
                    'monto' => $this->monto,
                    'monto_pagado' => $monto_pagado,
                    'estatus' => $recibo_pagado ? 'PAGADO' : 'POR_PAGAR',
                    'fecha_pago' => $recibo_pagado ? date('Y-m-d') : null,
                    'created_at' => date('Y-m-d')
                ];

                $recibos[] = $recibo;
            }
            $fecha_actual = $fecha_pago;
        }

        return $recibos;
    }
}
