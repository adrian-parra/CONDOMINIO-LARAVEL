<?php

namespace App\Http\Requests\V1\Propietario;

use App\Models\fraccionamiento;
use App\Models\mensaje;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class StorePropietarioRequest extends FormRequest
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
            'nombre' => ['required', 'max:100'],
            'apellidos' => ['required', 'max:100'],
            'correo' => ['required', 'max:40', Rule::unique('propietarios', 'correo')],
            'celular' => ['required', 'max:20', Rule::unique('propietarios', 'celular')],
            'celularAlt' => ['sometimes', 'max:20', Rule::unique('propietarios', 'celular')],
            'telefonoFijo' => ['required', 'max:20', Rule::unique('propietarios', 'telefono_fijo')],
            'archivoIdentificacion' => ['sometimes', 'file'],
            'isInquilino' => ['required', 'boolean'],
            'fraccionamientoId' => ['required', 'integer', 'exists:fraccionamientos,id'],
            'claveInterfon' => ['required', 'max:20', Rule::unique('propietarios', 'clave_interfon'), Rule::unique('propietarios', 'clave_interfon_alt')],
            'claveInterfonAlt' => ['sometimes', 'max:20', Rule::unique('propietarios', 'clave_interfon'), Rule::unique('propietarios', 'clave_interfon_alt')]
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'celular_alt' => $this->celularAlt,
            'telefono_fijo' => $this->telefonoFijo,
            'identificacion_url' => $this->identificacionUrl,
            'is_inquilino' => $this->isInquilino,
            'fraccionamiento_id' => $this->fraccionamientoId,
            'clave_interfon' => $this->claveInterfon,
            'clave_interfon_alt' => $this->claveInterfonAlt,
        ]);
    }
}
