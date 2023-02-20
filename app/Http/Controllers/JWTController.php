<?php

namespace App\Http\Controllers;

use App\Models\confirmar_correo;
use App\Models\mensaje;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
class JWTController extends Controller
{

    

    public function decodeToken($token){
        $secretKey = env('JWT_SECRET');
        $tkn = substr($token, 7, strlen($token));
        $jwtDecode = JWT::decode($tkn,new key($secretKey ,'HS256'));
        return $jwtDecode;
    }
    //
    public function generateToken(Request $request)
    {
        $payload = [
            'iss' => "laravel",
            'sub' => $request->email,
            'iat' => time(),
            'exp' => time() + 60*60
        ];

        $secretKey = env('JWT_SECRET');

 $jwt = JWT::encode($payload, $secretKey ,'HS256');

        return response()->json(['token' => $jwt]);
    }

    public function checkTokenRegistroFraccionamiento(Request $request){
        $mensaje = new mensaje();
        if (!$request->token) {
            $mensaje->icon = "error";
            $mensaje->title = "Solicitud no valida";
            return response()->json([$mensaje], 401);
        }

        $confirmarCorreo = confirmar_correo::where('token', '=', $request->token)->first();
        if($confirmarCorreo === null){
            $mensaje->icon = "error";
            $mensaje->title = "Esta solicitud no fue encontrada";
            return response()->json([$mensaje],422);
        }

        try{
            $decodeToken = $this->decodeToken("9999999".$request->token);
            $now = time();
            if($now > $decodeToken->exp){
                $mensaje->icon = "error";
                $mensaje->title = "Esta solicitud ya no es valida";
                return response()->json([$mensaje],422);
            }

            $mensaje->title = "";
            $mensaje->icon = "success";
            return response()->json([$mensaje],200);
        }catch(\Exception $e){
            $mensaje->icon = "error";
            $mensaje->body = $e->getMessage();
            $mensaje->title = "Ocurrio un proble con su solicitud";
            return response()->json([$mensaje],422);
        }
       

        




    }

    public function generateTokenRegistroFraccionamiento(Request $request){
        $payload = [
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'correo' => $request->correo,
            'nombre_fraccionamiento' => $request->nombre_fraccionamiento,
            'codigo_postal' => $request->codigo_postal,
            'iat' => time(),
            'exp' => time() + 30*60 //VALIDO DURANTE 30 MINUTOS
        ];

        $secretKey = env('JWT_SECRET');

        $jwt = JWT::encode($payload, $secretKey ,'HS256');

        return $jwt;
    }

    public function validateToken(Request $request)
    {   
        $jwt = $request->header("authorization");

        $secretKey = env('JWT_SECRET');



        try {
            $tkn = substr($jwt, 7, strlen($jwt));
            $decoded = JWT::decode($tkn , new key($secretKey,'HS256'));

            return response()->json(['status' => 'valid' ,'data'=>$decoded]);
        } catch (Exception $e) {
            return response()->json(['status' => 'invalid']);
        }catch (\Firebase\JWT\SignatureInvalidException $e) {
            // Handle the error
            return response()->json(['error' => 'Firma de token no válida'], 401);
        }
    }

}
