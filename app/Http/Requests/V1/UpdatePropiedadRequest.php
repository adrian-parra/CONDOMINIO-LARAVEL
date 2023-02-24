<?php

namespace App\Http\Requests\V1;

use App\Models\fraccionamiento;
use App\Models\Propiedad;
use App\Models\Propietario;
use Illuminate\Foundation\Http\FormRequest;
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $method = $this->getMethod();

        $fracc = fraccionamiento::pluck('id')->toArray();
        $propietario = Propietario::where('is_inquilino', false)->pluck('id')->toArray();

        $inquilino = Propietario::where('is_inquilino', true)->pluck('id')->toArray();

        if ($method == 'PUT') {
            return [
                'tipoPropiedadId' => ['required', 'numeric', Rule::in([0, 1, 2, 3])],
                'archivoPredial' => ['required', 'file'],
                'claveCatastral' =>  ['required', 'max:40'],
                'descripcion' => ['required'],
                'superficie' => ['required', 'numeric'],
                'balance' => ['required', 'numeric'],
                'estatusId' => ['required', Rule::in([0, 1, 2, 3])],
                'razonDeRechazo' => ['required'],
                'propietarioId' => ['required', 'numeric', Rule::in($propietario)],
                'inquilinoId' => ['required', 'numeric', Rule::in($inquilino)],
                'fraccionamientoId' => ['required', 'numeric', Rule::in($fracc)],
            ];
        } else if ($method == 'PATCH') {
            return [
                'tipoPropiedadId' => ['sometimes', 'required', 'numeric', Rule::in([0, 1, 2, 3])],
                'archivoPredial' => ['sometimes', 'required', 'file'],
                'claveCatastral' =>  ['sometimes', 'required', 'max:40'],
                'descripcion' => ['sometimes'],
                'superficie' => ['sometimes', 'required', 'numeric'],
                'balance' => ['sometimes', 'required', 'numeric'],
                'estatusId' => ['sometimes', 'required', Rule::in([0, 1, 2, 3])],
                'razonDeRechazo' => ['sometimes'],
                'propietarioId' => ['sometimes', 'required', 'numeric', Rule::in($propietario)],
                'inquilinoId' => ['sometimes', 'required', 'numeric', Rule::in($inquilino)],
                'fraccionamientoId' => ['sometimes', 'required', 'numeric', Rule::in($fracc)],
            ];
        }
    }

    protected function prepareForValidation()
    {
        $dataToMerge = [];

        $dataToMerge['tipo_propiedad_id'] = $this->tipoPropiedadId ?? null;
        $dataToMerge['clave_catastral'] = $this->claveCatastral ?? null;
        $dataToMerge['estatus_id'] = $this->estatusId ?? null;
        $dataToMerge['razon_de_rechazo'] = $this->razonDeRechazo ?? null;
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
