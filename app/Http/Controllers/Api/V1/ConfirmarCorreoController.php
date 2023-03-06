<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\Controller;
use App\Mail\confirmarRegistroFraccionamiento;
use App\Models\confirmar_correo;
use App\Models\mensaje;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ConfirmarCorreoController extends Controller
{

    public function checkTokenRegistroFraccionamiento(Request $request)
    {
        $mensaje = new mensaje();
        if (!$request->token) {
            $mensaje->icon = "error";
            $mensaje->title = "Solicitud no valida";
            return response()->json($mensaje, 401);
        }

        $confirmarCorreo = confirmar_correo::where('token', '=', $request->token)->first();
        if ($confirmarCorreo === null) {
            $mensaje->icon = "error";
            $mensaje->title = "Esta solicitud no fue encontrada";
            return response()->json($mensaje, 401);
        }

        try {

            $jwt = new JWTController();
            $decodeToken = $jwt->decodeTokenActivarCuenta("9999999" . $request->token);
            $now = time();
            if ($now > $decodeToken->exp) {
                $mensaje->icon = "error";
                $mensaje->title = "Esta solicitud ya no es valida";
                return response()->json($mensaje, 401);
            }

            $mensaje->title = "";
            $mensaje->icon = "success";
            return response()->json($mensaje, 204);
        } catch (\Exception $e) {
            $mensaje->title = "Ha ocurrido un problema interno en el sistema ,por favor informe a los administradores";
            $mensaje->body = ["error" => $e->getMessage()];
            $mensaje->icon = "info";
            return response()->json($mensaje, 500);
        }







    }


    /**
     * ? METODO ENCARGADO DE CONFIRMAR EL REGISTRO DE LA CUENTA DEL USUARIO ,
     * ? EL USUARIO RECIBIRA INDICACIONES POR CORREO PARA FINALIZAR SU REGISTRO
     * 
     *   @param  \Illuminate\Http\Request  $request 
     * ? TABLA USUARIOS
     * ! (nombre(s) ,apellidos ,correo) ,
     * ? TABLA FRACCIONAMIENTO
     * ! (nombre ,codigo postal)
     *   @return \Illuminate\Http\Response
     * ! mensaje = {title:"" ,icon:"" ,body:{}}
     */
    public function confirmarRegistroFraccionamiento(Request $request)
    {
        $mensaje = new mensaje();

        $validation = Validator::make($request->all(), [
            'correo' => 'required|email|unique:usuarios,correo',
            'nombre' => 'required|max:40',
            'apellidos' => 'required|max:40',
            'nombre_fraccionamiento' => 'required|max:40|unique:fraccionamientos,nombre',
            'codigo_postal' => 'required|numeric|digits:5|unique:fraccionamientos,codigo_postal'
        ]);

        if ($validation->fails()) {
            $mensaje->body = $validation->errors();
            $mensaje->title = "error";
            $mensaje->icon = "error";
            return response()->json($mensaje, 422);
        }

        $jwt = new JWTController();
        $token = $jwt->generateTokenActivarCuenta($request);


        $appUrl = env('APP_URL');
        $puerto = "";
        if ($appUrl == "http://localhost") {
            $puerto = ":4200/";
        }
        //LINK ENCARGADO DE REDIRECCIONAR A UNA VISTA PARA REALIZAR CONFIRMACION DE CUENTA
        $linkConfirmarRegistro = $appUrl . $puerto . 'registros/registro/acptContrsAdmnFracc?token=' . $token;

        //ENVIAR CORREO CON TOKEN PARA SEGUIR CON EL PROCESO DE REGISTRO

        //CREAR DATA QUE SE ENVIARA POR CORREO
        $data = [
            'nombre' => $request->nombre,
            'token' => $linkConfirmarRegistro
        ];

        //ENVIAR CORREO
        try {
            Mail::to($request->correo)->send(new confirmarRegistroFraccionamiento($data));
            $mensaje->title = "Se le envio un correo con los pasos para finalizar su proceso de registro de su fraccionamiento";
            $mensaje->icon = "info";
            //$mensaje->body = ['token' => $token];


            $id = confirmar_correo::where('correo', $request->correo)->pluck('id')->first();
            if ($id != null) {
                $ConfirmEmail = confirmar_correo::find($id);
                $ConfirmEmail->token = $token;
                $ConfirmEmail->save();
            } else {
                //GENERAR REGISTRO EN TABLE CONFIRM-EMAIL
                //USUARIO->?
                //ESTADO->ENVIADO
                //MOTIVO->REGISTRO
                //TOKEN->?
                //NUEVO_CORREO->NULL --SE UTILIZA PARA CUANDO EL USUARIO CAMBIE DE CORREO
                $ConfirmEmail = new confirmar_correo();
                $ConfirmEmail->correo = $request->correo;
                $ConfirmEmail->estado = "ENVIADO";
                $ConfirmEmail->motivo = "REGISTRO";
                $ConfirmEmail->token = $token;
                $ConfirmEmail->nuevo_correo = null;
                $ConfirmEmail->save();
            }


        } catch (\Exception $e) {
            $mensaje->title = "Ha ocurrido un problema interno en el sistema ,por favor informe a los administradores";
            $mensaje->body = ["error" => $e->getMessage()];
            $mensaje->icon = "info";
            return response()->json($mensaje, 500);

        }

        return response()->json($mensaje, 201);

    }

}