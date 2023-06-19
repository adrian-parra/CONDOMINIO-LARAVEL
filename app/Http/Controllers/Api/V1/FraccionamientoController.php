<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\V1\Fraccionamiento\GastosFraccionamientoResource;
use App\Models\fraccionamiento;
use App\Models\mensaje;

class FraccionamientoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getInformes($id)
    {
        $mensaje = new mensaje();

        $data = fraccionamiento::findOrFail($id);

        $data = $data->getEgresosIngresosMesFraccionamiento();

        $mensaje->title = "";
        $mensaje->icon = "success";
        $mensaje->body = $data;

        return response()->json($mensaje, 200);
    }
}
