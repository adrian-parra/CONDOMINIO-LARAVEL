<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\V1\StorePropiedadRequest;
use App\Http\Requests\V1\UpdatePropiedadRequest;
use App\Http\Resources\V1\PropiedadCollection;
use App\Filters\V1\PropiedadFilter;
use App\Http\Resources\V1\PropiedadResource;
use App\Models\mensaje;
use App\Models\Propiedad;
use App\Utils\AlmacenarArchivo;
use Illuminate\Http\Request;

class PropiedadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = new PropiedadFilter();
        $filterItems = $filter->transform($request); //[['column', 'operator', 'value']]

        $propiedades = Propiedad::where($filterItems);

        return new PropiedadCollection($propiedades->orderByDesc('id')->paginate()->appends($request->query()));
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
     * @param  \App\Http\Requests\V1\StorePropiedadRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePropiedadRequest $request)
    {
        // Obtener el archivo cargado del request
        $file = $request->file('archivoPredial');

        // Verificar si se cargó un archivo
        if (!$file) {
            return response()->json(['error' => 'No se ha cargado un archivo'], 400);
        }

        $almacen = new AlmacenarArchivo($file, 'predial');

        $data = $request->all();

        $data['predial_url'] = $almacen->storeFile();

        return new PropiedadResource(Propiedad::create($data));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Propiedad  $propiedad
     * @return \Illuminate\Http\Response
     */
    public function show(Propiedad $propiedad)
    {
        return new PropiedadResource($propiedad);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Propiedad  $propiedad
     * @return \Illuminate\Http\Response
     */
    public function edit(Propiedad $propiedad)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePropiedadRequest  $request
     * @param  \App\Models\Propiedad  $propiedad
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePropiedadRequest $request, Propiedad $propiedad)
    {
        // Obtener el archivo cargado del request
        $file = $request->file('archivoPredial');

        $data = $request->all();

        // Verificar si se cargó un archivo
        if ($file) {
            $almacen = new AlmacenarArchivo($file, 'predial');

            $data['predial_url'] = $almacen->storeFile();
        }

        $propiedad->update($data);

        return new PropiedadResource($propiedad);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Propiedad  $propiedad
     * @return \Illuminate\Http\Response
     */
    public function destroy(Propiedad $propiedad)
    {
        //
    }
}
