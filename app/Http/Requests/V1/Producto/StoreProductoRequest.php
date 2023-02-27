<?php

namespace App\Http\Requests\V1\Producto;

use App\Models\fraccionamiento;
use App\Models\Proveedor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductoRequest extends FormRequest
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
        $proveedores = Proveedor::pluck('id')->toArray();

        return [
            'descripcion' => ['required', 'max:100'],
            'identificadorInterno' => ['required', 'max:20'],
            'proveedorId' => ['required', 'integer', Rule::in($proveedores)],
            'fraccionamientoId' => ['required', 'integer', Rule::in($fracc)],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'identificador_interno' => $this->identificadorInterno,
            'proveedor_id' => $this->proveedorId,
            'fraccionamiento_id' => $this->fraccionamientoId,
        ]);
    }
}
