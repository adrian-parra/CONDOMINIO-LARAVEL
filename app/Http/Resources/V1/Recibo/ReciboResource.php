<?php

namespace App\Http\Resources\V1\Recibo;

use App\Http\Resources\V1\Propiedad\PropiedadReciboResource;
use App\Http\Resources\V1\Propiedad\PropiedadResource;
use App\Models\Propiedad;
use Illuminate\Http\Resources\Json\JsonResource;

class ReciboResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $propiedad = Propiedad::find($this->propiedad_id);

        return [
            'id' => $this->id,
            'fechaPago' => $this->fecha_pago,
            'fechaVencimiento' => $this->fecha_vencimiento,
            'monto' => $this->monto - $this->monto_pagado,
            'montoPenalizacion' => $this->monto_penalizacion,
            'montoDescuento' => $this->monto_descuento,
            'estatus' => $this->estatus,
            'propiedad' => new PropiedadReciboResource($propiedad),
            'fraccionamientoId' => $this->fraccionamiento_id,
        ];
    }
}
