<?php

namespace App\Http\Resources\V1\Propiedad\Interfon;

use Illuminate\Http\Resources\Json\JsonResource;

class ClaveInterfonResource extends JsonResource
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
            'numeroInterfon' => $this->numero_interfon,
            'codigoInterfon' => $this->codigo_interfon,
            'estatus' => $this->estatus
        ];
    }
}
