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

    public static function setBalancesById($propiedad_id, $fraccionamiento_id)
    {
        $propiedad = Propiedad::find($propiedad_id);

        if (is_null($propiedad)) {
            return false;
        }

        if (is_null($fraccionamiento_id)) {
            return false;
        }

        // TODO: hacer la sumatoria de la propiedad con la tabla recibos

        //SUMATORIA DE TODOS LOS ESTATUS POR PAGAR Y VENCIDOS


    }

    public static function setGeneralBalances()
    {
        // TODO: hacer la sumatoria de la propiedad con la tabla recibos

        Recibo::join('propiedads', 'recibos.propiedad_id', '=', 'propiedads.id')
            ->where('recibos.estatus', '=', 'VENCIDO')
            ->groupBy('propiedads.id')
            ->selectRaw('propiedads.id, SUM(recibos.monto) AS sumatoria')
            ->get()
            ->each(function ($select) {
                $propiedad = Propiedad::find($select->id);
                $propiedad->balance -= $select->sumatoria;
                $propiedad->save();
            });
    }

    public static function setFraccionamientoBalances()
    {
        // TODO: hacer la sumatoria de la propiedad con la tabla recibos

        //SUMATORIA DE TODOS LOS ESTATUS POR PAGAR Y VENCIDOS


    }
}
