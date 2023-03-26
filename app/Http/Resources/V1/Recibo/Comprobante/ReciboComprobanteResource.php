<?php

namespace App\Http\Resources\V1\Recibo\Comprobante;

use App\Models\Recibo;
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
        $recibo = Recibo::find($this->recibo_id);

        return [
            'id' => $this->id,
            'monto' => $this->monto,
            'comprobanteUrl' => $this->comprobante_url,
            'mes' => $recibo->getMesPago(1),
            'estatus' => $this->estatus,
            'tipoPago' => $this->tipo_pago,
            'razonRechazo' => $this->razon_rechazo,
            'reciboId' => $this->recibo_id,
            'propiedadId' => $this->propiedad_id,
            'fraccionamientoId' => $this->fraccionamiento_id,
        ];
    }
}
