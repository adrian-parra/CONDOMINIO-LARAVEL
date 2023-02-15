<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\users;
class JWTController extends Controller
{

    

    
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
