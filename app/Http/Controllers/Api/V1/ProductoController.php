<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\V1\ProductoFilter;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\V1\Producto\StoreProductoRequest;
use App\Http\Requests\V1\Producto\UpdateProductoRequest;
use App\Http\Resources\V1\Producto\ProductoCollection;
use App\Http\Resources\V1\Producto\ProductoResource;
use App\Models\mensaje;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $mensaje = new mensaje();
        $filter = new ProductoFilter();

        $filterItems = $filter->transform($request); //[['column', 'operator', 'value']]

        $productos = Producto::where($filterItems);

        $mensaje->title = "Productos conseguidos con exitosamente";
        $mensaje->icon = "success";
        $mensaje->body = new ProductoCollection(
            $productos
                ->orderByDesc('id')
                ->get()
        );

        return response()->json($mensaje, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductoRequest $request)
    {
        $mensaje = new mensaje();
        $producto = Producto::create($request->all());

        $mensaje->title = "Producto registrado exitosamente";
        $mensaje->icon = "success";
        $mensaje->body = new ProductoResource($producto);

        return response()->json($mensaje, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Producto $producto)
    {
        $mensaje = new mensaje();
        $mensaje->title = "Producto conseguido con éxito";
        $mensaje->icon = "success";

        $mensaje->body = new ProductoResource($producto);

        return response()->json($mensaje, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductoRequest  $request
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductoRequest $request, Producto $producto)
    {
        $mensaje = new mensaje();
        $producto->update($request->all());

        $mensaje->title = "Producto actualizado exitosamente";
        $mensaje->icon = "success";

        return response()->json($mensaje, 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        //
    }
}
