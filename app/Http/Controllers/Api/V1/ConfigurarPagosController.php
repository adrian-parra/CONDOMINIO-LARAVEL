<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\V1\ConfiguracionDePagosFilter;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\V1\ConfigurarPagos\StoreConfigurarPagosRequest;
use App\Http\Requests\v1\ConfigurarPagos\UpdateConfigurarPagosRequest;
use App\Models\ConfigurarPagos;
use App\Models\mensaje;
use Illuminate\Http\Request;

class ConfigurarPagosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $mensaje = new mensaje();
        $filter = new ConfiguracionDePagosFilter();

        //VERIFICAR SI TIPO_PAGO ES IGUAL A (ALL)
        //SI ES ASI RETORNAR PAGOS ORDINARIO Y EXTRAORDINARIOS

        $filterItems = $filter->transform($request);

        $configurarPagos = ConfigurarPagos::where($filterItems);

        $mensaje->title = "";
        $mensaje->icon = "success";
        $mensaje->body = $configurarPagos->orderByDesc('id')->get();

        return response()->json($mensaje, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreConfigurarPagosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreConfigurarPagosRequest $request)
    {
        //
        try {
            $mensaje = new mensaje();
            $configurarPagos = new ConfigurarPagos();
            $configurarPagos->id_fraccionamiento = $request->id_fraccionamiento;
            $configurarPagos->descripcion = $request->descripcion;
            $configurarPagos->tipo_pago = $request->tipo_pago;
            $configurarPagos->monto = $request->monto;
            $configurarPagos->fecha_inicial = $request->fecha_inicial;
            $configurarPagos->periodo = $request->periodo;
            $configurarPagos->dias_max_pago = $request->dias_max_pago;
            $configurarPagos->dias_max_descuento = $request->dias_max_descuento;
            $configurarPagos->porcentaje_penalizacion = $request->porcentaje_penalizacion;
            $configurarPagos->porcentaje_descuento = $request->porcentaje_descuento;
            $configurarPagos->estatus = 1;
            $configurarPagos->save();

            $mensaje->title = "Configuracion de pago almacenado";
            $mensaje->icon = "success";
            return response()->json($mensaje, 422);
        } catch (\Exception $e) {
            $mensaje->title = "error";
            $mensaje->icon = $e->getMessage();
            return response()->json($mensaje, 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ConfigurarPagos  $configurarPagos
     * @return \Illuminate\Http\Response
     */
    public function show(ConfigurarPagos $configurarPagos)
    {
        //
        $mensaje = new mensaje();
        try {

            $configurarPagos = $configurarPagos::find(request()->configurar_pago);
            $mensaje->title = "Configuracion de pago obtenida";
            $mensaje->icon = "success";
            $mensaje->body = $configurarPagos;
            return response()->json($mensaje, 200);
        } catch (\Exception $e) {
            $mensaje->title = "Esta peticion no se pudo completar";
            $mensaje->icon = "error";
            return response()->json($mensaje, 422);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ConfigurarPagos  $configurarPagos
     * @return \Illuminate\Http\Response
     */
    public function edit(ConfigurarPagos $configurarPagos)
    {
        //
        return "hoal";
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateConfigurarPagosRequest  $request
     * @param  \App\Models\ConfigurarPagos  $configurarPagos
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateConfigurarPagosRequest $request, ConfigurarPagos $configurarPagos)
    {


        try {
            $mensaje = new mensaje();
            //$configurarPagos = ConfigurarPagos::find($request->configurar_pago);
            $configurarPagos = $configurarPagos::find($request->configurar_pago);

            //$configurarPagos->id_fraccionamiento = $request->id_fraccionamiento;
            $configurarPagos->descripcion = $request->descripcion;
            $configurarPagos->tipo_pago = $request->tipo_pago;
            $configurarPagos->monto = $request->monto;
            $configurarPagos->fecha_inicial = $request->fecha_inicial;
            $configurarPagos->periodo = $request->periodo;
            $configurarPagos->dias_max_pago = $request->dias_max_pago;
            $configurarPagos->dias_max_descuento = $request->dias_max_descuento;
            $configurarPagos->porcentaje_penalizacion = $request->porcentaje_penalizacion;
            $configurarPagos->porcentaje_descuento = $request->porcentaje_descuento;
            $configurarPagos->estatus = 1;
            $configurarPagos->update();
            $mensaje->title = "Configuracion de pago actualizado";
            $mensaje->icon = "success";
            return response()->json($mensaje, 422);
        } catch (\Exception $e) {
            $mensaje->title = "error";
            $mensaje->icon = $e->getMessage();
            return response()->json($mensaje, 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ConfigurarPagos  $configurarPagos
     * @return \Illuminate\Http\Response
     */
    public function destroy(ConfigurarPagos $configurarPagos)
    {
        //
    }
}
