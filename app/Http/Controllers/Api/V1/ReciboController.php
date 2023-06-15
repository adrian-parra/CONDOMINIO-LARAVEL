<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\V1\ReciboFilter;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\V1\Recibo\GenerarReciboRequest;
use App\Http\Resources\V1\Recibo\ReciboCollection;
use App\Http\Resources\V1\Recibo\ReciboResource;
use App\Models\ConfigurarPagos;
use App\Models\mensaje;
use App\Models\Recibo;
use Illuminate\Http\Request;

class ReciboController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $mensaje = new mensaje();
        $filter = new ReciboFilter();

        $filterItems = $filter->transform($request);

        $recibos = Recibo::where($filterItems);

        $mensaje->title = "";
        $mensaje->icon = "success";

        $mensaje->body = new ReciboCollection(
            $recibos
                ->orderByDesc('id')
                ->get()
        );

        return response()->json($mensaje, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Recibo  $recibo
     * @return \Illuminate\Http\Response
     */
    public function show(Recibo $recibo)
    {
        $mensaje = new mensaje();

        $mensaje->title = "Recibo conseguido exitosamente";
        $mensaje->icon = "success";
        $mensaje->body = new ReciboResource($recibo);

        return response()->json($mensaje, 200);
    }

    public function generar_recibos(GenerarReciboRequest $request)
    {
        $configuracion = ConfigurarPagos::find($request->configuracionId);

        if($request->year){
            $mensaje = $configuracion->crearRecibosAnual($request->year, $request->iniciarDesde);
        }

        if($request->plazoPorGenerar){
            $mensaje = $configuracion->crearRecibos($request->plazoPorGenerar, $request->inciarDesde);
        }


        if ($mensaje->icon == "error") {
            return response()->json($mensaje, 400);
        }

        return response()->json($mensaje, 201);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Recibo  $recibo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recibo $recibo)
    {
        //
    }
}
