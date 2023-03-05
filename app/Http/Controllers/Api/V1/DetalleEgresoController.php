<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\V1\Egreso\Detalle\StoreDetalleEgresoRequest;
use App\Http\Requests\V1\Egreso\Detalle\UpdateDetalleEgresoRequest;
use App\Models\DetalleEgreso;
use App\Models\mensaje;

class DetalleEgresoController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDetalleEgresoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDetalleEgresoRequest $request)
    {
        $mensaje = new mensaje();
        DetalleEgreso::create($request->all());

        $mensaje->title = "Registrado con éxito";
        $mensaje->icon = "success";

        return response()->json($mensaje, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDetalleEgresoRequest  $request
     * @param  \App\Models\DetalleEgreso  $detalleEgreso
     * @return \Illuminate\Http\Response
     */
    public function update_or_delete(UpdateDetalleEgresoRequest $request, $id_egreso, $id_producto)
    {
        $mensaje = new mensaje();
        $detalleEgreso  = DetalleEgreso::where('producto_id', '=', $id_producto)->where('egreso_id', '=', $id_egreso)->get()->first();;

        if (!$detalleEgreso) {

            $mensaje->title = "No se encontro un detalle egreso";
            $mensaje->icon = "error";

            return response()->json($mensaje, 404);
        }

        if (request()->isMethod('delete')) {
            $detalleEgreso->delete();

            $mensaje->title = "Borrado con éxito";
            $mensaje->icon = "success";

            return response()->json($mensaje, 204);
        }

        $detalleEgreso->update($request->all());

        $mensaje->title = "Actualizado con éxito";
        $mensaje->icon = "success";

        return response()->json($mensaje, 204);
    }
}
