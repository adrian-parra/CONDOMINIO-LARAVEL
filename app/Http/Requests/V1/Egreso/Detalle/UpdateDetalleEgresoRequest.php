<?php

namespace App\Http\Requests\V1\Egreso\Detalle;

use App\Models\Egreso;
use App\Models\fraccionamiento;
use App\Models\mensaje;
use App\Models\Producto;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
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
        $method = $this->method();

        if ($method == 'DELETE') {
            return [];
        }

        $fracc = fraccionamiento::pluck('id')->toArray();
        $egreso = Egreso::pluck('id')->toArray();
        $producto = Producto::pluck('id')->toArray();

        if ($method == 'PUT') {
            return [
                'egresoId' => ['required', 'integer', 'exists:egresos,id'],
                'productoId' => ['required', 'integer', 'exists:productos,id'],
                'fraccionamientoId' => ['required', 'integer', 'exists:fraccionamientos,id'],
                'descripcion' => ['required', 'max:100'],
                'cantidad' => ['required', 'integer'],
                'precioUnitario' => ['required', 'numeric']
            ];
        }

        return [
            'egresoId' => ['required', 'integer', 'exists:egresos,id'],
            'productoId' => ['required', 'integer', 'exists:productos,id'],
            'fraccionamientoId' => ['sometimes', 'required', 'integer', 'exists:fraccionamientos,id'],
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
