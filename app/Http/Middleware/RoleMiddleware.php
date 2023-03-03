<?php

namespace App\Http\Middleware;

use App\Models\mensaje;
use App\Models\Rol;
use App\Models\RolPorUsuario;
use App\Models\usuario;
use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next ,...$roles)
    {

        $mensaje = new mensaje();

        $token = $request->header('Authorization');

        try{
            $token = substr($token, 7, strlen($token));
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));


            $userId =$decoded->userId;
            //OBTENGO LA DESCRIPCION DE LO ROLES ASIGNADOS A DICHO USUARIO
            //QUE INTENTA ACCEDER A LA RUTA
            $userRoles = Rol::whereHas('users', function ($query) use ($userId) {
                $query->where('id', $userId);
            })
            ->select('descripcion')
            ->get(); //RETORNO UN ARRAY DE OBJETOS DESCRIPCION DE ROLES

            //VERIFICAR SI USUARIO TIENE MAS DE UN ROL
            if(count($userRoles) > 1){
                foreach ($userRoles as $DescripcionRol) { //RECORRE LOS ROLES ENCONTRADOS
                    $rol = $DescripcionRol->descripcion; //ALMACENO SOLO LA DESCRIPCION DEL ROL
                    foreach ($roles as $role) { //RECORRO LOS ROLES QUE TIENE ASIGNADOS LA RUTA
                        if($rol == $role){
                           //USUARIO FUE AUTORIZADO PARA ACCEDER A LA RUTA
                           return $next($request);
                        }
                    }
    
                }
            }else{
                //USUARIO SOLO TIENE UN ROL
                $rol = $userRoles[0]->descripcion;
                
                foreach ($roles as $role) { //RECORRO LOS ROLES QUE TIENE ASIGNADOS LA RUTA
                    if($rol == $role){
                       //USUARIO FUE AUTORIZADO PARA ACCEDER A LA RUTA
                       return $next($request);
                    }
                }
          
            }

            //SI LA EJECUCION DEL PROGRAMA LLEGA HASTA AQUI
            //FUE POR QUE EL USUARIO NO ESTA AUTORIZADO PARA ACCEDER A DICHA RUTA
            $mensaje->title = "Su rol de usuario no tiene autorizacion para esta peticion";
            $mensaje->icon = "error";
            return response()->json($mensaje ,422);
            
        }catch(\Exception $e){
            $mensaje->title = "Exception detectada";
            $mensaje->icon = "error";
            $mensaje->body = $e->getMessage();
            return response()->json($mensaje ,422);
        }
    }
}
