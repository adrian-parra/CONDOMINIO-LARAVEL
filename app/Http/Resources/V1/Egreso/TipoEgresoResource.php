<?php

namespace App\Http\Resources\V1\Egreso;

use App\Http\Resources\V1\Proveedor\ProveedorResource;
use App\Models\Proveedor;
use Illuminate\Http\Resources\Json\JsonResource;

class TipoEgresoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $proveedor = Proveedor::find($this->proveedor_id);
        return [
            'id' => $this->id,
            'descripcion' => $this->descripcion,
            'status' => $this->status,
            'proveedorDefault' => new ProveedorResource($proveedor),
        ];
    }
}
