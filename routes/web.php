<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\MonedaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\DetalleVentaController;
use App\Http\Controllers\ComprobanteElectronicoController;
use App\Http\Controllers\ComprobanteArchivoController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Rutas para la gestión de productos
Route::resource('productos', ProductoController::class);


// Rutas para el CRUD de marcas
Route::resource('marcas', MarcaController::class);

// Rutas para la gestión de proveedores
Route::resource('proveedores', ProveedorController::class)->parameters([
    'proveedores' => 'proveedor'
]);

// Rutas para el CRUD de categorias
Route::resource('categorias', CategoriaController::class);

// Rutas para el CRUD de productos
Route::resource('productos', ProductoController::class);

// Rutas para el CRUD de clientes
Route::resource('clientes', ClienteController::class);

// Rutas para el CRUD de monedas
Route::resource('monedas', MonedaController::class);

// Ruta para el dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Ruta para cerrar sesión
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

// Routes for Gestion de Ventas
Route::resource('ventas', VentaController::class);
// Route for canceling sales
Route::patch('ventas/{venta}/cancel', [VentaController::class, 'cancel'])->name('ventas.cancel');

// Rutas para el recurso 'detalle_ventas'
Route::resource('detalle_ventas', DetalleVentaController::class);

// Rutas para el recurso 'comprobantes_electronicos'
Route::resource('comprobantes_electronicos', ComprobanteElectronicoController::class);

// Rutas para el recurso 'comprobante_archivos'
Route::resource('comprobante_archivos', ComprobanteArchivoController::class);

// Ruta para buscar clientes
Route::get('/api/clientes', [ClienteController::class, 'buscar'])->name('clientes.buscar');

// Ruta para buscar productos
Route::get('/api/productos', [ProductoController::class, 'buscar'])->name('productos.buscar');

// Ruta para obtener detalles de un producto específico
Route::get('/api/productos/{id}', [ProductoController::class, 'getDetails'])->name('productos.getDetails');
