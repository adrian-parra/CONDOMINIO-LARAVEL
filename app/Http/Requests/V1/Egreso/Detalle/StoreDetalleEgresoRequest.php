<?php

namespace App\Http\Requests\V1\Egreso\Detalle;

use App\Models\Egreso;
use App\Models\fraccionamiento;
use App\Models\mensaje;
use App\Models\Producto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
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
        $fracc = fraccionamiento::pluck('id')->toArray();
        $egreso = Egreso::pluck('id')->toArray();
        $producto = Producto::pluck('id')->toArray();

        return [
            'egresoId' => ['required', 'integer', 'exists:egresos,id'],
            'productoId' => ['required', 'integer', 'exists:productos,id'],
            'fraccionamientoId' => ['required', 'integer', 'exists:fraccionamientos,id'],
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
