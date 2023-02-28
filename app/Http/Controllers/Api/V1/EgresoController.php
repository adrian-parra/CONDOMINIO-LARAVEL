<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\V1\EgresoFilter;
use App\Http\Requests\V1\Egreso\StoreEgresoRequest;
use App\Http\Requests\V1\Egreso\UpdateEgresoRequest;
use App\Http\Resources\V1\Egreso\EgresoCollection;
use App\Http\Resources\V1\Egreso\EgresoResource;
use App\Models\Egreso;
use App\Utils\AlmacenarArchivo;
use Illuminate\Http\Request;

class EgresoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = new EgresoFilter();

        $filterItems = $filter->transform($request);
        $incluirDetalles = $request->query('incluirDetalles');

        $egresos = Egreso::where($filterItems);

        if ($incluirDetalles) {
            $egresos = $egresos->with('detalleEgreso');
        }

        return new EgresoCollection(
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
     * @param  \App\Http\Requests\StoreEgresoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEgresoRequest $request)
    {
        // Obtener el archivo cargado del request
        $file = $request->file('archivoComprobante');

        // Verificar si se cargó un archivo
        if (!$file) {
            return response()->json(['error' => 'No se ha cargado un archivo'], 400);
        }

        $almacen = new AlmacenarArchivo($file, 'egresos');

        $data = $request->all();

        $data['comprobante_url'] = $almacen->storeFile();

        return new EgresoResource(Egreso::create($data));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Egreso  $egreso
     * @return \Illuminate\Http\Response
     */
    public function show(Egreso $egreso)
    {
        $incluirDetalles = request()->query('incluirDetalles');

        if ($incluirDetalles) {
            return new EgresoResource($egreso->loadMissing('detalleEgreso'));
        }

        return new EgresoResource($egreso);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Egreso  $egreso
     * @return \Illuminate\Http\Response
     */
    public function edit(Egreso $egreso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEgresoRequest  $request
     * @param  \App\Models\Egreso  $egreso
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEgresoRequest $request, Egreso $egreso)
    {
        // Obtener el archivo cargado del request
        $file = $request->file('archivoComprobante');

        $data = $request->all();

        // Verificar si se cargó un archivo
        if ($file) {
            $almacen = new AlmacenarArchivo($file, 'egresos');

            $data['comprobante_url'] = $almacen->storeFile();
        }

        $egreso->update($data);

        return new EgresoResource($egreso);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Egreso  $egreso
     * @return \Illuminate\Http\Response
     */
    public function destroy(Egreso $egreso)
    {
        //
    }
}
