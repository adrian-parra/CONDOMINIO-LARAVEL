<?php

namespace App\Http\Requests\V1\Producto;

use App\Models\fraccionamiento;
use App\Models\mensaje;
use App\Models\Proveedor;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
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
        return [
            'descripcion' => ['required', 'max:100'],
            'identificadorInterno' => ['required', 'max:20', Rule::unique('productos', 'identificador_interno')],
            'proveedorId' => ['required', 'integer', 'exists:proveedors,id'],
            'fraccionamientoId' => ['required', 'integer', 'exists:fraccionamientos,id'],
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
