<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\V1\EgresoFilter;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\V1\Egreso\StoreEgresoRequest;
use App\Http\Requests\V1\Egreso\UpdateEgresoRequest;
use App\Http\Resources\V1\Egreso\EgresoCollection;
use App\Http\Resources\V1\Egreso\EgresoResource;
use App\Models\Egreso;
use App\Models\mensaje;
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
        $mensaje = new mensaje();
        $filter = new EgresoFilter();

        $filterItems = $filter->transform($request);
        $incluirDetalles = $request->query('incluirDetalles');

        $egresos = Egreso::where($filterItems);

        if ($incluirDetalles) {
            $egresos = $egresos->with('detalleEgreso');
        }

        $mensaje->title = "";
        $mensaje->icon = "success";
        $mensaje->body = new EgresoCollection(
            $egresos
                ->orderByDesc('id')
                ->get()
        );

        return response()->json($mensaje, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreEgresoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEgresoRequest $request)
    {
        $mensaje = new mensaje();
        // Obtener el archivo cargado del request
        $file = $request->file('archivoComprobante');

        // Verificar si se cargó un archivo
        if (!$file) {
            return response()->json(['error' => 'No se ha cargado un archivo'], 400);
        }

        $almacen = new AlmacenarArchivo($file, 'egresos');

        $data = $request->all();

        $data['comprobante_url'] = $almacen->storeFile();

        $egreso = Egreso::create($data);

        $egreso->isVerified($request->method);

        $mensaje->title = "Egreso registrado exitosamente";
        $mensaje->icon = "success";
        $mensaje->body = new EgresoResource($egreso);

        return response()->json($mensaje, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Egreso  $egreso
     * @return \Illuminate\Http\Response
     */
    public function show(Egreso $egreso)
    {
        $mensaje = new mensaje();
        $incluirDetalles = request()->query('incluirDetalles');

        $mensaje->title = "Egreso conseguido con éxito";
        $mensaje->icon = "success";

        if ($incluirDetalles) {
            $mensaje->body = new EgresoResource($egreso->loadMissing('detalleEgreso'));
        } else {
            $mensaje->body = new EgresoResource($egreso);
        }

        return response()->json($mensaje, 200);
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
        $mensaje = new mensaje();
        // Obtener el archivo cargado del request
        $file = $request->file('archivoComprobante');

        $data = $request->all();

        // Verificar si se cargó un archivo
        if ($file) {
            $almacen = new AlmacenarArchivo($file, 'egresos');

            $data['comprobante_url'] = $almacen->storeFile();
        }


        $egreso->update($data);

        $egreso->isVerified($request->getMethod());

        $mensaje->title = "Egreso actualizado exitosamente";
        $mensaje->icon = "success";

        return response()->json($mensaje, 200);
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
