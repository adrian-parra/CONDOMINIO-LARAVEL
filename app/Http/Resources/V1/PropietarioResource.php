<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class PropietarioResource extends JsonResource
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
            'apellidos' => $this->apellidos,
            'correo' => $this->correo,
            'celular' => $this->celular,
            'celularAlt' => $this->celular_alt,
            'telefonoFijo' => $this->telefono_fijo,
            'identificacionUrl' => $this->identificacion_url,
            'isInquilino' => $this->is_inquilino,
            'fraccionamientoId' => $this->fraccionamiento_id,
            'claveInterfon' => $this->clave_interfon,
            'claveInterfonAlt' => $this->clave_interfon_alt,
            'propiedad' => PropiedadPropietarioResource::collection($this->whenLoaded('propiedad')),
        ];
    }
}
