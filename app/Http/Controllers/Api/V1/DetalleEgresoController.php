<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\V1\DetalleEgresoFilter;
use App\Http\Requests\StoreDetalleEgresoRequest;
use App\Http\Requests\UpdateDetalleEgresoRequest;
use App\Http\Resources\V1\Egreso\Detalle\DetalleEgresoCollection;
use App\Http\Resources\V1\Egreso\Detalle\DetalleEgresoResource;
use App\Http\Resources\V1\Egreso\EgresoResource;
use App\Models\DetalleEgreso;
use App\Models\Egreso;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use function GuzzleHttp\Promise\all;

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
        DetalleEgreso::create($request->all());

        return Response('Registrado con éxito', 201);
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
        $detalleEgreso  = DetalleEgreso::where('producto_id', '=', $id_producto)->where('egreso_id', '=', $id_egreso)->get()->first();;

        if (!$detalleEgreso) {
            return Response([
                'msg' => 'No se encontro un detalle egreso'
            ], 404);
        }

        if (request()->isMethod('delete')) {
            $detalleEgreso->delete();
            return Response([
                'msg' => 'Borrado con éxito'
            ], 204);
        }

        $detalleEgreso->update($request->all());

        return Response([
            'msg' => 'Actualizado con éxito'
        ], 200);
    }
}
