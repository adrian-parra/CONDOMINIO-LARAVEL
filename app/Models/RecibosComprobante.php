<?php

namespace App\Models;

use App\Mail\PagoRechazado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class RecibosComprobante extends Model
{
    use HasFactory;

    protected $fillable = [
        'fraccionamiento_id',
        'propiedad_id',
        'recibo_id',
        'monto',
        'comprobante_url',
        'estatus',
        'razon_rechazo',
        'tipo_pago'
    ];

    //Manda email en caso de ser rechazado el pago
    public function sendRechazoEmail($arrendatario, $razon)
    {
        $data = new \stdClass();

        $data->nombre = $arrendatario->nombre . $arrendatario->apellidos;
        $data->razonRechazo = $razon;
        $data->pagina = "https://colonos.mx/";

        // Mail::to($arrendatario->correo)
        Mail::to('hector.sauceda.01@gmail.com')
            ->send(new PagoRechazado($data));
    }
}
