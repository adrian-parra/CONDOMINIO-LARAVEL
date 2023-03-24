<?php

namespace App\Http\Requests\V1\Propiedad;

use App\Models\fraccionamiento;
use App\Models\mensaje;
use App\Models\Propietario;
use App\Rules\UniqueTogether;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class StorePropiedadRequest extends FormRequest
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
            'tipoPropiedadId' => ['required', 'integer', Rule::in([0, 1, 2, 3])],
            'archivoPredial' => ['sometimes', 'file'],
            'claveCatastral' =>  ['required', 'max:40', Rule::unique('propiedads', 'clave_catastral')],
            'descripcion' => ['required'],
            'superficie' => ['required', 'numeric'],
            'balance' => ['sometimes', 'numeric'],
            'estatus' => ['sometimes', 'boolean'],
            'lote' => ['required', 'max:5', new UniqueTogether('propiedads', ['fraccionamiento_id', 'lote'])],
            'propietarioId' => ['required', 'integer', 'exists:propietarios,id'],
            'inquilinoId' => ['sometimes', 'integer', 'exists:propietarios,id', Rule::unique('propiedads', 'inquilino_id')],
            'fraccionamientoId' => ['required', 'integer', 'exists:fraccionamientos,id', new UniqueTogether('propiedads', ['fraccionamiento_id', 'lote'])],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'tipo_propiedad_id' => $this->tipoPropiedadId,
            'clave_catastral' => $this->claveCatastral,
            'propietario_id' => $this->propietarioId,
            'inquilino_id' => $this->inquilinoId,
            'fraccionamiento_id' => $this->fraccionamientoId,
            'predial_url' => $this->archivoPredial,
        ]);
    }
}
