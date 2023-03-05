<?php

namespace App\Http\Resources\V1\Proveedor;

use Illuminate\Http\Resources\Json\JsonResource;

class ProveedorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $METODO_PAGO = [
            'TRANSFERENCIA',
            'CHEQUE'
        ];

        $objMetodoPago = [
            'id' => $this->metodo_de_pago_id,
            'descripcion' => $METODO_PAGO[$this->metodo_de_pago_id]
        ];

        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'rfc' => $this->rfc,
            'nombreContacto' => $this->nombre_contacto,
            'correoContacto' => $this->correo_contacto,
            'notas' => $this->notas,
            'metodoDePago' => $objMetodoPago,
            'fraccionamientoId' => $this->fraccionamiento_id,
        ];
    }
}
