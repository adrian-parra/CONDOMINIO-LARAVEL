<?php

namespace App\Http\Resources\V1\Egreso;

use App\Http\Resources\V1\Egreso\Detalle\DetalleEgresoCollection;
use App\Http\Resources\V1\Egreso\Detalle\DetalleEgresoResource;
use App\Models\TipoDeEgreso;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'PENDIENTE APROVACION',
            'ACTIVO',
            'EDITADO',
            'ELIMINADO',
            'PAGADO',
            'COBRADO',
            'RECHAZADO',
        ];

        $objEstatusEgreso = [
            'id' => $this->estatus_egreso_id,
            'descripcion' => $tipoEstatusEgreso[$this->estatus_egreso_id]
        ];

        return [
            'id' => $this->id,
            'descripcion' => $this->descripcion,
            'isVerified' => $this->is_verified,
            'estatusEgreso' => $objEstatusEgreso,
            'montoTotal' => $this->monto_total,
            'comprobanteUrl' => $this->comprobante_url,
            'tipoEgreso' => new TipoEgresoResource($tipo_egreso),
            'fraccionamientoId' => $this->fraccionamiento_id,
            'detalleEgreso' => DetalleEgresoResource::collection(
                $this->whenLoaded('detalleEgreso')
            )
        ];
    }
}
