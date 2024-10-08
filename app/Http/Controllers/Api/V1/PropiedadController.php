<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\V1\HistorialBalancesFilter;
use App\Filters\V1\PropiedadFilter;
use App\Filters\V1\RfdiFilter;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\V1\Propiedad\Interfon\StoreClaveInterfonRequest;
use App\Http\Requests\V1\Propiedad\Interfon\UpdateClaveInterfonRequest;
use App\Http\Requests\V1\Propiedad\Rfdi\StoreRfdiRequest;
use App\Http\Requests\V1\Propiedad\Rfdi\UpdateRfdiRequest;
use App\Http\Requests\V1\Propiedad\SetBalancesPropiedad;
use App\Http\Requests\V1\Propiedad\StorePropiedadRequest;
use App\Http\Requests\V1\Propiedad\UpdatePropiedadRequest;
use App\Http\Resources\V1\Historial\Balance\HistorialBalanceCollection;
use App\Http\Resources\V1\Propiedad\Estado\PropiedadEstadoCuentaResource;
use App\Http\Resources\V1\Propiedad\Estado\PropietarioEstadoCuentaResource;
use App\Http\Resources\V1\Propiedad\PropiedadCollection;
use App\Http\Resources\V1\Propiedad\PropiedadResource;
use App\Http\Resources\V1\Propiedad\Rfdi\RfdiCollection;
use App\Http\Resources\V1\Propiedad\Rfdi\RfdiResource;
use App\Models\ClaveInterfon;
use App\Models\HistoricoBalance;
use App\Models\mensaje;
use App\Models\Propiedad;
use App\Models\Rfdi;
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

        $data = $request->all();

        if ($file) {
            $almacen = new AlmacenarArchivo($file, 'private/predial');

            $data['predial_url'] = $almacen->storeFile();
        }

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

        $mensaje->title = "";
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

    public function get_historial_balances(Request $request)
    {
        $filter = new HistorialBalancesFilter();
        $filterItems = $filter->transform($request); //[['column', 'operator', 'value']]

        $historial = HistoricoBalance::where($filterItems);

        $mensaje = new mensaje();

        $mensaje->title = "";
        $mensaje->icon = "success";
        $mensaje->body = new HistorialBalanceCollection(
            $historial
                ->orderByDesc('created_at')
                ->get()
        );

        return response()->json($mensaje, 200);
    }

    public function getRfdis(Request $request)
    {
        $filter = new RfdiFilter();
        $filterItems = $filter->transform($request);

        $rfdi = Rfdi::where($filterItems);

        $mensaje = new mensaje();

        $mensaje->title = "";
        $mensaje->icon = "success";
        $mensaje->body = new RfdiCollection(
            $rfdi
                ->orderByDesc('created_at')
                ->get()
        );

        return response()->json($mensaje, 200);
    }

    public function postRfdi(StoreRfdiRequest $request)
    {
        $mensaje = new mensaje();

        $data = $request->all();

        $rfdi  = Rfdi::create($data);

        $mensaje->title = "Rfdi registrada exitosamente";
        $mensaje->icon = "success";
        $mensaje->body = new RfdiResource($rfdi);

        return response()->json($mensaje, 201);
    }

    public function putRfdi(UpdateRfdiRequest $request, $id)
    {
        $mensaje = new mensaje();

        $rfdi = Rfdi::where('rfdi', $id)->first();

        if (!$rfdi) {
            $mensaje->title = "Rfdi no encontrado";
            $mensaje->icon = "error";
            return response()->json($mensaje, 400);
        }

        $data = $request->all();

        unset($data['propiedadId']);

        $rfdi->where('rfdi', $id)->update($data);

        $mensaje->title = "Rfdi actualizada exitosamente";
        $mensaje->icon = "success";

        return response()->json($mensaje, 200);
    }

    public function postInterfon(StoreClaveInterfonRequest $request)
    {
        $mensaje = new mensaje();

        $data = $request->all();

        ClaveInterfon::create($data);

        $mensaje->title = "Interfon registrado exitosamente";
        $mensaje->icon = "success";

        return response()->json($mensaje, 201);
    }

    public function putInterfon(UpdateClaveInterfonRequest $request, $interfonId)
    {
        $mensaje = new mensaje();

        $interfon = ClaveInterfon::where('numero_interfon', $interfonId)
            ->first();

        if (!$interfon) {
            $mensaje->title = "Interfon no encontrado";
            $mensaje->icon = "error";
            return response()->json($mensaje, 400);
        }

        $data = $request->all();

        unset($data['numeroInterfon']);
        unset($data['propiedadId']);
        unset($data['fraccionamientoId']);
        unset($data['codigoInterfon']);

        $interfon->where('numero_interfon', $interfonId)
            ->update($data);

        $mensaje->title = "Interfon actualizado exitosamente";
        $mensaje->icon = "success";

        return response()->json($mensaje, 200);
    }

    public function getEstadoDeCuentaPorPropiedad(Request $request, $id)
    {
        $mensaje = new mensaje();

        $propiedad = Propiedad::find($id);

        $propiedad->obtenerRecibos();

        $mensaje->title = "";
        $mensaje->icon = "success";
        $mensaje->body = new PropiedadEstadoCuentaResource($propiedad);

        return response()->json($mensaje, 200);
    }

    public function getBalanceGeneral(Request $request, $id)
    {
        $mensaje = new mensaje();

        $propiedades = Propiedad::where('propietario_id', $id)->get();

        // Log::debug($propiedades);

        $propiedad = Propiedad::find($id);
        $temp = new Propiedad();

        foreach ($propiedades as $propiedad) {
            $propiedad->obtenerRecibos();
            $temp->balance += $propiedad->balance;
        }

        $mensaje->title = "";
        $mensaje->icon = "success";
        $mensaje->body = new PropietarioEstadoCuentaResource($temp);

        return response()->json($mensaje, 200);
    }
}
