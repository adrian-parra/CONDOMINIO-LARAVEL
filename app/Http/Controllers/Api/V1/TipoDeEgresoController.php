<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\V1\TipoEgresoFilter;
use App\Http\Requests\V1\TipoDeEgreso\StoreTipoDeEgresoRequest;
use App\Http\Requests\V1\TipoDeEgreso\UpdateTipoDeEgresoRequest;
use App\Http\Resources\V1\Egreso\TipoEgresoCollection;
use App\Http\Resources\V1\Egreso\TipoEgresoResource;
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
        $filter = new TipoEgresoFilter();

        $filterItems = $filter->transform($request);

        $egresos = TipoDeEgreso::where($filterItems);

        return new TipoEgresoCollection(
            $egresos
                ->orderByDesc('id')
                ->paginate()
                ->appends($request->query())
        );
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
     * @param  \App\Http\Requests\StoreTipoDeEgresoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTipoDeEgresoRequest $request)
    {
        return new TipoEgresoResource(TipoDeEgreso::create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TipoDeEgreso  $tipoDeEgreso
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tipoEgreso = TipoDeEgreso::find($id);

        if (!$tipoEgreso) {
            return response()->json(['message' => 'Tipo de egreso no encontrado'], 404);
        }

        return new TipoEgresoResource($tipoEgreso);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TipoDeEgreso  $tipoDeEgreso
     * @return \Illuminate\Http\Response
     */
    public function edit(TipoDeEgreso $tipoDeEgreso)
    {
        //
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
        $tipoEgreso = TipoDeEgreso::find($id);

        if (!$tipoEgreso) {
            return response()->json(['message' => 'Tipo de egreso no encontrado'], 404);
        }

        $tipoEgreso->update($request->all());

        return new TipoEgresoResource($tipoEgreso);
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
