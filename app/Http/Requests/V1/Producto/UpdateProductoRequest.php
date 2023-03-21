<?php

namespace App\Http\Requests\V1\Producto;

use App\Models\fraccionamiento;
use App\Models\mensaje;
use App\Models\Proveedor;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
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
        $producto = $this->route('producto');

        $id = $producto->id;

        if ($method == 'PUT') {
            return [
                'descripcion' => ['required', 'max:100'],
                'identificadorInterno' => ['required', 'max:20', Rule::unique('productos', 'identificador_interno')->ignore($id)],
                'proveedorId' => ['required', 'integer', 'exists:proveedors,id'],
                'fraccionamientoId' => ['required', 'integer', 'exists:fraccionamientos,id'],
            ];
        }

        return [
            'descripcion' => ['sometimes', 'required', 'max:100'],
            'identificadorInterno' => ['sometimes', 'required', 'max:20', Rule::unique('productos', 'identificador_interno')->ignore($id)],
            'proveedorId' => ['sometimes', 'required', 'integer', 'exists:proveedors,id'],
            'fraccionamientoId' => ['sometimes', 'required', 'integer', 'exists:fraccionamientos,id'],
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
