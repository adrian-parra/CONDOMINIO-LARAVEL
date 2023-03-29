<?php

namespace App\Http\Resources\V1\Historial\Balance;

use App\Http\Resources\V1\Propiedad\PropiedadReciboResource;
use App\Models\Propiedad;
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
        $propiedad = Propiedad::find($this->propiedad_id);
        return [
            'id' => $this->id,
            'monto' => $this->monto,
            'tipo' => $this->tipo,
            'propiedad' => new PropiedadReciboResource($propiedad),
            'pagoId' => $this->pago_id,
            'reciboId' => $this->recibo_id,
        ];
    }
}
