<?php

namespace App\Http\Resources\V1\Recibo;

use App\Http\Resources\V1\Propietario\PropietarioResource;
use App\Models\Propietario;
use Illuminate\Http\Resources\Json\JsonResource;

class ReciboResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $propietario = Propietario::find($this->propietario_id);
        $inquilino = Propietario::find($this->inquilino_id);

        return [
            'id' => $this->id,
            'fechaPago' => $this->fecha_pago,
            'fechaVencimiento' => $this->fecha_vencimiento,
            'monto' => $this->monto,
            'montoPenalizacion' => $this->monto_penalizacion,
            'montoDescuento' => $this->monto_descuento,
            'estatus' => $this->estatus,
            'propietarioId' => new PropietarioResource($propietario),
            'inquilinoId' => new PropietarioResource($inquilino),
            'fraccionamientoId' => $this->fraccionamiento_id,
        ];
    }
}
