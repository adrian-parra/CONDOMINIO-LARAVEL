<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\Controller;
use App\Models\confirmar_correo;
use App\Models\fraccionamiento;
use App\Models\mensaje;
use App\Models\Rol;
use App\Models\RolPorUsuario;
use App\Models\usuario;
use Illuminate\Http\Request;
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
    public function index(Request $request)
    {
        //
        $mensaje = new mensaje();

        $mensaje->title = "";
        $mensaje->icon = "success";
        $users = usuario::where('id_fraccionamiento', $request->id_fraccionamiento)->with('roles')->get();
        $mensaje->body = $users;

        return response()->json($mensaje, 200);
    }

    public function iniciarSesion(Request $request)
    {
        $mensaje = new mensaje();

        $validation = Validator::make($request->all(), [
            'password' => 'required|min:8',
            'correo' => 'required|email'
        ]);

        if ($validation->fails()) {
            $mensaje->body = $validation->errors();
            $mensaje->title = "error";
            $mensaje->icon = "error";
            return response()->json($mensaje, 422);
        }

        $user = usuario::where('correo', $request->correo)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Las credenciales son válidas, inicia sesión en el usuario y redirige a la página de inicio

            //GENERAR TOKEN DE AUTORIZACION PARA RUTAS PROTEGIDAS
            $aut = new JWTController();
            $token = $aut->generateToken($request->correo, $user->id);

            //OBTENGO LA DESCRIPCION DE LO ROLES ASIGNADOS A DICHO USUARIO
            //QUE INTENTA ACCEDER A LA RUTA
            $userId = $user->id;
            $roles = "";
            $userRoles = Rol::whereHas('users', function ($query) use ($userId) {
                $query->where('id', $userId);
            })
                ->select('descripcion')
                ->get(); //RETORNO UN ARRAY DE OBJETOS DESCRIPCION DE ROLES

            //VERIFICAR SI USUARIO TIENE MAS DE UN ROL
            if (count($userRoles) > 1) {
                foreach ($userRoles as $DescripcionRol) { //RECORRE LOS ROLES ENCONTRADOS
                    $roles = $roles . $DescripcionRol->descripcion . ','; //ALMACENO SOLO LA DESCRIPCION DEL ROL
                }

            } else {
                //USUARIO SOLO TIENE UN ROL
                $roles = $userRoles[0]->descripcion;
            }

            //! OBTENER EL NOMBRE DEL FRACCIONAMIENTO Y CODIGO POSTAL
            $fraccionamiento = fraccionamiento::find($user->id_fraccionamiento);



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
            $mensaje->title = "";
            $mensaje->body = [
                "token" => $token,
                "rol" => $roles,
                'id_fraccionamiento' => $user->id_fraccionamiento,
                'nombre_fraccionamiento' => $fraccionamiento->nombre,
                'codigo_postal_fraccionamiento' => $fraccionamiento->codigo_postal
            ];

            $mensaje->estatus = 200;
            //return response()->json($mensaje,$mensaje->estatus)->withCookie(Cookie::make('token', $token, 60, null, null, false, true, false, 'strict'));
            //return response()->json($mensaje,$mensaje->estatus)->withCookie(Cookie::make('token', $token, 60 ,'/','localhost', false, true));
            return response()->json($mensaje, $mensaje->estatus);
        } else {
            // Las credenciales son inválidas, muestra un mensaje de error
            $mensaje->icon = "error";
            $mensaje->title = "Credenciales incorrectas";
            $mensaje->estatus = 401;
            return response()->json($mensaje, $mensaje->estatus);
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
            return response()->json($mensaje, 422);
        }


        $aut = new JWTController();

        try {

            //! DECODIFICO EL TOKEN PARA OBTENER LOS ATRIBUTOS QUE SE REQUIEREN
            $tokenDecode = $aut->decodeTokenActivarCuenta("9999999" . $request->token);

            //! OBTENGO EL ID DEL REGISTRO CONFIRM CORREO 
            $idConfirmCorreo = confirmar_correo::where('correo', $tokenDecode->correo)->pluck('id')->first();

            //! BUSCO EL REGISTRO CONFIRM CORREO EN BASE AL ID
            $confirmCorreo = confirmar_correo::find($idConfirmCorreo);
            $confirmCorreo->estado = "CONFIRMADO";
            $confirmCorreo->token = null;
            $confirmCorreo->save(); //? GUARDO LOS CAMBIOS EN EL REGISTRO

            // TODO: VALIDAMOS QUE ROL SEA IGUAL A (ADMIN FRACCIONAMIENTO)
            // TODO: PARA REGISTRAR UN NUEVO FRACCIONAMIENTO
            $fraccionamiento = new fraccionamiento(); //INSTANCIA DEL MODEL FRACCIONAMIENTO
            if ($tokenDecode->rol == "ADMIN FRACCIONAMIENTO") {
                //! CREO UN NUEVO FRACCIONAMIENTO
                $fraccionamiento->nombre = $tokenDecode->nombre_fraccionamiento;
                $fraccionamiento->codigo_postal = $tokenDecode->codigo_postal;
                $fraccionamiento->save();
            }

            //! CREO UN NUEVO REGISTRO PARA EL USUARIO
            $usuario = new usuario;
            $usuario->nombre = $tokenDecode->nombre;
            $usuario->apellido = $tokenDecode->apellidos;
            $usuario->correo = $tokenDecode->correo;
            $usuario->password = Hash::make($request->password);
            $usuario->token = null;
            $usuario->id_confirmar_correo = $idConfirmCorreo;

            // TODO: VALIDAMOS QUE ROL SEA DIFERENTE A (ADMIN FRACCIONAMIENTO)
            // TODO: Y OBTENEMOS EL ID DEL FRACCIONAMIENTO EN BASE A SU NOMBRE
            if ($tokenDecode->rol != "ADMIN FRACCIONAMIENTO") {
                $idFraccionamiento = fraccionamiento::where('nombre', $tokenDecode->nombre_fraccionamiento)->pluck('id')->first();
                $usuario->id_fraccionamiento = $idFraccionamiento;
            } else {
                $usuario->id_fraccionamiento = $fraccionamiento->id;
            }
            $usuario->save(); //SE GUARDA EL NUEVO USUARIO

            //! REGISTRAR ROL DE TIPO ADMIN DE FRACCIONAMEINTO
            $rolPorUsuario = new RolPorUsuario;
            $rolPorUsuario->id_usuario = $usuario->id;

            // TODO: OBTENER EL ID DEL ROL EN BASE A SU DESCRIPCION
            $idRol = Rol::where('descripcion', $tokenDecode->rol)->pluck('id')->first();
            $rolPorUsuario->id_rol = $idRol; //id 2 = ADMINISTRADOR DE FRACCIONAMIENTO
            $rolPorUsuario->save();
            $mensaje->icon = "success";
            $mensaje->title = "Su cuenta se ha creado con exito";
            $mensaje->body = $idRol;



        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            $mensaje->title = "Firma de token no válida";
            $mensaje->icon = "error";
            // Handle the error
            return response()->json($mensaje, 401);
        } catch (\Firebase\JWT\BeforeValidException $e) {
            $mensaje->title = "Token aún no válido";
            $mensaje->icon = "error";
            return response()->json($mensaje, 401);
        } catch (\Firebase\JWT\ExpiredException $e) {
            $mensaje->title = "El token ha caducado";
            $mensaje->icon = "error";
            return response()->json($mensaje, 401);
        } catch (\Exception $e) {
            $mensaje->icon = "error";
            $mensaje->title = "Esta peticion no fue autorizada";
            $mensaje->body = $e->getMessage();
            return response()->json($mensaje, 401);
        }



        return response()->json($mensaje, 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function show(usuario $usuario)
    {
        $mensaje = new mensaje();

        $mensaje->title = "";
        $mensaje->icon = "success";
        $mensaje->body = $usuario->with('roles')->get();


        return response()->json($mensaje, 200);
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