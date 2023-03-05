<?php

namespace App\Http\Requests\V1\Recibo;

use App\Models\ConfigurarPagos;
use Illuminate\Foundation\Http\FormRequest;
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $configuraciones = ConfigurarPagos::pluck('id')->toArray();

        //TODO: PUEDE LLEGAR A ELEGIR QUE FECHA INICIAR LA FACTUARCIÓN

        return [
            'configuracionId' => ['required', 'integer', Rule::in($configuraciones)],
            'plazoPorGenerar' => ['required', 'integer'],
            'iniciarDesde' => ['sometimes', 'date']
        ];
    }
}