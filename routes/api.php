<?php

use App\Http\Controllers\ConfirmarCorreoController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\JWTController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/



/**
* ! PRUEBAS CON RUTAS DE LA API
* Route::post('generate-token', [JWTController::class,'generateToken']);
* Route::post('validate-token', [JWTController::class,'validateToken']);
* Route::post('user/login', [JWTController::class,'login']); 
*/


/**
 * MIDDLEWARE PARA AUTENTICACION DE TOKEN DE ACCESO JWT
 * PARA RUTAS PROTEGIDAS
 */
Route::middleware(['jwt.auth'])->group(function () {
    // Protected routes
    Route::apiResource('usuario',UsuarioController::class);

});

Route::post('usuario/confirmar-registro' ,[ConfirmarCorreoController::class ,'confirmarRegistroFraccionamiento']);
Route::post("usuario/confirmar-registro/check-token" , [ConfirmarCorreoController::class ,'checkTokenRegistroFraccionamiento']);
Route::post("usuario/registro" ,[UsuarioController::class ,'store']);


