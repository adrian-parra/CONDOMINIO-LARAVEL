<?php

namespace App\Utils;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class EliminarArchivo
{

    protected $path; // RUTA DE DONDE SE ENCUANTRA EL ARCHIVO PARA ELIMINAR

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function deletePrivateFile()
    {
        $file = storage_path('app/' . $this->path);

        if (!File::exists($file)) { //VERIFICA SI EXISTE RUTA DEL ARCHIVO
            abort(404);
        }

        unlink($file); //ELIMINACION DEL ARCHIVO



    }

}