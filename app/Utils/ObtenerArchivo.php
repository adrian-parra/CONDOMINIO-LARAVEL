<?php

namespace App\Utils;
use App\Models\mensaje;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ObtenerArchivo
{

    /**
     * @param mixed $foldername
     * @param mixed $filename
     */
    public function getFile(Request $request) {

        $path = storage_path('app/'.$request->foldertype.'/'.$request->foldername.'/' . $request->filename);
    
        if (!File::exists($path)) { //VERIFICA SI EXISTE RUTA DEL ARCHIVO
            abort(404);
         }

        $file = File::get($path); //SE ALMACENA EL ARCHIVO
        $type = File::mimeType($path); //TIPO DE ARCHIVO
        //REGRESA EL ARCHIVO PARA PODERLO MANEJAR CON EL FRONT END

        $mensaje =new mensaje();
        $mensaje->title = '';
        $mensaje->icon = 'success';
        $mensaje->body = $file;
        $response = response($file, 200); 
        //ENCABESADO EN LA PETICION PARA INFORMAR QUE TIPO DE ARCHIVO ESTA RETORNANDO
        $response->header("Content-Type", $type); 

        return $response;
    }
    
}