<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\confirmar_correo;
use App\Models\fraccionamiento;
use App\Models\mensaje;
use App\Models\usuario;
use Firebase\JWT\JWK;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cookie;

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

    public function iniciarSesion(Request $request){
        $mensaje = new mensaje();

        $validation = Validator::make($request->all(), [
            'password' => 'required|min:8' ,
            'correo' => 'required|email'
        ]);

        if ($validation->fails()) {
            $mensaje->body = $validation->errors();
            $mensaje->title = "error";
            $mensaje->icon = "error";
            return response()->json([$mensaje], 422);
        }

        $user = usuario::where('correo', $request->correo)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Las credenciales son válidas, inicia sesión en el usuario y redirige a la página de inicio
           
            //GENERAR TOKEN DE AUTORIZACION PARA RUTAS PROTEGIDAS
            $aut = new JWTController();
            $token = $aut->generateToken($request->correo ,$user->id);
           
                    
            //ENCRIPTAR TOKEN
            //$iv_personalizado = random_bytes(16);
            /*
            $encrypted_token = encrypt($token ,[
                'key' => env('APP_KEY'),
                'iv' => $iv_personalizado,
                 'cipher' => 'AES-256-CBC'
            ]);*/

            // Para poder descifrar el texto cifrado, necesitas utilizar el mismo IV que se usó para cifrarlo.
           /*
            $decripted_token = Crypt::decrypt($encrypted_token, [
                'key' => env('APP_KEY'),
                'iv' => $iv_personalizado,
                'cipher' => 'AES-256-CBC'
            ]);*/

            $mensaje->icon = "success";
            $mensaje->title = "Credenciales correctas";
           
            $mensaje->estatus = 200;
            return response()->json([$mensaje],$mensaje->estatus)->withCookie(Cookie::make('token', $token, 60, null, null, false, true, false, 'strict'));
        } else {
            // Las credenciales son inválidas, muestra un mensaje de error
            $mensaje->icon = "error";
            $mensaje->title = "Credenciales incorrectas";
            $mensaje->estatus = 401;
            return response()->json([$mensaje],$mensaje->estatus);
        }

        
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
            $tokenDecode = $aut->decodeTokenActivarCuenta("9999999".$request->token);


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



        }catch (\Firebase\JWT\SignatureInvalidException $e) {
            $mensaje->title = "Firma de token no válida";
            $mensaje->icon = "error";
            // Handle the error
            return response()->json([$mensaje], 401);
        } catch (\Firebase\JWT\BeforeValidException $e) {
            $mensaje->title = "Token aún no válido";
            $mensaje->icon = "error";
            return response()->json([$mensaje], 401);
        } catch (\Firebase\JWT\ExpiredException $e) {
            $mensaje->title = "El token ha caducado";
            $mensaje->icon = "error";
            return response()->json([$mensaje], 401);
        } 
        catch (\Exception $e) {
            $mensaje->icon = "error";
            $mensaje->title = "Esta peticion no fue autorizada";
            $mensaje->body = $e->getMessage();
            return response()->json([$mensaje] ,401);
        }



        return response()->json([$mensaje], 200);

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