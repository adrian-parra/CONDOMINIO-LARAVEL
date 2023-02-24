<?php

namespace App\Http\Resources\V1;

use App\Models\Propietario;
use Illuminate\Http\Resources\Json\JsonResource;

class PropiedadPropietarioResource extends JsonResource
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

        $inquilino = Propietario::where('id', $this->inquilino_id)->first();

        return [
            'id' => $this->id,
            'tipoPropiedad' => $tipoPropiedad[$this->tipo_propiedad_id],
            'claveCatastral' =>  $this->clave_catastral,
            'predialUrl' => $this->predial_url,
            'descripcion' => $this->descripcion,
            'superficie' => $this->superficie,
            'balance' => $this->balance,
            'estatusId' => $tipoEstatus[$this->estatus_id],
            'razonDeRechazo' => $this->razon_de_rechazo,
            'inquilino' => new PropietarioResource($inquilino),
            'fraccionamientoId' => $this->fraccionamiento_id,
        ];
    }
}
