<?php

namespace App\Http\Requests\V1\Proveedor;

use App\Models\fraccionamiento;
use App\Models\mensaje;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class StoreProveedorRequest extends FormRequest
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

        return [
            'nombre' => ['required', 'max:100'],
            'rfc' => ['required', 'max:13', Rule::unique('proveedors', 'rfc')],
            'nombreContacto' => ['required', 'max:80'],
            'correoContacto' => ['required', 'email', 'max:40'],
            'notas' => ['required', 'max:200'],
            'metodoDePagoId' => ['required', 'integer', Rule::in([0, 1])],
            'fraccionamientoId' => ['required', 'integer', 'exists:fraccionamientos,id'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'nombre_contacto' => $this->nombreContacto,
            'correo_contacto' => $this->correoContacto,
            'metodo_de_pago_id' => $this->metodoDePagoId,
            'fraccionamiento_id' => $this->fraccionamientoId,
        ]);
    }
}
