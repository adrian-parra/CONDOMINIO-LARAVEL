<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\V1\PropiedadFilter;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\V1\Propiedad\StorePropiedadRequest;
use App\Http\Requests\V1\Propiedad\UpdatePropiedadRequest;
use App\Http\Resources\V1\Propiedad\PropiedadCollection;
use App\Http\Resources\V1\Propiedad\PropiedadResource;
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

        $mensaje = new mensaje();

        $mensaje->title = "Propiedades conseguidas exitosamente";
        $mensaje->icon = "success";
        $mensaje->body = new PropiedadCollection(
            $propiedades
                ->orderByDesc('id')
                ->get()
        );

        return response()->json($mensaje, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\V1\StorePropiedadRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePropiedadRequest $request)
    {
        $mensaje = new mensaje();
        // Obtener el archivo cargado del request
        $file = $request->file('archivoPredial');

        // Verificar si se cargó un archivo
        if (!$file) {
            $mensaje->title = "Propiedades obtenidas exitosamente";
            $mensaje->icon = "error";
            $mensaje->body = 'No se ha cargado un archivo';

            return response()->json($mensaje, 422);
        }

        $almacen = new AlmacenarArchivo($file, 'predial');

        $data = $request->all();

        $propiedad  = Propiedad::create($data);

        $data['predial_url'] = $almacen->storeFile();

        $mensaje->title = "Propiedad creada exitosamente";
        $mensaje->icon = "success";
        $mensaje->body = new PropiedadResource($propiedad);

        return response()->json($mensaje, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Propiedad  $propiedad
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $propiedad = Propiedad::find($id);

        $mensaje = new mensaje();

        $mensaje->title = "Propiedad conseguida exitosamente";
        $mensaje->icon = "success";
        $mensaje->body = new PropiedadResource($propiedad);

        return response()->json($mensaje, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePropiedadRequest  $request
     * @param  \App\Models\Propiedad  $propiedad
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePropiedadRequest $request, $id)
    {
        $propiedad = Propiedad::find($id);

        // Obtener el archivo cargado del request
        $file = $request->file('archivoPredial');

        $data = $request->all();

        // Verificar si se cargó un archivo
        if ($file) {
            $almacen = new AlmacenarArchivo($file, 'predial');

            $data['predial_url'] = $almacen->storeFile();
        }

        $mensaje = new mensaje();

        $propiedad->update($data);

        $mensaje->title = "Propiedad actualizada exitosamente";
        $mensaje->icon = "success";

        return response()->json($mensaje, 204);
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
