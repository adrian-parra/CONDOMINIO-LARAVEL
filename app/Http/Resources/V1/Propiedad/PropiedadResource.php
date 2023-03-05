<?php

namespace App\Http\Resources\V1\Propiedad;

use App\Http\Resources\V1\Propietario\PropietarioResource;
use App\Models\Propietario;
use Illuminate\Http\Resources\Json\JsonResource;

class PropiedadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $tipoPropiedad = [
            'CASA', //0
            'DEPARTAMENTO', //1
            'LOTE', //2
            'OTRO' //3
        ];

        $tipoEstatus = [
            'NUEVA', //0
            'BALANCE PENDIENTE', //1
            'INCOMPLETA', //2
            'ACTIVA' //3
        ];

        $propietario = Propietario::where('id', $this->propietario_id)->first();
        $inquilino = Propietario::where('id', $this->inquilino_id)->first();

        $objTipoPropiedad = [
            'id' => $this->tipo_propiedad_id,
            'descripcion' => $tipoPropiedad[$this->tipo_propiedad_id]
        ];

        $objTipoEstatus = [
            'id' => $this->estatus_id,
            'descripcion' => $tipoEstatus[$this->estatus_id]
        ];

        return [
            'id' => $this->id,
            'tipoPropiedad' => $objTipoPropiedad,
            'claveCatastral' =>  $this->clave_catastral,
            'predialUrl' => $this->predial_url,
            'descripcion' => $this->descripcion,
            'superficie' => $this->superficie,
            'balance' => $this->balance,
            'estatus' => $objTipoEstatus,
            'propietario' => new PropietarioResource($propietario),
            'inquilino' => new PropietarioResource($inquilino),
            'fraccionamientoId' => $this->fraccionamiento_id,
        ];
    }
}
