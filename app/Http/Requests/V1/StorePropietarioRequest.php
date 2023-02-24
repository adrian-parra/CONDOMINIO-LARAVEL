<?php

namespace App\Http\Requests\V1;

use App\Models\fraccionamiento;
use Illuminate\Foundation\Http\FormRequest;
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
            'apellidos' => ['required', 'max:100'],
            'correo' => ['required', 'max:40'],
            'celular' => ['required', 'max:20'],
            'celularAlt' => ['required', 'max:20'],
            'telefonoFijo' => ['required', 'max:20'],
            'archivoIdentificacion' => ['required', 'file'],
            'isInquilino' => ['required', 'boolean'],
            'fraccionamientoId' => ['required', 'integer', Rule::in($fracc)],
            'claveInterfon' => ['required', 'max:20'],
            'claveInterfonAlt' => ['required', 'max:20']
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
