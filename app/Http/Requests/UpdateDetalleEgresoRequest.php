<?php

namespace App\Http\Requests;

use App\Models\Egreso;
use App\Models\fraccionamiento;
use App\Models\Producto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDetalleEgresoRequest extends FormRequest
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

        if ($method == 'DELETE') {
            return [];
        }

        $fracc = fraccionamiento::pluck('id')->toArray();
        $egreso = Egreso::pluck('id')->toArray();
        $producto = Producto::pluck('id')->toArray();

        if ($method == 'PUT') {
            return [
                'egresoId' => ['required', 'integer', Rule::in($egreso)],
                'productoId' => ['required', 'integer', Rule::in($producto)],
                'fraccionamientoId' => ['required', 'integer', Rule::in($fracc)],
                'descripcion' => ['required', 'max:100'],
                'cantidad' => ['required', 'integer'],
                'precioUnitario' => ['required', 'numeric']
            ];
        }

        return [
            'egresoId' => ['required', 'integer', Rule::in($egreso)],
            'productoId' => ['required', 'integer', Rule::in($producto)],
            'fraccionamientoId' => ['sometimes', 'required', 'integer', Rule::in($fracc)],
            'descripcion' => ['sometimes', 'required', 'max:100'],
            'cantidad' => ['sometimes', 'required', 'integer'],
            'precioUnitario' => ['sometimes', 'required', 'numeric']
        ];
    }

    protected function prepareForValidation()
    {
        $dataToMerge = [];

        $dataToMerge['egreso_id'] = $this->egresoId ?? null;
        $dataToMerge['producto_id'] = $this->productoId ?? null;
        $dataToMerge['fraccionamiento_id'] = $this->fraccionamientoId ?? null;
        $dataToMerge['precio_unitario'] = $this->precioUnitario ?? null;

        $dataToMerge = array_filter($dataToMerge, function ($value) {
            return $value !== null;
        });

        $this->merge($dataToMerge);
    }
}
