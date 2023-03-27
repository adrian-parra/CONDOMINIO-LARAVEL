<?php

namespace App\Http\Resources\V1\Proveedor;

use Illuminate\Http\Resources\Json\JsonResource;

class ProveedorResource extends JsonResource
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
            'nombre' => $this->nombre,
            'rfc' => $this->rfc,
            'nombreContacto' => $this->nombre_contacto,
            'correoContacto' => $this->correo_contacto,
            'notas' => $this->notas,
            'fraccionamientoId' => $this->fraccionamiento_id,
        ];
    }
}
