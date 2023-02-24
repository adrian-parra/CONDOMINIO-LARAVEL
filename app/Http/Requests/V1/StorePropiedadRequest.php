<?php

namespace App\Http\Requests\V1;

use App\Models\fraccionamiento;
use App\Models\Propietario;
use Illuminate\Foundation\Http\FormRequest;
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {

        $fracc = fraccionamiento::pluck('id')->toArray();
        $propietario = Propietario::where('is_inquilino', false)->pluck('id')->toArray();

        if ($this->inquilinoId) {
            $inquilino = Propietario::where('is_inquilino', true)->pluck('id')->toArray();
        }

        return [
            'tipoPropiedadId' => ['required', 'integer', Rule::in([0, 1, 2, 3])],
            'archivoPredial' => ['required', 'file'],
            'claveCatastral' =>  ['required', 'max:40'],
            'descripcion' => ['required'],
            'superficie' => ['required', 'numeric'],
            'balance' => ['required', 'numeric'],
            'estatusId' => ['required', Rule::in([0, 1, 2, 3])],
            'razonDeRechazo' => ['required'],
            'propietarioId' => ['required', 'integer', Rule::in($propietario)],
            'inquilinoId' => ['integer', Rule::in($inquilino)],
            'fraccionamientoId' => ['required', 'integer', Rule::in($fracc)],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'tipo_propiedad_id' => $this->tipoPropiedadId,
            'clave_catastral' => $this->claveCatastral,
            'estatus_id' => $this->estatusId,
            'razon_de_rechazo' => $this->razonDeRechazo,
            'propietario_id' => $this->propietarioId,
            'inquilino_id' => $this->inquilinoId,
            'fraccionamiento_id' => $this->fraccionamientoId,
            'predial_url' => $this->archivoPredial,
        ]);
    }
}
