<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\V1\ReciboComprobanteFilter;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\V1\Recibo\Comprobantes\StoreRecibosComprobanteRequest;
use App\Http\Requests\V1\Recibo\Comprobantes\UpdateRecibosComprobanteRequest;
use App\Http\Resources\V1\Recibo\Comprobante\ReciboComprobanteCollection;
use App\Http\Resources\V1\Recibo\Comprobante\ReciboComprobanteResource;
use App\Models\mensaje;
use App\Models\Propiedad;
use App\Models\Propietario;
use App\Models\Recibo;
use App\Models\RecibosComprobante;
use App\Utils\AlmacenarArchivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RecibosComprobanteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $mensaje = new mensaje();
        $filter = new ReciboComprobanteFilter();

        $filterItems = $filter->transform($request);

        $recibos = RecibosComprobante::where($filterItems);

        $mensaje->title = "";
        $mensaje->icon = "success";

        /**
         *     ! LINEA DE CODIGO QUE FUNCIONA SIMILAR A LOS RESOURCE EN LARAVEL,
         *     ! ME SERVIRA EN UN FUTURO...
         */ // ? $mensaje->body = $recibos->with('propiedad.propietario ,propiedad.tipo')->get();

        $mensaje->body = new ReciboComprobanteCollection(
            $recibos
                ->orderByDesc('id')
                ->get()
        );
    

        return response()->json($mensaje, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storerecibos_comprobanteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRecibosComprobanteRequest $request)
    {
        $mensaje = new mensaje();

        $file = $request->file('archivoComprobante');

        $data = $request->all();

        $almacen = new AlmacenarArchivo($file, 'private/comprobantes');

        $data['estatus'] = 'PENDIENTE';
        $data['comprobante_url'] = $almacen->storeFile();

        $pago = RecibosComprobante::create($data);

        $mensaje->title = "Pago registrado exitosamente";
        $mensaje->icon = "success";
        $mensaje->body = new ReciboComprobanteResource($pago);

        return response()->json($mensaje, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\recibos_comprobante  $recibos_comprobante
     * @return \Illuminate\Http\Response
     */
    public function show(RecibosComprobante $pago)
    {
        $mensaje = new mensaje();

        if (!$pago->id) {
            $mensaje->title = "Pago no encontrado";
            $mensaje->icon = "error";
            return response()->json($mensaje, 404);
        }

        $mensaje->title = "";
        $mensaje->icon = "success";

        $mensaje->body = new ReciboComprobanteResource($pago);

        return response()->json($mensaje, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updaterecibos_comprobanteRequest  $request
     * @param  \App\Models\recibos_comprobante  $recibos_comprobante
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRecibosComprobanteRequest $request, RecibosComprobante $pago)
    {

        $mensaje = new mensaje();
        $data = $request->all();

        $estatus = $data["estatus"];

        // if ($pago->estatus != 'PENDIENTE') {
        //     $mensaje->title = "Este pago ya no puede ser modificado ya que su estatus no lo permite";
        //     $mensaje->icon = "error";

        //     return response()->json($mensaje, 400);
        // }

        $propiedad = Propiedad::findOrFail($pago->propiedad_id);

        if ($estatus == 'FINALIZADO') {

            $recibo = Recibo::findOrFail($pago->recibo_id);

            $recibo->comprobarPago($pago->monto, $pago);
        }

        //En caso de ser rechazado
        if ($estatus == 'RECHAZADO') {
            //Comprobar que tenga razon de rechazo
            if (!array_key_exists('razonRechazo', $data)) {
                $mensaje->title = "No se a especificado la razon de rechazo";
                $mensaje->icon = "error";

                return response()->json($mensaje, 404);
            }

            if ($propiedad->inquilino_id) {
                $arrendatario = Propietario::findOrFail($propiedad->inquilino_id);
            } else {
                $arrendatario = Propietario::findOrFail($propiedad->propietario_id);
            }

            $pago->sendRechazoEmail($arrendatario, $data["razonRechazo"]);
        }

        $pago->update($data);

        $mensaje->title = "Pago actualizado exitosamente";
        $mensaje->icon = "success";

        return response()->json($mensaje, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\recibos_comprobante  $recibos_comprobante
     * @return \Illuminate\Http\Response
     */
    public function destroy(RecibosComprobante $recibos_comprobante)
    {
        //
    }
}
