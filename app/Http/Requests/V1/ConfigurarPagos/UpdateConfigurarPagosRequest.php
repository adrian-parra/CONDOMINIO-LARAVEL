<?php

namespace App\Http\Requests\V1\ConfigurarPagos;

use App\Models\mensaje;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class UpdateConfigurarPagosRequest extends FormRequest
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
               $mensaje
            , 422)
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
            //
            //'configurar_pago' => 'exists:ConfigurarPagos,configurar_pago',
            //'id_configuracion_pago' => 'required',
            'descripcion' => 'required',
            'tipo_pago' => ['required' ,Rule::in(['ORDINARIO','EXTRAORDINARIO'])],
            'monto' => 'required',
            'fecha_inicial' => 'required|date',
            'periodo' => ['required',Rule::in(['UNICO','SEMANAL' ,'MENSUAL', 'ANUAL'])],
            'dias_max_pago' => 'required',
            'dias_max_descuento' => 'required',
            'porcentaje_penalizacion' => 'required',
            'porcentaje_descuento' => 'required',
        ];
    }
}
