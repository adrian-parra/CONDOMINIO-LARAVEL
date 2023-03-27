<?php

namespace App\Http\Resources\V1\Propiedad\Rfdi;

use App\Http\Resources\V1\Propiedad\PropiedadReciboResource;
use App\Models\Propiedad;
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

        $propiedad = Propiedad::find($this->propiedad_id);

        return [
            'rfdi' => $this->rfdi,
            'tipo' => $this->tipo,
            'estatus' => $this->estatus,
            'propiedadId' => new PropiedadReciboResource($propiedad),
            'fraccionamientoId' => $this->fraccionamiento_id
        ];
    }
}
