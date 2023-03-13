<?php

namespace App\Utils;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class AlmacenarArchivo
{
    protected $file; // Archivo a almacenar
    protected $dir; // Path donde se almacenará

    public function __construct($file, $dir)
    {
        $this->file = $file;
        $this->dir = $dir;
    }

    public function storeFile()
    {
        $file = $this->file;

        // Generar unique identifier
        $uuid = Str::uuid()->toString();
        $fileName = $uuid . '.' . $file->getClientOriginalExtension();

        // Almacenar el archivo en el disco
        $ruta = $file->storeAs($this->dir, $fileName);

        return $ruta;
    }

    public function deletePrivateFile()
    {
        $file = storage_path('app/' . $this->dir);

        if (!File::exists($file)) { //VERIFICA SI EXISTE RUTA DEL ARCHIVO
            abort(404);
        }

        unlink($file); //ELIMINACION DEL ARCHIVO

    }
}
