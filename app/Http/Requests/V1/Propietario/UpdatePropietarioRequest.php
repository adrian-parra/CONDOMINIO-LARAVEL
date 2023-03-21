<?php

namespace App\Http\Requests\V1\Propietario;

use App\Models\fraccionamiento;
use App\Models\mensaje;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
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

        $propietario = $this->route('propietario');

        $id = $propietario->id;

        if ($method == 'PUT') {
            return [
                'nombre' => ['required', 'max:100'],
                'apellidos' => ['required', 'max:100'],
                'correo' => ['required', 'max:40', Rule::unique('propietarios', 'correo')->ignore($id)],
                'celular' => ['required', 'max:20', Rule::unique('propietarios', 'celular')->ignore($id)],
                'celularAlt' => ['sometimes', 'max:20', Rule::unique('propietarios', 'celular')->ignore($id)],
                'telefonoFijo' => ['required', 'max:20', Rule::unique('propietarios', 'telefono_fijo')->ignore($id)],
                'archivoIdentificacion' => ['sometimes', 'file'],
                'isInquilino' => ['required', 'boolean'],
                'fraccionamientoId' => ['required', 'integer', 'exists:fraccionamientos,id'],
                'claveInterfon' => ['required', 'max:20', Rule::unique('propietarios', 'clave_interfon')->ignore($id), Rule::unique('propietarios', 'clave_interfon_alt')->ignore($id)],
                'claveInterfonAlt' => ['sometimes', 'max:20', Rule::unique('propietarios', 'clave_interfon'), Rule::unique('propietarios', 'clave_interfon_alt')]
            ];
        } else if ($method == 'PATCH') {
            return [
                'nombre' => ['sometimes', 'max:100'],
                'apellidos' => ['sometimes', 'max:100'],
                'correo' => ['sometimes', 'max:40', Rule::unique('propietarios', 'correo')->ignore($id)],
                'celular' => ['sometimes', 'max:20', Rule::unique('propietarios', 'celular')->ignore($id)],
                'celularAlt' => ['sometimes', 'max:20', Rule::unique('propietarios', 'celular')->ignore($id)],
                'telefonoFijo' => ['sometimes', 'max:20', Rule::unique('propietarios', 'telefono_fijo')->ignore($id)],
                'archivoIdentificacion' => ['sometimes', 'file'],
                'isInquilino' => ['sometimes', 'boolean'],
                'fraccionamientoId' => ['sometimes', 'integer', 'exists:fraccionamientos,id'],
                'claveInterfon' => ['sometimes', 'max:20', Rule::unique('propietarios', 'clave_interfon')->ignore($id), Rule::unique('propietarios', 'clave_interfon_alt')->ignore($id)],
                'claveInterfonAlt' => ['sometimes', 'max:20', Rule::unique('propietarios', 'clave_interfon'), Rule::unique('propietarios', 'clave_interfon_alt')]
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
