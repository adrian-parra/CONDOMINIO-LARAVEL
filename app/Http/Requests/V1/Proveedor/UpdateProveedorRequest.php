<?php

namespace App\Http\Requests\V1\Proveedor;

use App\Models\fraccionamiento;
use App\Models\mensaje;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class UpdateProveedorRequest extends FormRequest
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
        $method = $this->method();

        $fracc = fraccionamiento::pluck('id')->toArray();

        if ($method == 'PUT') {
            return [
                'nombre' => ['required', 'max:100'],
                'rfc' => ['required', 'max:13'],
                'nombreContacto' => ['required', 'max:80'],
                'correoContacto' => ['required', 'max:40'],
                'notas' => ['required', 'max:200'],
                'metodoDePagoId' => ['required', 'integer', Rule::in([0, 1])],
                'fraccionamientoId' => ['required', 'integer', Rule::in($fracc)],
            ];
        }

        return [
            'nombre' => ['sometimes', 'required', 'max:100'],
            'rfc' => ['sometimes', 'required', 'max:13'],
            'nombreContacto' => ['sometimes', 'required', 'max:80'],
            'correoContacto' => ['sometimes', 'required', 'max:40'],
            'notas' => ['sometimes', 'required', 'max:200'],
            'metodoDePagoId' => ['sometimes', 'required', 'integer', Rule::in([0, 1])],
            'fraccionamientoId' => ['sometimes', 'required', 'integer', Rule::in($fracc)],
        ];
    }

    protected function prepareForValidation()
    {
        $dataToMerge = [];

        $dataToMerge['nombre_contacto'] = $this->nombreContacto ?? null;
        $dataToMerge['correo_contacto'] = $this->correoContacto ?? null;
        $dataToMerge['metodo_de_pago_id'] = $this->metodoDePagoId ?? null;
        $dataToMerge['fraccionamiento_id'] = $this->fraccionamientoId ?? null;

        $dataToMerge = array_filter($dataToMerge, function ($value) {
            return $value !== null;
        });

        $this->merge($dataToMerge);
    }
}
