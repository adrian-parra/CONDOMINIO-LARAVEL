<?php

namespace App\Http\Requests\V1\Propiedad\Rfdi;

use App\Models\mensaje;
use App\Rules\UniqueTogether;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class StoreRfdiRequest extends FormRequest
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
        $uniqueTogether = new UniqueTogether('rfdis', ['fraccionamiento_id', 'rfdi']);

        return [
            'rfdi' => ['required', 'string', 'max:20', $uniqueTogether],
            'tipo' => ['required', Rule::in(['PEATONAL', 'AUTOMOVIL'])],
            'propiedadId' => ['required', 'numeric', 'exists:propiedads,id'],
            'fraccionamientoId' => ['required', 'numeric', 'exists:fraccionamientos,id'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'propiedad_id' => $this->propiedadId,
            'fraccionamiento_id' => $this->fraccionamientoId,
        ]);
    }
}
