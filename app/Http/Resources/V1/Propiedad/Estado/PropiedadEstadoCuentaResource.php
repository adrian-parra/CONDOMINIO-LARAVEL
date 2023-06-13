<?php

namespace App\Http\Resources\V1\Propiedad\Estado;

use App\Http\Resources\V1\Recibo\Estado\ReciboEstadoCuentaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PropiedadEstadoCuentaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $recibos_transformed = [];

        foreach ($this->recibos as $recibo) {
            $recibos_transformed[] = new ReciboEstadoCuentaResource($recibo);
        }

        return [
            "propietario" => $this->propietario,
            "balance" => $this->balance,
            "recibos" => $recibos_transformed
        ];
    }
}
