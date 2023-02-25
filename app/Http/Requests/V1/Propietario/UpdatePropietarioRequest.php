<?php

namespace App\Http\Requests\V1\Propietario;

use App\Models\fraccionamiento;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePropietarioRequest extends FormRequest
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
        $method = $this->getMethod();

        $fracc = fraccionamiento::pluck('id')->toArray();

        if ($method == 'PUT') {
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
        } else if ($method == 'PATCH') {
            return [
                'nombre' => ['sometimes', 'required', 'max:100'],
                'apellidos' => ['sometimes', 'required', 'max:100'],
                'correo' => ['sometimes', 'required', 'max:40'],
                'celular' => ['sometimes', 'required', 'max:20'],
                'celularAlt' => ['sometimes', 'required', 'max:20'],
                'telefonoFijo' => ['sometimes', 'required', 'max:20'],
                'archivoIdentificacion' => ['sometimes', 'required', 'file'],
                'isInquilino' => ['sometimes', 'required', 'boolean'],
                'fraccionamientoId' => ['sometimes', 'required', 'integer', Rule::in($fracc)],
                'claveInterfon' => ['sometimes', 'required', 'max:20'],
                'claveInterfonAlt' => ['sometimes', 'required', 'max:20']
            ];
        }
    }

    protected function prepareForValidation()
    {
        $dataToMerge = [];

        $dataToMerge['celular_alt'] = $this->celularAlt ?? null;
        $dataToMerge['telefono_fijo'] = $this->telefonoFijo ?? null;
        $dataToMerge['identificacion_url'] = $this->identificacionUrl ?? null;
        $dataToMerge['is_inquilino'] = $this->isInquilino ?? null;
        $dataToMerge['fraccionamiento_id'] = $this->fraccionamientoId ?? null;
        $dataToMerge['clave_interfon'] = $this->claveInterfon ?? null;
        $dataToMerge['clave_interfon_alt'] = $this->claveInterfonAlt ?? null;

        $dataToMerge = array_filter($dataToMerge, function ($value) {
            return $value !== null;
        });

        $this->merge($dataToMerge);
    }
}
