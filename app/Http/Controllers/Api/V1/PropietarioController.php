<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Propietario;
use Illuminate\Http\Request;
use App\Filters\V1\PropietarioFilter;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\V1\Propietario\StorePropietarioRequest;
use App\Http\Requests\V1\Propietario\UpdatePropietarioRequest;
use App\Http\Resources\V1\Propietario\PropietarioCollection;
use App\Http\Resources\V1\Propietario\PropietarioResource;
use App\Models\mensaje;
use App\Utils\AlmacenarArchivo;

class PropietarioController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $mensaje = new mensaje();
        $filter = new PropietarioFilter();
        $filterItems = $filter->transform($request); //[['column', 'operator', 'value']]

        $incluirPropiedades = $request->query('incluirPropiedades');
        $incluirVehiculos = $request->query('incluirVehiculos');

        $propietarios = Propietario::where($filterItems);

        if ($incluirPropiedades) {
            $propietarios = $propietarios->with('propiedad');
        }

        if ($incluirVehiculos) {
            $propietarios = $propietarios->with('vehiculos');
        }

        $mensaje->title = "";
        $mensaje->icon = "success";
        $mensaje->body = new PropietarioCollection(
            $propietarios
                ->orderByDesc('id')
                ->get()
        );

        return response()->json($mensaje, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\V1\StorePropietarioRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePropietarioRequest $request)
    {
        $mensaje = new mensaje();
        // Obtener el archivo cargado del request
        $file = $request->file('archivoIdentificacion');

        // Verificar si se cargó un archivo
        if (!$file) {
            $mensaje->title = "No se ha cargado un archivo";
            $mensaje->icon = "error";

            return response()->json($mensaje, 400);
        }

        $almacen = new AlmacenarArchivo($file, 'identificacion');

        $data = $request->all();

        $data['identificacion_url'] = $almacen->storeFile();

        $mensaje->title = "Propietario registrado exitosamente";
        $mensaje->icon = "success";
        $mensaje->body = new PropietarioResource(
            Propietario::create($data)
        );

        return response()->json($mensaje, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Propietario  $propietario
     * @return \Illuminate\Http\Response
     */
    public function show(Propietario $propietario)
    {
        $mensaje = new mensaje();
        $incluirPropiedades = request()->query('incluirPropiedades');
        $incluirVehiculos = request()->query('incluirVehiculos');


        if ($incluirPropiedades) {
            $propietario->loadMissing('propiedad');
        }

        if ($incluirVehiculos) {
            $propietario->loadMissing('vehiculos');
        }

        $mensaje->title = "Propietario conseguido exitosamente";
        $mensaje->icon = "success";
        $mensaje->body = new PropietarioResource($propietario);

        return response()->json($mensaje, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\V1\UpdatePropietarioRequest  $request
     * @param  \App\Models\Propietario  $propietario
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePropietarioRequest $request, Propietario $propietario)
    {
        $mensaje = new mensaje();
        // Obtener el archivo cargado del request
        $file = $request->file('archivoIdentificacion');

        $data = $request->all();

        // Verificar si se cargó un archivo
        if ($file) {
            $almacen = new AlmacenarArchivo($file, 'identificacion');

            $data['identificacion_url'] = $almacen->storeFile();
        }

        $propietario->update($data);

        $mensaje->title = "Propietario actualizado exitosamente";
        $mensaje->icon = "success";

        return response()->json($mensaje, 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Propietario  $propietario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Propietario $propietario)
    {
        //
    }
}
