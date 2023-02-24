<?php

namespace App\Utils;

use Illuminate\Support\Str;

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
}
