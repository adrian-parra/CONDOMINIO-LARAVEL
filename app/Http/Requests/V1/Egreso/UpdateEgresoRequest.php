<?php

namespace App\Http\Requests\V1\Egreso;

use App\Models\fraccionamiento;
use App\Models\mensaje;
use App\Models\TipoDeEgreso;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UpdateEgresoRequest extends FormRequest
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
                'descripcion' => ['required', 'max:100'],
                'isVerified' => ['required', 'boolean'],
                'estatusEgresoId' => ['required', Rule::in([0, 1, 2])],
                'montoTotal' => ['required', 'numeric'],
                'archivoComprobante' => ['required', 'file'],
                'tipoPago' => ['required', Rule::in(['T/C', 'T/D', 'CHEQUE', 'EFECTIVO', 'TRANSFERENCIA'])],
                'fechaPago' => ['required', 'date'],
                'tipoEgreso' => ['required', 'integer', 'exists:tipo_de_egresos,id'],
                'fraccionamientoId' => ['required', 'integer', 'exists:fraccionamientos,id']
            ];
        } else if ($method == 'PATCH') {
            return [
                'descripcion' => ['sometimes', 'required', 'max:100'],
                'isVerified' => ['sometimes', 'required', 'boolean'],
                'estatusEgresoId' => ['sometimes', 'required', Rule::in([0, 1, 2])],
                'montoTotal' => ['sometimes', 'required', 'numeric'],
                'archivoComprobante' => ['sometimes', 'required', 'file'],
                'tipoPago' => ['sometimes', Rule::in(['T/C', 'T/D', 'CHEQUE', 'EFECTIVO', 'TRANSFERENCIA'])],
                'fechaPago' => ['sometimes', 'date'],
                'tipoEgreso' => ['sometimes', 'required', 'integer', 'exists:tipo_de_egresos,id'],
                'fraccionamientoId' => ['sometimes', 'required', 'integer', 'exists:fraccionamientos,id']
            ];
        }
    }

    protected function prepareForValidation()
    {
        $dataToMerge = [];

        $dataToMerge['is_verified'] = $this->isVerified ?? null;
        $dataToMerge['estatus_egreso_id'] = $this->estatusEgresoId ?? null;
        $dataToMerge['monto_total'] = $this->montoTotal ?? null;
        $dataToMerge['tipo_egreso_id'] = $this->tipoEgreso ?? null;
        $dataToMerge['tipo_pago'] = $this->tipoPago ?? null;
        $dataToMerge['fecha_pago'] = $this->fechaPago ?? null;
        $dataToMerge['fraccionamiento_id'] = $this->fraccionamientoId ?? null;

        $dataToMerge = array_filter($dataToMerge, function ($value) {
            return $value !== null;
        });

        $this->merge($dataToMerge);
    }
}
