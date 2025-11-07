<?php

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

use App\Http\Controllers\Api\BusquedaController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rutas para búsqueda de clientes y productos en ventas
Route::get('/clientes', [BusquedaController::class, 'clientes']);
Route::get('/clientes/buscar/{documento}', [BusquedaController::class, 'buscarPorDocumento']);
Route::get('/productos', [BusquedaController::class, 'productos']);
Route::get('/productos/{id}', [BusquedaController::class, 'producto']);
