<?php

namespace App\Http\Controllers;

use App\Models\confirmar_correo;
use App\Models\fraccionamiento;
use App\Models\mensaje;
use App\Models\usuario;
use Firebase\JWT\JWK;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return usuario::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * ! (password ,token)
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $mensaje = new mensaje();

        $validation = Validator::make($request->all(), [
            'password' => 'required|min:8'
        ]);

        if ($validation->fails()) {
            $mensaje->body = $validation->errors();
            $mensaje->title = "error";
            $mensaje->icon = "error";
            return response()->json([$mensaje], 422);
        }


        $aut = new JWTController();

        try {
            $tokenDecode = $aut->decodeToken($request->header('Authorization'));


            $idConfirmCorreo = confirmar_correo::where('correo', $tokenDecode->correo)->pluck('id')->first();

            $confirmCorreo = confirmar_correo::find($idConfirmCorreo);
            $confirmCorreo->estado = "CONFIRMADO";
            $confirmCorreo->token = null;
            $confirmCorreo->save();

            $fraccionamiento = new fraccionamiento();
            $fraccionamiento->nombre = $tokenDecode->nombre_fraccionamiento;
            $fraccionamiento->codigo_postal = $tokenDecode->codigo_postal;
            $fraccionamiento->save();

            $usuario = new usuario;
            $usuario->nombre = $tokenDecode->nombre;
            $usuario->apellido = $tokenDecode->apellidos;
            $usuario->correo = $tokenDecode->correo;
            $usuario->password = Hash::make($request->password);
            $usuario->token = null;
            $usuario->id_confirmar_correo = $idConfirmCorreo;
            $usuario->id_fraccionamiento = $fraccionamiento->id;
            $usuario->save();

            


            $mensaje->icon = "success";
            $mensaje->title = "Su cuenta se ha creado con exito";



        } catch (\Exception $e) {
            $mensaje->icon = "error";
            $mensaje->title = "Esta peticion no fue autorizada";
            $mensaje->body = $e->getMessage();
            return response()->json($mensaje);
        }



        return response()->json($mensaje, 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function show(usuario $usuario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, usuario $usuario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function destroy(usuario $usuario)
    {
        //
    }
}