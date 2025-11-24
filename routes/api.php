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

use App\Http\Controllers\{
    PdfController,
    ProductoController,
    ClienteController,
    VentaController
};

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| API Routes para generación de PDFs (Sin autenticación para flexibility)
|--------------------------------------------------------------------------
*/
Route::prefix('pdf')->group(function () {
    // Generar PDF de comprobante (API)
    Route::get('comprobante/{venta}', [PdfController::class, 'generatePdf']);
    Route::get('comprobante/{venta}/view', [PdfController::class, 'viewPdf']);
    Route::get('comprobante/{venta}/qr', [PdfController::class, 'generarQR']);
});

/*
|--------------------------------------------------------------------------
| API Routes para búsquedas (Sin autenticación para el formulario de ventas)
|--------------------------------------------------------------------------
*/
// Búsqueda de productos para el formulario de ventas
Route::get('/productos/search', [ProductoController::class, 'searchPublic']);

// Búsqueda de clientes para el formulario de ventas
Route::get('/clientes/search', [ClienteController::class, 'searchPublic']);

// Crear nuevo cliente desde formulario de ventas
Route::post('/clientes', [ClienteController::class, 'storePublic']);

// Guardar nueva venta desde formulario
Route::post('/ventas/guardar', [VentaController::class, 'guardarVenta']);

// Obtener siguiente número de comprobante
Route::get('/ventas/siguiente-numero', [VentaController::class, 'siguienteNumero']);
