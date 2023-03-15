<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];

    public function propietario()
    {
        return $this->belongsTo(Propietario::class);
    }

    //Funcion que actualiza el balance de la propiedad al realizar el pago de
    //algún recibo
    public function updateBalance($monto)
    {
        $this->balance += $monto;
        $this->save();
    }

    public static function setGeneralBalances()
    {
        // TODO: verificar el group by fraccionamiento.

        Recibo::join('propiedads', 'recibos.propiedad_id', '=', 'propiedads.id')
            ->where('recibos.estatus', '=', 'VENCIDO')
            ->groupBy('propiedads.id')
            ->selectRaw('propiedads.id, SUM(recibos.monto) AS sumatoria')
            ->get()
            ->each(function ($select) {
                $propiedad = Propiedad::find($select->id);
                if ($select->sumatoria) {
                    $propiedad->balance = $select->sumatoria;
                } else {
                    $propiedad->balance = 0;
                }
                $propiedad->save();
            });
    }

    public static function setFraccionamientoBalances()
    {
        // TODO: hacer la sumatoria de la propiedad con la tabla recibos

        //SUMATORIA DE TODOS LOS ESTATUS POR PAGAR Y VENCIDOS


    }
}
