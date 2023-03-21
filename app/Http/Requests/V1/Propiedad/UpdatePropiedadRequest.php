<?php

namespace App\Http\Requests\V1\Propiedad;

use App\Models\fraccionamiento;
use App\Models\mensaje;
use App\Models\Propiedad;
use App\Models\Propietario;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UpdatePropiedadRequest extends FormRequest
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

        $id = $this->route('propiedade');

        if ($method == 'PUT') {
            return [
                'tipoPropiedadId' => ['required', 'integer', Rule::in([0, 1, 2, 3])],
                'archivoPredial' => ['sometimes', 'file'],
                'claveCatastral' =>  ['required', 'max:40', Rule::unique('propiedads', 'clave_catastral')->ignore($id)],
                'descripcion' => ['required'],
                'superficie' => ['required', 'numeric'],
                'balance' => ['sometimes', 'numeric'],
                'estatus' => ['sometimes', 'boolean'],
                'lote' => ['required', 'max:5'],
                'propietarioId' => ['required', 'integer', 'exists:propietarios,id'],
                'inquilinoId' => ['sometimes', 'integer', 'exists:propietarios,id', Rule::unique('propiedads', 'clave_catastral')->ignore($id)],
                'fraccionamientoId' => ['required', 'integer', 'exists:fraccionamientos,id'],
            ];
        } else if ($method == 'PATCH') {
            return [
                'tipoPropiedadId' => ['sometimes', 'integer', Rule::in([0, 1, 2, 3])],
                'archivoPredial' => ['sometimes', 'file'],
                'claveCatastral' =>  ['sometimes', 'max:40', Rule::unique('propiedads', 'clave_catastral')->ignore($id)],
                'descripcion' => ['sometimes'],
                'superficie' => ['sometimes', 'numeric'],
                'balance' => ['sometimes', 'numeric'],
                'estatus' => ['sometimes', 'boolean'],
                'lote' => ['sometimes', 'max:5'],
                'propietarioId' => ['sometimes', 'integer', 'exists:propietarios,id'],
                'inquilinoId' => ['sometimes', 'integer', 'exists:propietarios,id', Rule::unique('propiedads', 'clave_catastral')->ignore($id)],
                'fraccionamientoId' => ['sometimes', 'integer', 'exists:fraccionamientos,id'],
            ];
        }
    }

    protected function prepareForValidation()
    {
        $dataToMerge = [];

        $dataToMerge['tipo_propiedad_id'] = $this->tipoPropiedadId ?? null;
        $dataToMerge['clave_catastral'] = $this->claveCatastral ?? null;
        $dataToMerge['propietario_id'] = $this->propietarioId ?? null;
        $dataToMerge['inquilino_id'] = $this->inquilinoId ?? null;
        $dataToMerge['fraccionamiento_id'] = $this->fraccionamientoId ?? null;
        $dataToMerge['predial_url'] = $this->archivoPredial ?? null;

        $dataToMerge = array_filter($dataToMerge, function ($value) {
            return $value !== null;
        });

        $this->merge($dataToMerge);
    }
}
