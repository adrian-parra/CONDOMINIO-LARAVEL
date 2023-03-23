<?php

namespace App\Http\Resources\V1\Recibo\Comprobante;

use Illuminate\Http\Resources\Json\JsonResource;

class ReciboComprobanteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'monto' => $this->monto,
            'comprobanteUrl' => $this->comprobante_url,
            'estatus' => $this->estatus,
            'tipoPago' => $this->tipo_pago,
            'razonRechazo' => $this->razon_rechazo,
            'reciboId' => $this->recibo_id,
            'propiedadId' => $this->propiedad_id,
            'fraccionamientoId' => $this->fraccionamiento_id,
        ];
    }
}
