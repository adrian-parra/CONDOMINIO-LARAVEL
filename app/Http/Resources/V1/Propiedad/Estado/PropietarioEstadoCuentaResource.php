<?php

namespace App\Http\Resources\V1\Propiedad\Estado;

use Illuminate\Http\Resources\Json\JsonResource;

class PropietarioEstadoCuentaResource extends JsonResource
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
            "balance" => $this->balance,
        ];
    }
}
