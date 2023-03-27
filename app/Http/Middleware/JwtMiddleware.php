<?php

namespace App\Http\Middleware;

use App\Models\mensaje;
use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Get the token from the request header
    $token = $request->header('Authorization');

    $mensaje = new mensaje();
    // Check if the token is present
    if (!$token) {
        $mensaje->title = "Token no proporcionado";
        $mensaje->icon = "error";
        return response()->json($mensaje, 401);
    }

    try {
        // Decode the JWT token
        $token = substr($token, 7, strlen($token));
        $decoded = JWT::decode($token, new key(env('JWT_SECRET'), 'HS256'));

        // Do something with the decoded data
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
        $mensaje->status = "999"; // ! ESTE ESTATUS SIRVE PARA INFORMAR AL FRONT QUE SU SESION HA EXPÍRADO
        $mensaje->icon = "error";
        return response()->json($mensaje, 401);
    }catch(\Exception $e){
        $mensaje->title = "El token no fue autorizado";
        $mensaje->icon = "error";
        return response()->json($mensaje, 401);        
    }
    
        return $next($request);
    }
}
