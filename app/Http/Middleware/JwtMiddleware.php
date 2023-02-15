<?php

namespace App\Http\Middleware;

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
    // Check if the token is present
    if (!$token) {
        return response()->json(['error' => 'Token no proporcionado'], 401);
    }

    try {
        // Decode the JWT token
        $token = substr($token, 7, strlen($token));
        $decoded = JWT::decode($token, new key(env('JWT_SECRET'), 'HS256'));

        // Do something with the decoded data
    } catch (\Firebase\JWT\SignatureInvalidException $e) {
        // Handle the error
        return response()->json(['error' => 'Firma de token no válida'], 401);
    } catch (\Firebase\JWT\BeforeValidException $e) {
        return response()->json(['error' => 'Token aún no válido'], 401);
    } catch (\Firebase\JWT\ExpiredException $e) {
        return response()->json(['error' => 'El token ha caducado'], 401);
    }catch(\Exception $e){
        return response()->json(['error' => 'El token no fue autorizado'], 401);        
    }
    
        return $next($request);
    }
}
