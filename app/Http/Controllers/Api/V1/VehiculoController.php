<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\V1\Vehiculo\StoreVehiculoRequest;
use App\Http\Requests\V1\Vehiculo\UpdateVehiculoRequest;
use App\Models\EstadosDeMexico;
use App\Models\mensaje;
use App\Models\TipoVehiculo;
use App\Models\Vehiculo;
use App\Utils\AlmacenarArchivo;
use App\Utils\EliminarArchivo;
use Illuminate\Http\Request;

class VehiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $mensaje = new mensaje();
        $ConfigurarPagos = Vehiculo::where('id_fraccionamiento', $request->id_fraccionamiento)->
        where('estatus',1)->get();
        $mensaje->title = "";
        $mensaje->icon = "success";
        $mensaje->body = $ConfigurarPagos;

        return response()->json($mensaje, 200);
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

    public function getEstadosMexico()
    {

        $mensaje = new mensaje();
        try{
            $estados = EstadosDeMexico::all();
            $mensaje->title = "";
            $mensaje->icon ="success";
            $mensaje->body = $estados;
            return response()->json($mensaje, 200);
        }catch(\Exception $e){
            $mensaje->title = $e->getMessage();
            $mensaje->icon = "error";
            return response()->json($mensaje ,500);
        }
      

      
    }

    public function getTiposVehiculos()
    {
        $mensaje = new mensaje();
        try{
            
            $tiposVehiculos = TipoVehiculo::all();
            $mensaje->title = "";
            $mensaje->icon ="success";
            $mensaje->body = $tiposVehiculos;
            return response()->json($mensaje, 200);
        }catch(\Exception $e){
            $mensaje->title = $e->getMessage();
            $mensaje->icon = "error";
            return response()->json($mensaje ,500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreVehiculoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVehiculoRequest $request)
    {
        $mensaje = new mensaje();

        //GUARDAR DATOS DEL VEHICULO
        $vehiculo = new Vehiculo();
        $vehiculo->id_propiedad = $request->id_propiedad;
        $vehiculo->id_fraccionamiento = $request->id_fraccionamiento;
        $vehiculo->id_tipo_vehiculo = $request->id_tipo_vehiculo;
        $vehiculo->id_estado = $request->id_estado;
        $vehiculo->propietario_id = $request->propietario_id;
        $vehiculo->marca = $request->marca;
        $vehiculo->color = $request->color;
        $vehiculo->placas = $request->placas;
        $vehiculo->estatus = true;

        /**
         * ? VERIFICAR SI ARCHIVO TARJETA DE CIRCULACION SE HA RECIBIDO
         */
        if ($request->hasFile('tarjeta_circulacion')) {
            $file = $request->file('tarjeta_circulacion');

            //ALMACENO ARCHIVO EN RUTA PRIVATE
            $almacenarArchivo = new AlmacenarArchivo($file, 'private/tarjetasCirculacion');
            $path = $almacenarArchivo->storeFile();

            //REGISTRO PATCH DE DONDE SE GUARDO EL ARCHIVO
            $vehiculo->path_tarjeta_circulacion = $path;
        } else {
            $vehiculo->path_tarjeta_circulacion = null;
        }

        $vehiculo->save();

        $mensaje->title = "Datos almacenados";
        $mensaje->icon = "success";
        return response()->json($mensaje, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vehiculo  $vehiculo
     * @return \Illuminate\Http\Response
     */
    public function show(Vehiculo $vehiculo)
    {
        //
        $mensaje = new mensaje();
        try {
            //$vehiculo =$vehiculo::find(request()->vehiculo);
            $mensaje->title = "Vehiculo obtenido";
            $mensaje->icon = "success";
            $mensaje->body = $vehiculo;
            return response()->json($mensaje, 200);
        } catch (\Exception $e) {
            $mensaje->title = "Esta peticion no se pudo completar";
            $mensaje->icon = "error";
            return response()->json($mensaje, 422);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vehiculo  $vehiculo
     * @return \Illuminate\Http\Response
     */
    public function edit(Vehiculo $vehiculo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateVehiculoRequest  $request
     * @param  \App\Models\Vehiculo  $vehiculo
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVehiculoRequest $request, Vehiculo $vehiculo)
    {
        //
        $mensaje = new mensaje();


        $vehiculo->id_tipo_vehiculo = $request->id_tipo_vehiculo;
        $vehiculo->id_estado = $request->id_estado_emisor_placas;
        $vehiculo->marca = $request->marca;
        $vehiculo->color = $request->color;
        $vehiculo->propietario_id = $request->propietario_id;
        $vehiculo->placas = $request->placas;

        if ($request->hasFile('tarjeta_circulacion')) {
            //SI RECIBO UN ARCHIVO EN TARJETA DE CIRCULACION
            //ELIMINO EL ARCHIVO ANTERIOR SI EXISTE ALGUNO

            if ($vehiculo->patch_tarjeta_circulacion !== null) {
                $deleteFile = new EliminarArchivo($vehiculo->patch_tarjeta_circulacion);
                $deleteFile->deletePrivateFile();
            }

            $file = $request->file('tarjeta_circulacion');

            //ALMACENO ARCHIVO EN RUTA PRIVATE
            $almacenarArchivo = new AlmacenarArchivo($file, 'private/tarjetasCirculacion');
            $path = $almacenarArchivo->storeFile();

            //REGISTRO PATCH DE DONDE SE GUARDO EL ARCHIVO
            $vehiculo->patch_tarjeta_circulacion = $path;
        }

        $vehiculo->update();

        $mensaje->title = "Datos actualizados";
        $mensaje->icon = "success";
        return response()->json($mensaje, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vehiculo  $vehiculo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vehiculo $vehiculo)
    {
        //
    }
}
