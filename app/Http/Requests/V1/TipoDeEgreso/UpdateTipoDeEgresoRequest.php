<?php

namespace App\Http\Requests\V1\TipoDeEgreso;

use App\Models\mensaje;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

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
