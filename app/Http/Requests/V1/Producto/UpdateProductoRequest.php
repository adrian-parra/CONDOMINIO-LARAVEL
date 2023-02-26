<?php

namespace App\Http\Requests\V1\Producto;

use App\Models\fraccionamiento;
use App\Models\Proveedor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductoRequest extends FormRequest
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
        $method = $this->method();

        $fracc = fraccionamiento::pluck('id')->toArray();
        $proveedores = Proveedor::pluck('id')->toArray();

        if ($method == 'PUT') {
            return [
                'descripcion' => ['required', 'max:100'],
                'identificadorInterno' => ['required', 'max:20'],
                'proveedorId' => ['required', 'integer', Rule::in($proveedores)],
                'fraccionamientoId' => ['required', 'integer', Rule::in($fracc)],
            ];
        }

        return [
            'descripcion' => ['sometimes', 'required', 'max:100'],
            'identificadorInterno' => ['sometimes', 'required', 'max:20'],
            'proveedorId' => ['sometimes', 'required', 'integer', Rule::in($proveedores)],
            'fraccionamientoId' => ['sometimes', 'required', 'integer', Rule::in($fracc)],
        ];
    }

    protected function prepareForValidation()
    {
        $dataToMerge = [];

        $dataToMerge['identificador_interno'] = $this->identificadorInterno ?? null;
        $dataToMerge['proveedor_id'] = $this->proveedorId ?? null;
        $dataToMerge['fraccionamiento_id'] = $this->fraccionamientoId ?? null;

        $dataToMerge = array_filter($dataToMerge, function ($value) {
            return $value !== null;
        });

        $this->merge($dataToMerge);
    }
}
