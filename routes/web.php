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
use Illuminate\Support\Facades\Hash;

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

// Ruta temporal para login rápido
Route::get('/quick-login', function () {
    $user = \App\Models\Usuario::where('usuario', 'admin')->first();
    if ($user) {
        Auth::login($user);
        return redirect('/dashboard');
    }
    return 'Usuario no encontrado';
});

// Rutas de autenticación
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'usuario' => ['required', 'string'],
        'password' => ['required'],
    ]);

    // Intentar login con Auth::attempt
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('dashboard');
    }

    return back()->withErrors([
        'usuario' => 'Las credenciales no coinciden con nuestros registros.',
    ])->onlyInput('usuario');
});

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    $user = \App\Models\User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
    ]);

    Auth::login($user);

    return redirect('dashboard');
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

// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    // Ruta para el dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Routes for Gestion de Ventas
    Route::resource('ventas', VentaController::class);
    // Route for confirm canceling sales
    Route::get('ventas/{venta}/confirm-cancel', [VentaController::class, 'confirmCancel'])->name('ventas.confirm-cancel');
    // Route for canceling sales
    Route::patch('ventas/{venta}/cancel', [VentaController::class, 'cancel'])->name('ventas.cancel');
    // Route for generating PDF
    Route::get('ventas/{venta}/pdf', [VentaController::class, 'generarPDF'])->name('ventas.pdf');

    // Rutas para el recurso 'detalle_ventas'
    Route::resource('detalle_ventas', DetalleVentaController::class);

    // Rutas para el recurso 'comprobantes_electronicos'
    Route::resource('comprobantes_electronicos', ComprobanteElectronicoController::class);

    // Rutas para el recurso 'comprobante_archivos'
    Route::resource('comprobante_archivos', ComprobanteArchivoController::class);
    
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

    // Rutas para el CRUD de clientes
    Route::resource('clientes', ClienteController::class);

    // Rutas para el CRUD de monedas
    Route::resource('monedas', MonedaController::class);

    // Ruta para buscar clientes
    Route::get('/api/clientes', [ClienteController::class, 'buscar'])->name('clientes.buscar');

    // Ruta para buscar productos
    Route::get('/api/productos', [ProductoController::class, 'buscar'])->name('productos.buscar');

    // Ruta para obtener detalles de un producto específico
    Route::get('/api/productos/{id}', [ProductoController::class, 'getDetails'])->name('productos.getDetails');
});

// Ruta para cerrar sesión
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login')->with('message', 'Sesión cerrada exitosamente');
})->name('logout');
