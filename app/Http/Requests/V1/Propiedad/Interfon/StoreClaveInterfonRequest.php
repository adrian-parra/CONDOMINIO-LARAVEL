<?php

namespace App\Http\Requests\V1\Propiedad\Interfon;

use App\Models\mensaje;
use App\Rules\UniqueTogether;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class StoreClaveInterfonRequest extends FormRequest
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
            'numeroInterfon' => [
                'required',
                'string',
                'max:20',
                new UniqueTogether('clave_interfons', ['numero_interfon', 'fraccionamiento_id'])
            ],
            'propiedadId' => ['required', 'exists:propiedads,id'],
            'fraccionamientoId' => [
                'required',
                'exists:fraccionamientos,id',
                new UniqueTogether('clave_interfons', ['numero_interfon', 'fraccionamiento_id'])
            ],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'numero_interfon' => $this->numeroInterfon,
            'fraccionamiento_id' => $this->fraccionamientoId,
            'propiedad_id' => $this->propiedadId,
        ]);
    }
}
