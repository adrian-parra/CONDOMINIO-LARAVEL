<?php

namespace App\Http\Resources\V1\Recibo\Comprobante;

use App\Models\Propiedad;
use App\Models\Recibo;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\V1\Propiedad\PropiedadResource;
use App\Http\Resources\V1\Recibo\ReciboResource;


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
        $recibo = Recibo::findOrFail($this->recibo_id);
        $propiedad = Propiedad::find($this->propiedad_id);

        return [
            'id' => $this->id,
            'monto' => $this->monto,
            'comprobanteUrl' => $this->comprobante_url,
            'mes' => $recibo->getMesPago(1),
            'estatus' => $this->estatus,
            'tipoPago' => $this->tipo_pago,
            'razonRechazo' => $this->razon_rechazo,
            'recibo' => new ReciboResource($recibo),
            'propiedad' => new PropiedadResource($propiedad),
            'fraccionamientoId' => $this->fraccionamiento_id,
        ];
    }
}
