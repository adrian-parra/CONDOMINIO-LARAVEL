<?php

use App\Http\Controllers\Api\V1\ConfigurarPagosController;
use App\Http\Controllers\Api\V1\ConfirmarCorreoController;
use App\Http\Controllers\Api\V1\DetalleEgresoController;
use App\Http\Controllers\Api\V1\EgresoController;
use App\Http\Controllers\Api\V1\ProductoController;
use App\Http\Controllers\Api\V1\ProveedorController;
use App\Http\Controllers\Api\V1\ReciboController;
use App\Http\Controllers\Api\V1\TipoDeEgresoController;
use App\Http\Controllers\Api\V1\UsuarioController;
use App\Http\Controllers\Api\V1\VehiculoController;
use App\Utils\ObtenerArchivo;
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
 * ! MIDDLEWARE PARA AUTENTICACION DE TOKEN DE ACCESO JWT
 * ! PARA RUTAS PROTEGIDAS
 */
Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1', 'middleware' => 'jwt.auth'], function () {
    // Protected routes
   

        Route::apiResource('vehiculos', VehiculoController::class);
        Route::get('vehiculo/estados-de-mexico', [VehiculoController::class, 'getEstadosMexico'])
        ->middleware('role:ADMIN GENERAL,ADMIN FRACCIONAMIENTO');
});




Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1'], function () {
    
    // Debug Routes
    Route::apiResource('proveedores', ProveedorController::class);
    Route::apiResource('propietarios', PropietarioController::class);
    Route::apiResource('propiedades', PropiedadController::class);
    Route::apiResource('productos', ProductoController::class);
    
    Route::get('vehiculo/tipos-de-vehiculos', [VehiculoController::class, 'getTiposVehiculos']);
    Route::apiResource('configurar-pagos', ConfigurarPagosController::class);
    Route::get('/private-files/{foldername}/{filename}', [ObtenerArchivo::class, 'getFile']);
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
    Route::apiResource('recibos', ReciboController::class);
    Route::post(
        'generarRecibos',
        [ReciboController::class, 'generar_recibos']
    );

    Route::post('usuario/confirmar-registro', [ConfirmarCorreoController::class, 'confirmarRegistroFraccionamiento']);
    Route::post("usuario/confirmar-registro/check-token", [ConfirmarCorreoController::class, 'checkTokenRegistroFraccionamiento']);
    Route::post("usuario/registro", [UsuarioController::class, 'store']);
    Route::post("usuario/iniciar-sesion", [UsuarioController::class, 'iniciarSesion']);
});