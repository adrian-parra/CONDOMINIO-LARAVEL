<?php

namespace App\Http\Requests\V1\Propiedad\Rfdi;

use App\Rules\UniqueTogether;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRfdiRequest extends FormRequest
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

        if ($method == 'PUT') {
            return [
                'tipo' => ['required', Rule::in(['PEATONAL', 'AUTOMOVIL'])],
                'estatus' => ['sometimes', Rule::in(['ACTIVO', 'INACTIVO', 'CANCELADA'])],
                'propiedadId' => ['required', 'numeric', 'exists:propiedads,id'],
            ];
        } 
        // else if ($method == 'PATCH') {
        //     return [
        //         'tipo' => ['sometimes', Rule::in(['PEATONAL', 'AUTOMOVIL'])],
        //         'estatus' => ['sometimes', Rule::in(['ACTIVO', 'INACTIVO', 'CANCELADA'])],
        //         'propiedadId' => ['sometimes', 'numeric', 'exists:propiedads,id'],
        //     ];
        // }
    }

    protected function prepareForValidation()
    {
        $dataToMerge = [];

        $dataToMerge['propiedad_id'] = $this->propiedadId ?? null;

        $dataToMerge = array_filter($dataToMerge, function ($value) {
            return $value !== null;
        });

        $this->merge($dataToMerge);
    }
}
