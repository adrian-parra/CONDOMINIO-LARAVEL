<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\Controller;
use Exception;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTController extends Controller
{
    private $secretKey;
    private $secretKeyConfimCuenta;

    public function __construct()
    {
        $this->secretKey = env('JWT_SECRET');
        $this->secretKeyConfimCuenta = env('JWT_SECRET_CONFIRM_CUENTA');
    }

    /**
     * ! METODOS PARA EL PROCESO DE REGISTRO DE USUARIO
     */
    /**
     * ? GENERA TOKEN QUE SE LE ENVIA POR CORREO
     * ? AL USUARIO AL REGISTRARSE EN EL SISTEMA.
     * ? EL TOKEN CONTIENE TODOS SUS DATOS DE REGISTRO EN SU
     * ? PAYLOAD 
     */
    public function generateTokenActivarCuenta(Request $request)
    {
        $payload = [
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'correo' => $request->correo,
            'nombre_fraccionamiento' => $request->nombre_fraccionamiento,
            'codigo_postal' => $request->codigo_postal,
            'iat' => time(),
            'exp' => time() + 30 * 60 //VALIDO DURANTE 30 MINUTOS
        ];

        $jwt = JWT::encode($payload, $this->secretKeyConfimCuenta, 'HS256');
        return $jwt;
    }

    /**
     * ? SE ENCARGA DE OBTENER LOS DATOS QUE EL TOKEN TIENE EN SU PAYLOAD
     * ? EN ESTE CASO TIENE LOS DATOS DE REGISTRO DEL USUARIO
     */
    public function decodeTokenActivarCuenta($token)
    {
        $tkn = substr($token, 7, strlen($token));
        $jwtDecode = JWT::decode($tkn, new key($this->secretKeyConfimCuenta, 'HS256'));
        return $jwtDecode;
    }

    /**
     * ! AQUI TERMINA LOS METODOS PARA EL PROCESO DE REGISTRO DE USUARIO
     */
    //
    public function generateToken($correo ,$id)
    {

        $payload = [
            'userId' => $id,
            'correo' => $correo,
            'iat' => time(),
            'exp' => time() + 60 * 60
        ];

        $jwt = JWT::encode($payload, $this->secretKey, 'HS256');

        return $jwt;
    }


   
   

    public function validateToken(Request $request)
    {
        $jwt = $request->header("authorization");

        $secretKey = env('JWT_SECRET');



        try {
            $tkn = substr($jwt, 7, strlen($jwt));
            $decoded = JWT::decode($tkn, new key($secretKey, 'HS256'));

            return response()->json(['status' => 'valid', 'data' => $decoded]);
        } catch (Exception $e) {
            return response()->json(['status' => 'invalid']);
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            // Handle the error
            return response()->json(['error' => 'Firma de token no válida'], 401);
        }
    }

}