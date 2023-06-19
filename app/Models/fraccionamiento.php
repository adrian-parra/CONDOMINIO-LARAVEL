<?php

namespace App\Models;

use App\Mail\EgresoMail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
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
        //TODO: implementar envio email variable.
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


    function getEgresosIngresosMesFraccionamiento()
    {

        $mesActual = Carbon::now()->format('m');
        $anioActual = Carbon::now()->format('Y');

        $egresos = Egreso::whereMonth('created_at', $mesActual)
            ->whereYear('created_at', $anioActual)
            ->where("fraccionamiento_id", $this->id)
            ->get();

        $ingresos = Recibo::whereMonth('created_at', $mesActual)
            ->whereYear('created_at', $anioActual)
            ->where('estatus', "PAGADO")
            ->where("fraccionamiento_id", $this->id)
            ->get();

        $totalEgresos = $egresos->sum('monto_total');
        $totalIngresos = $ingresos->sum('monto');

        $recibosMes = Recibo::whereMonth('created_at', $mesActual)
            ->whereYear('created_at', $anioActual)
            ->where("fraccionamiento_id", $this->id)
            ->get();

        $totalRecibosPagados = $recibosMes->where('estatus', "PAGADO")
            ->count();
        $totalRecibosVencidos = $recibosMes->where('estatus', "VENCIDO")
            ->count();
        $totalRecibosPendientes = $recibosMes->where('estatus', "POR_PAGAR")
            ->count();

        $data = [
            'ingresos' => $ingresos,
            'egresos' => $egresos,
            'totalEgresos' => $totalEgresos,
            'totalIngresos' => $totalIngresos,
            'totaPorPagar' => $totalRecibosPendientes,
            'totalVencidos' => $totalRecibosVencidos,
            'totalPagados' => $totalRecibosPagados
        ];

        return $data;
    }
}
