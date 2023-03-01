<?php

namespace App\Http\Resources\V1\Egreso\Detalle;

use App\Http\Resources\V1\Egreso\EgresoResource;
use App\Models\DetalleEgreso;
use App\Models\Egreso;
use App\Models\Producto;
use Illuminate\Http\Resources\Json\JsonResource;

class DetalleEgresoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $producto = Producto::find($this->producto_id);

        return [
            'producto' => $producto,
            'descripcion' => $this->descripcion,
            'cantidad' => $this->cantidad,
            'precio_unitario' => $this->precio_unitario
        ];
    }
}
