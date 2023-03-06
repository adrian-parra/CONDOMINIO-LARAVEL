<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\V1\TipoEgresoFilter;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\V1\TipoDeEgreso\StoreTipoDeEgresoRequest;
use App\Http\Requests\V1\TipoDeEgreso\UpdateTipoDeEgresoRequest;
use App\Http\Resources\V1\Egreso\TipoEgresoCollection;
use App\Http\Resources\V1\Egreso\TipoEgresoResource;
use App\Models\mensaje;
use App\Models\TipoDeEgreso;
use Illuminate\Http\Request;

class TipoDeEgresoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $mensaje = new mensaje();
        $filter = new TipoEgresoFilter();

        $filterItems = $filter->transform($request);

        $egresos = TipoDeEgreso::where($filterItems);

        $mensaje->title = "";
        $mensaje->icon = "success";
        $mensaje->body = new TipoEgresoCollection(
            $egresos
                ->orderByDesc('id')
                ->get()
        );

        return response()->json($mensaje, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTipoDeEgresoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTipoDeEgresoRequest $request)
    {
        $mensaje = new mensaje();

        $mensaje->title = "Tipo de egreso registrado exitosamente";
        $mensaje->icon = "success";
        $mensaje->body = new TipoEgresoResource(
            TipoDeEgreso::create($request->all())
        );

        return response()->json($mensaje, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TipoDeEgreso  $tipoDeEgreso
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mensaje = new mensaje();
        $tipoEgreso = TipoDeEgreso::find($id);

        if (!$tipoEgreso) {
            $mensaje->title = "Tipo de egreso no encontrado";
            $mensaje->icon = "error";
            return response()->json($mensaje, 404);
        }
        $mensaje->title = "Tipo de egreso conseguido exitosamente";
        $mensaje->icon = "success";
        $mensaje->body = new TipoEgresoResource($tipoEgreso);

        return response()->json($mensaje, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTipoDeEgresoRequest  $request
     * @param  \App\Models\TipoDeEgreso  $tipoDeEgreso
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTipoDeEgresoRequest $request, $id)
    {
        $mensaje = new mensaje();
        $tipoEgreso = TipoDeEgreso::find($id);

        if (!$tipoEgreso) {
            $mensaje->title = "Tipo de egreso no encontrado";
            $mensaje->icon = "error";
            return response()->json($mensaje, 404);
        }

        $tipoEgreso->update($request->all());

        $mensaje->title = "Tipo de egreso actualizado exitosamente";
        $mensaje->icon = "success";

        return response()->json($mensaje, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TipoDeEgreso  $tipoDeEgreso
     * @return \Illuminate\Http\Response
     */
    public function destroy(TipoDeEgreso $tipoDeEgreso)
    {
        //
    }
}
