<?php

namespace App\Http\Requests\V1\Egreso;

use App\Models\fraccionamiento;
use App\Models\mensaje;
use App\Models\TipoDeEgreso;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class StoreEgresoRequest extends FormRequest
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

        $fracc = fraccionamiento::pluck('id')->toArray();
        $tipoEgreso = TipoDeEgreso::pluck('id')->toArray();

        return [
            'descripcion' => ['required', 'max:100'],
            'isVerified' => ['required', 'boolean'],
            'estatusEgresoId' => ['required', Rule::in([0, 1, 2, 3, 4, 5, 6, 7])],
            'montoTotal' => ['required', 'numeric'],
            'archivoComprobante' => ['required', 'file'],
            'tipoEgreso' => ['required', 'integer', 'exists:tipo_de_egresos,id'],
            'fraccionamientoId' => ['required', 'integer', 'exists:fraccionamientos,id']
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_verified' => $this->isVerified,
            'estatus_egreso_id' => $this->estatusEgresoId,
            'monto_total' => $this->montoTotal,
            'tipo_egreso' => $this->tipoEgreso,
            'fraccionamiento_id' => $this->fraccionamientoId,
            'comprobante_url' => $this->archivoComprobante,
        ]);
    }
}
