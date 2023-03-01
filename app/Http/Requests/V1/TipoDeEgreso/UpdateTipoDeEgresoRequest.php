<?php

namespace App\Http\Requests\V1\TipoDeEgreso;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTipoDeEgresoRequest extends FormRequest
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


        if ($method == 'PUT') {
            return [
                'descripcion' => ['required', 'max:100'],
                'status' => ['required', 'boolean']
            ];
        }

        return [
            'descripcion' => ['sometimes', 'required', 'max:100'],
            'status' => ['sometimes', 'required', 'boolean']
        ];
    }
}
