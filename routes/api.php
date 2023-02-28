<?php

use App\Http\Controllers\Api\V1\ConfigurarPagosController;
use App\Http\Controllers\Api\V1\ConfirmarCorreoController;
use App\Http\Controllers\Api\V1\DetalleEgresoController;
use App\Http\Controllers\Api\V1\EgresoController;
use App\Http\Controllers\Api\V1\ProductoController;
use App\Http\Controllers\Api\V1\ProveedorController;
use App\Http\Controllers\Api\V1\TipoDeEgresoController;
use App\Http\Controllers\Api\V1\UsuarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1', 'middleware' => 'jwt.auth'], function () {
    // Protected routes
   //Route::apiResource('usuario', UsuarioController::class);
    Route::apiResource('configurar-pagos' ,ConfigurarPagosController::class);
    // Route::apiResource('propietario', PropietarioController::class);
    // Route::apiResource('propiedad', Propiedad::class);

});

    Route::apiResource('proveedores', ProveedorController::class);


Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1'], function () {
    // Debug Routes
    Route::apiResource('propietarios', PropietarioController::class);
    Route::apiResource('propiedades', PropiedadController::class);
    Route::apiResource('productos', ProductoController::class);
    Route::apiResource('egresos', EgresoController::class);
    Route::apiResource('tipoEgresos', TipoDeEgresoController::class);
    Route::post(
        'detalleEgresos',
        [DetalleEgresoController::class, 'store']
    );
    Route::match(
        ['PUT', 'DELETE', 'PATCH'],
        'detalleEgresos/{id_egreso}/{id_producto}',
        [DetalleEgresoController::class, 'update_or_delete']
    );
});

Route::post('usuario/confirmar-registro', [ConfirmarCorreoController::class, 'confirmarRegistroFraccionamiento']);
Route::post("usuario/confirmar-registro/check-token", [ConfirmarCorreoController::class, 'checkTokenRegistroFraccionamiento']);
Route::post("usuario/registro", [UsuarioController::class, 'store']);
Route::post("usuario/iniciar-sesion", [UsuarioController::class, 'iniciarSesion']);
