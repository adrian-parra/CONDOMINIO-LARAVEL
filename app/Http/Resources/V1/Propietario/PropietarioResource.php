<?php

namespace App\Http\Resources\V1\Propietario;

use App\Http\Resources\V1\Propiedad\PropiedadPropietarioResource;
use App\Http\Resources\V1\Vehiculo\VehiculoResource;
use App\Models\Vehiculo;
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
            'propiedad' => PropiedadPropietarioResource::collection($this->whenLoaded('propiedad')),
            'vehiculos' => VehiculoResource::collection($this->whenLoaded('vehiculos')),
        ];
    }
}
