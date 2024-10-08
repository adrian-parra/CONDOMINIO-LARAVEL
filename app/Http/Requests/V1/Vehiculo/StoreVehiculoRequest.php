<?php

namespace App\Http\Requests\V1\Vehiculo;

use App\Models\mensaje;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class StoreVehiculoRequest extends FormRequest
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
            'id_fraccionamiento' => 'required',
            'id_propiedad' => 'required',
            'id_tipo_vehiculo' => 'required',
            'id_estado' => 'required',
            'propietario_id' => 'required',
            'marca' => 'required',
            'submarca'=>'required',
            'color' => 'required',
            'placas' => 'required',
            'tarjeta_circulacion' => 'sometimes',
        ];
    }
}
