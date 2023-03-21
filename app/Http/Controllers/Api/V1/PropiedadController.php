<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\V1\PropiedadFilter;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\V1\Propiedad\SetBalancesPropiedad;
use App\Http\Requests\V1\Propiedad\StorePropiedadRequest;
use App\Http\Requests\V1\Propiedad\UpdatePropiedadRequest;
use App\Http\Resources\V1\Propiedad\PropiedadCollection;
use App\Http\Resources\V1\Propiedad\PropiedadResource;
use App\Models\mensaje;
use App\Models\Propiedad;
use App\Utils\AlmacenarArchivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

        $mensaje->title = "";
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
            $mensaje->title = "No se ha cargado un archivo";
            $mensaje->icon = "error";

            return response()->json($mensaje, 422);
        }

        $almacen = new AlmacenarArchivo($file, 'private/predial');

        $data = $request->all();

        $data['predial_url'] = $almacen->storeFile();

        $propiedad  = Propiedad::create($data);

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
            $almacen = new AlmacenarArchivo($file, 'private/predial');

            $data['predial_url'] = $almacen->storeFile();
        }

        $mensaje = new mensaje();

        $propiedad->update($data);

        $mensaje->title = "Propiedad actualizada exitosamente";
        $mensaje->icon = "success";

        return response()->json($mensaje, 200);
    }

    public function delete_relacion_propietario(Request $request, $id)
    {
        Log::debug($id);

        $propiedad = Propiedad::find($id);

        $mensaje = new mensaje();
        $propietario = $request->query('propietario');

        if (!$propietario) {
            $act = [
                "inquilino_id" => null
            ];
            $tipo = "Inquilino";
        } else {
            $act = [
                "propietario_id" => null
            ];
            $tipo = "Propietario";
        }

        $propiedad->update($act);

        $mensaje->title = $tipo . " eliminado exitosamente";
        $mensaje->icon = "success";

        return response()->json($mensaje, 200);
    }

    public function set_balances_generales(SetBalancesPropiedad $request)
    {

        Propiedad::setFraccionamientoBalances($request->fraccionamientoId);

        $mensaje = new mensaje();

        $mensaje->title = "Balances Actualizados Correctamente";
        $mensaje->icon = "success";

        return response()->json($mensaje, 200);
    }
}
