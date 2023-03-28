<?php

namespace App\Http\Resources\V1\Egreso;

use App\Http\Resources\V1\Egreso\Detalle\DetalleEgresoCollection;
use App\Http\Resources\V1\Egreso\Detalle\DetalleEgresoResource;
use App\Http\Resources\V1\Proveedor\ProveedorResource;
use App\Models\Proveedor;
use App\Models\TipoDeEgreso;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class EgresoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $tipo_egreso = TipoDeEgreso::where('id', $this->tipo_egreso_id)->first();

        $tipoEstatusEgreso = [
            'APROBADO',
            'PENDIENTE',
            'CANCELADO'
        ];

        $objEstatusEgreso = [
            'id' => $this->estatus_egreso_id ? $this->estatus_egreso_id : 0,
            'descripcion' => $this->estatus_egreso_id ? $tipoEstatusEgreso[$this->estatus_egreso_id] : $tipoEstatusEgreso[0]
        ];

        $proveedor = Proveedor::find($this->proveedor_id);

        return [
            'id' => $this->id,
            'descripcion' => $this->descripcion,
            'isVerified' => $this->is_verified,
            'estatusEgreso' => $objEstatusEgreso,
            'montoTotal' => $this->monto_total,
            'comprobanteUrl' => $this->comprobante_url,
            'tipoPago' => $this->tipo_pago,
            'fechaPago' => $this->fecha_pago,
            'tipoEgreso' => new TipoEgresoResource($tipo_egreso),
            'fraccionamientoId' => $this->fraccionamiento_id,
            'proveedor' => new ProveedorResource($proveedor),
            'detalleEgreso' => DetalleEgresoResource::collection(
                $this->whenLoaded('detalleEgreso')
            )
        ];
    }
}
