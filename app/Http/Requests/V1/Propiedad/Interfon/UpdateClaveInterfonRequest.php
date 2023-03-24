<?php

namespace App\Http\Requests\V1\Propiedad\Interfon;

use App\Models\mensaje;
use App\Rules\UniqueTogether;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class UpdateClaveInterfonRequest extends FormRequest
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
        $method = $this->getMethod();

        $id = $this->route('id');

        if ($method == 'PUT') {
            return [
                'numero_interfon' => [
                    'required',
                    'string',
                    'max:20',
                    new UniqueTogether(
                        'clave_interfons',
                        [
                            'numero_interfon',
                            'fraccionamiento_id'
                        ],
                        $id
                    )
                ],
                'propiedad_id' => ['required', 'exists:propiedads,id'],
                'fraccionamiento_id' => [
                    'required',
                    'exists:fraccionamiento,id',
                    new UniqueTogether(
                        'clave_interfons',
                        [
                            'numero_interfon',
                            'fraccionamiento_id'
                        ],
                        $id
                    )
                ],
            ];
        } else if ($method == 'PATCH') {
            $mensaje = new mensaje();

            $mensaje->title = "Metodo PATCH no es aceptado";
            $mensaje->icon = "error";

            throw new HttpResponseException(
                new JsonResponse(
                    $mensaje,
                    400
                )
            );
        }
    }

    protected function prepareForValidation()
    {
        $dataToMerge = [];

        $dataToMerge['numero_interfon'] = $this->numeroInterfon ?? null;
        $dataToMerge['fraccionamiento_id'] = $this->fraccionamientoId ?? null;
        $dataToMerge['propiedad_id'] = $this->propiedadId ?? null;

        $dataToMerge = array_filter($dataToMerge, function ($value) {
            return $value !== null;
        });

        $this->merge($dataToMerge);
    }
}
