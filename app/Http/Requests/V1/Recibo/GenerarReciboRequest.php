<?php

namespace App\Http\Requests\V1\Recibo;

use App\Models\ConfigurarPagos;
use App\Models\mensaje;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class GenerarReciboRequest extends FormRequest
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
            'configuracionId' => ['required', 'integer', 'exists:configurar_pagos,id'],
            'plazoPorGenerar' => ['sometimes', 'integer'],
            'iniciarDesde' => ['sometimes', 'date'],
            'year' => ['sometimes', 'date_format:Y']
        ];
    }
}
