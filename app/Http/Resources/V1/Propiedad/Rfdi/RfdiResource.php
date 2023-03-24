<?php

namespace App\Http\Resources\V1\Propiedad\Rfdi;

use App\Http\Resources\V1\Propiedad\PropiedadReciboResource;
use Illuminate\Http\Resources\Json\JsonResource;

class RfdiResource extends JsonResource
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
            'rfdi' => $this->rfdi,
            'tipo' => $this->tipo,
            'propiedadId' => new PropiedadReciboResource($this->propiedad_id),
            'fraccionamientoId' => $this->fraccionamiento_id
        ];
    }
}
