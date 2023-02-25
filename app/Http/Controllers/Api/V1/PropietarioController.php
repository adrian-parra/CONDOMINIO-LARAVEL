<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Propietario;
use Illuminate\Http\Request;
use App\Filters\V1\PropietarioFilter;
use App\Http\Requests\V1\Propietario\StorePropietarioRequest;
use App\Http\Requests\V1\Propietario\UpdatePropietarioRequest;
use App\Http\Resources\V1\Propietario\PropietarioCollection;
use App\Http\Resources\V1\Propietario\PropietarioResource;
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
        $filter = new PropietarioFilter();
        $filterItems = $filter->transform($request); //[['column', 'operator', 'value']]

        $incluirPropiedades = $request->query('incluirPropiedades');

        $propietarios = Propietario::where($filterItems);

        if ($incluirPropiedades) {
            $propietarios = $propietarios->with('propiedad');
        }

        return new PropietarioCollection($propietarios->orderByDesc('id')->paginate()->appends($request->query()));
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
     * @param  \App\Http\Requests\V1\StorePropietarioRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePropietarioRequest $request)
    {
        // Obtener el archivo cargado del request
        $file = $request->file('archivoIdentificacion');

        // Verificar si se cargó un archivo
        if (!$file) {
            return response()->json(['error' => 'No se ha cargado un archivo'], 400);
        }

        $almacen = new AlmacenarArchivo($file, 'identificacion');

        $data = $request->all();

        $data['identificacion_url'] = $almacen->storeFile();

        return new PropietarioResource(Propietario::create($data));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Propietario  $propietario
     * @return \Illuminate\Http\Response
     */
    public function show(Propietario $propietario)
    {
        $incluirPropiedades = request()->query('incluirPropiedades');

        if ($incluirPropiedades) {
            return new PropietarioResource($propietario->loadMissing('propiedad'));
        }

        return new PropietarioResource($propietario);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Propietario  $propietario
     * @return \Illuminate\Http\Response
     */
    public function edit(Propietario $propietario)
    {
        //
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
        // Obtener el archivo cargado del request
        $file = $request->file('archivoIdentificacion');

        $data = $request->all();

        // Verificar si se cargó un archivo
        if ($file) {
            $almacen = new AlmacenarArchivo($file, 'identificacion');

            $data['identificacion_url'] = $almacen->storeFile();
        }

        $propietario->update($data);

        return new PropietarioResource($propietario);
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
