<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function sendEmailToUser()
    {
        $user = usuario::find($this->user_id);

        // TODO: implement send email to the user
    }
}
