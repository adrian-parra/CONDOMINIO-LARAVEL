<?php

namespace App\Models;

use App\Mail\EgresoMail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class fraccionamiento extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'codigo_postal',
        'egresos_authorized',
        'user_id',
    ];

    public function getEgresosNeedToBeAuthorize()
    {
        return $this->egresos_authorized;
    }

    public function sendEmailToUser($method)
    {
        $user = usuario::find($this->user_id);
        $data = new \stdClass();
        $type = 0;
        // $data->nombre = $user->nombre;
        // $data->pagina = "https://colonos.mx/"

        $data->nombre = "Hector";
        $data->pagina = "https://colonos.mx/";

        // Mail::to($user->email)
        // ->send(new EgresoMail($data));
        if ($method != "POST") {
            $type = 1;
        }

        Mail::to('hector.sauceda.01@gmail.com')
            ->send(new EgresoMail($data, $type));
    }
}
