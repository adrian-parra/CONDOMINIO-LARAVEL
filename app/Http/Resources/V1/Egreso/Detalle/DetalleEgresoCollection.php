<?php

namespace App\Http\Resources\V1\Egreso\Detalle;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DetalleEgresoCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
