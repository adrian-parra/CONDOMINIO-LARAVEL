<?php

namespace App\Http\Resources\V1\Historial\Balance;

use Illuminate\Http\Resources\Json\JsonResource;

class HistorialBalanceResource extends JsonResource
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
            'monto' => $this->monto,
            'tipo' => $this->tipo,
            'propiedadId' => $this->propiedad_id,
            'pagoId' => $this->pago_id,
            'reciboId' => $this->recibo_id,
        ];
    }
}
