<?php

namespace App\Http\Resources\V1\Producto;

use App\Http\Resources\V1\Proveedor\ProveedorResource;
use App\Models\Proveedor;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $proveedor = Proveedor::where('id', $this->proveedor_id)->first();

        return [
            'id' => $this->id,
            'descripcion' => $this->descripcion,
            'identificadorInterno' => $this->identificador_interno,
            'proveedor' => new ProveedorResource($proveedor),
            'fraccionamientoId' => $this->fraccionamiento_id,
        ];
    }
}
