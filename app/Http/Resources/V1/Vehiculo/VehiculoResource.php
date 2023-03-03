<?php

namespace App\Http\Resources\V1\Vehiculo;

use Illuminate\Http\Resources\Json\JsonResource;

class VehiculoResource extends JsonResource
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
            'propiedadId' => $this->id_propiedad,
            'fraccionamientoId' => $this->id_fraccionamiento,
            'estadoId' => $this->id_estado,
            'TipoVehiculoId' => $this->id_tipo_vehiculo,
            'marca' => $this->marca,
            'pathTarjetaCirculacion' => $this->path_tarjeta_circulacion,
            'color' => $this->color,
            'placas' => $this->placas,
            'estatus' => $this->estatus,
        ];
    }
}
