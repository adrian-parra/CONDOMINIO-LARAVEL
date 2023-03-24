<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Propiedad extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo_propiedad_id',
        'predial_url',
        'clave_catastral',
        'descripcion',
        'superficie',
        'balance',
        'estatus_id',
        'propietario_id',
        'inquilino_id',
        'fraccionamiento_id',
        'lote'
    ];

    protected $with = [
        'claveInterfon',
        'rfdi'
    ];

    public function propietario()
    {
        return $this->belongsTo(Propietario::class);
    }

    public function rfdi()
    {
        return $this->hasMany(Rfdi::class);
    }

    public function claveInterfon()
    {
        return $this->hasMany(ClaveInterfon::class);
    }

    //Funcion que actualiza el balance de la propiedad al realizar el pago de
    //algún recibo
    public function updateBalance($monto, $status)
    {
        if ($status == 'PAGADO') {
            $this->balance += $monto;
        } elseif ($status == 'VENCIDO') {
            $this->balance -= $monto;
        }
        $this->save();
    }

    //Funcion que actualiza los balances generales de todas las propiedades.
    public static function setGeneralBalances()
    {
        $recibos = Recibo::join('propiedads', 'recibos.propiedad_id', '=', 'propiedads.id')
            ->whereIn('recibos.estatus', ['PAGADO', 'VENCIDO'])
            ->groupBy('propiedads.id', 'recibos.estatus')
            ->selectRaw('propiedads.id, SUM(recibos.monto) AS sumatoria, recibos.estatus')
            ->get();

        $last_id = 0;
        $balance = 0;

        foreach ($recibos as $recibo) {

            $id = $recibo->id;

            //Comprobar si se esta sumando otro recibo para guardar el balance obtenido.
            if ($last_id != $id) {
                $propiedad = Propiedad::find($id);

                $propiedad->balance = $balance;
                $propiedad->save();

                $balance = 0;
            }

            if ($recibo->estatus == 'VENCIDO') {
                $balance -= $recibo->sumatoria;
            }

            $last_id = $id;
        }
    }

    public static function setFraccionamientoBalances($fraccionamientoId)
    {
        // TODO: hacer la sumatoria de la propiedad con la tabla recibos


        $recibos = Recibo::join('propiedads', 'recibos.propiedad_id', '=', 'propiedads.id')
            ->whereIn('recibos.estatus', ['PAGADO', 'VENCIDO'])
            ->where('propiedads.fraccionamiento_id', '=', $fraccionamientoId)
            ->groupBy('propiedads.id', 'recibos.estatus')
            ->selectRaw('propiedads.id, SUM(recibos.monto) AS sumatoria, recibos.estatus')
            ->get();

        $last_id = 0;
        $balance = 0;

        foreach ($recibos as $recibo) {

            $id = $recibo->id;

            if ($last_id != $id) {
                $propiedad = Propiedad::find($id);

                $propiedad->balance = $balance;
                $propiedad->save();

                $balance = 0;
            }

            if ($recibo->estatus == 'VENCIDO') {
                $balance -= $recibo->sumatoria;
            }

            $last_id = $id;
        }

        $propiedad = Propiedad::find($last_id);

        $propiedad->balance = $balance;
        $propiedad->save();
    }
}
