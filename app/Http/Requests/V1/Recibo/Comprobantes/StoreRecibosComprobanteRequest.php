<?php

namespace App\Http\Requests\V1\Recibo\Comprobantes;

use App\Models\mensaje;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class StoreRecibosComprobanteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        $mensaje = new mensaje();
        $mensaje->body = $validator->errors();
        $mensaje->title = "error";
        $mensaje->icon = "error";

        throw new HttpResponseException(
            new JsonResponse(
                $mensaje,
                422
            )
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'monto' => ['required', 'numeric'],
            'archivoComprobante' => ['required', 'file'],
            'tipoPago' => ['required', Rule::in(['T/C', 'T/D', 'CHEQUE', 'EFECTIVO', 'TRANSFERENCIA'])],
            'reciboId' => ['required', 'exists:recibos,id'],
            'propiedadId' => ['required', 'exists:propiedads,id'],
            'fraccionamientoId' => ['required', 'exists:fraccionamientos,id'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'archivo_comprobante' => $this->archivoComprobante,
            'tipo_pago' => $this->tipoPago,
            'propiedad_id' => $this->propiedadId,
            'recibo_id' => $this->reciboId,
            'fraccionamiento_id' => $this->fraccionamientoId,
        ]);
    }
}
