<?php

namespace App\Http\Resources\V1\Recibo\Estado;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class ReciboEstadoCuentaResource extends JsonResource
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
            'fechaPago' => $this->fecha_pago,
            'fechaVencimiento' => $this->fecha_vencimiento,
            'monto' => $this->monto - $this->monto_pagado,
            'montoPenalizacion' => $this->monto_penalizacion,
            'montoDescuento' => $this->monto_descuento,
            'estatus' => $this->estatus,
        ];
    }
}
