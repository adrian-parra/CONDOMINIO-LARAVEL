<?php

namespace App\Http\Resources\V1\Propiedad;

use App\Http\Resources\V1\Propiedad\Interfon\ClaveInterfonResource;
use App\Http\Resources\V1\Propiedad\Rfdi\RfdiResource;
use App\Http\Resources\V1\Propiedad\Rfdi\RfdiResourcePropiedad;
use App\Http\Resources\V1\Propietario\PropietarioResource;
use App\Models\Propietario;
use Illuminate\Http\Resources\Json\JsonResource;

class PropiedadReciboResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $propietario = Propietario::where('id', $this->propietario_id)->first();
        $inquilino = Propietario::where('id', $this->inquilino_id)->first();

        return [
            'id' => $this->id,
            'balance' => $this->balance,
            'lote' => $this->lote,
            'propietario' => new PropietarioResource($propietario),
            'inquilino' => new PropietarioResource($inquilino),
        ];
    }
}
