<?php

namespace App\Http\Resources\V1\Propiedad\Rfdi;

use Illuminate\Http\Resources\Json\JsonResource;

class RfdiResourcePropiedad extends JsonResource
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
        ];
    }
}
