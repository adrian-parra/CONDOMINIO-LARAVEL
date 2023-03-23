<?php

namespace App\Http\Requests\V1\Recibo\Comprobantes;

use App\Models\mensaje;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class UpdateRecibosComprobanteRequest extends FormRequest
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
        $method = $this->getMethod();

        if ($method == 'PUT') {
            return [
                'monto' => ['required', 'numeric'],
                'archivoComprobante' => ['required', 'file'],
                'estatus' => ['required', Rule::in(['PENDIENTE', 'FINALIZADO', 'RECHAZADO'])],
                'tipoPago' => ['required', Rule::in(['T/C', 'T/D', 'CHEQUE', 'EFECTIVO', 'TRANSFERENCIA'])],
                'razonRechazo' => ['required', 'max:200'],
                'reciboId' => ['required', 'exists:recibos,id'],
                'propiedadId' => ['required', 'exists:propiedads,id'],
                'fraccionamientoId' => ['required', 'exists:fraccionamientos,id'],
            ];
        } else if ($method == 'PATCH') {
            return [
                'monto' => ['sometimes', 'numeric'],
                'archivoComprobante' => ['sometimes', 'file'],
                'estatus' => ['sometimes', Rule::in(['PENDIENTE', 'FINALIZADO', 'RECHAZADO'])],
                'tipoPago' => ['sometimes', Rule::in(['T/C', 'T/D', 'CHEQUE', 'EFECTIVO', 'TRANSFERENCIA'])],
                'razonRechazo' => ['sometimes', 'max:200'],
            ];
        }
    }

    protected function prepareForValidation()
    {
        $dataToMerge = [];

        $dataToMerge['archivo_comprobante'] = $this->archivoComprobante ?? null;
        $dataToMerge['tipo_pago'] = $this->tipoPago ?? null;
        $dataToMerge['recibo_id'] = $this->reciboId ?? null;
        $dataToMerge['propiedad_id'] = $this->propiedadId ?? null;
        $dataToMerge['fraccionamiento_id'] = $this->fraccionamientoId ?? null;

        $dataToMerge = array_filter($dataToMerge, function ($value) {
            return $value !== null;
        });

        $this->merge($dataToMerge);
    }
}
