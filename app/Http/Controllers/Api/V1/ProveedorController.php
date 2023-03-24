<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\V1\ProveedorFilter;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\V1\Proveedor\StoreProveedorRequest;
use App\Http\Requests\V1\Proveedor\UpdateProveedorRequest;
use App\Http\Resources\V1\Proveedor\ProveedorCollection;
use App\Http\Resources\V1\Proveedor\ProveedorResource;
use App\Models\mensaje;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $mensaje = new mensaje();
        $filter = new ProveedorFilter();

        $filterItems = $filter->transform($request);

        $provedores = Proveedor::where($filterItems);

        $mensaje->title = "";
        $mensaje->icon = "success";
        $mensaje->body = new ProveedorCollection(
            $provedores
                ->orderByDesc('id')
                ->get()
        );

        return response()->json($mensaje, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProveedorRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProveedorRequest $request)
    {
        $mensaje = new mensaje();

        $mensaje->title = "Proveedor registrado exitosamente";
        $mensaje->icon = "success";
        $mensaje->body = new ProveedorResource(
            Proveedor::create($request->all())
        );

        return response()->json($mensaje, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Proveedor  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mensaje = new mensaje();
        $proveedor = Proveedor::find($id);

        if (!$proveedor) {
            $mensaje->title = "Proveedor no encontrado";
            $mensaje->icon = "error";
            return response()->json($mensaje, 404);
        }

        $mensaje->title = "Proveedor conseguido exitosamente";
        $mensaje->icon = "success";
        $mensaje->body = new ProveedorResource($proveedor);

        return response()->json($mensaje, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProveedorRequest  $request
     * @param  \App\Models\Proveedor  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProveedorRequest $request, $id)
    {
        $mensaje = new mensaje();

        $proveedor = Proveedor::find($id);

        if (!$proveedor) {
            $mensaje->title = "Proveedor no encontrado";
            $mensaje->icon = "error";
            return response()->json($mensaje, 404);
        }

        $proveedor->update($request->all());

        $mensaje->title = "Proveedor actualizado exitosamente";
        $mensaje->icon = "success";

        return response()->json($mensaje, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Proveedor  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Proveedor $proveedor)
    {
        //
    }
}
