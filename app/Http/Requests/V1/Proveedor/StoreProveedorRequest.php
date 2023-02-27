<?php

namespace App\Http\Requests\V1\Proveedor;

use App\Models\fraccionamiento;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProveedorRequest extends FormRequest
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

        return [
            'nombre' => ['required', 'max:100'],
            'rfc' => ['required', 'max:13'],
            'nombreContacto' => ['required', 'max:80'],
            'correoContacto' => ['required', 'email', 'max:40'],
            'notas' => ['required', 'max:200'],
            'metodoDePagoId' => ['required', 'integer', Rule::in([0, 1])],
            'fraccionamientoId' => ['required', 'integer', Rule::in($fracc)],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'nombre_contacto' => $this->nombreContacto,
            'correo_contacto' => $this->correoContacto,
            'metodo_de_pago_id' => $this->metodoDePagoId,
            'fraccionamiento_id' => $this->fraccionamientoId,
        ]);
    }
}
