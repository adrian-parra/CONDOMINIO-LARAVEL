<?php

namespace App\Http\Requests\V1\Egreso\Detalle;

use App\Models\Egreso;
use App\Models\fraccionamiento;
use App\Models\Producto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDetalleEgresoRequest extends FormRequest
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
        $egreso = Egreso::pluck('id')->toArray();
        $producto = Producto::pluck('id')->toArray();

        return [
            'egresoId' => ['required', 'integer', Rule::in($egreso)],
            'productoId' => ['required', 'integer', Rule::in($producto)],
            'fraccionamientoId' => ['required', 'integer', Rule::in($fracc)],
            'descripcion' => ['required', 'max:100'],
            'cantidad' => ['required', 'integer'],
            'precioUnitario' => ['required', 'numeric']
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'egreso_id' => $this->egresoId,
            'producto_id' => $this->productoId,
            'fraccionamiento_id' => $this->fraccionamientoId,
            'precio_unitario' => $this->precioUnitario,
        ]);
    }
}
