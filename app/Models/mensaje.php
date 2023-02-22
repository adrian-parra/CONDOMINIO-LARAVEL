<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mensaje extends Model
{
    use HasFactory;

    protected $fillable = [
        'icon',
        'title',
        'body' ,
        'estatus'
    ];
}
