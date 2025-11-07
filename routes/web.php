<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Controllers
use App\Http\Controllers\{
    DashboardController,
    VentaController,
    DetalleVentaController,
    ComprobanteElectronicoController,
    ComprobanteArchivoController,
    ProductoController,
    ClienteController,
    MarcaController,
    ProveedorController,
    CategoriaController,
    MonedaController
};

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

/*
|--------------------------------------------------------------------------
| RUTAS PRINCIPALES
|--------------------------------------------------------------------------
*/

// Ruta raíz - Redirección inteligente
Route::get('/', fn() => Auth::check() ? redirect()->route('dashboard') : redirect()->route('login'));

/*
|--------------------------------------------------------------------------
| AUTENTICACIÓN
|--------------------------------------------------------------------------
*/

// Rutas de autenticación (públicas)
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', fn() => view('auth.login'))->name('login');
    Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'usuario' => ['required', 'string'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'usuario' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('usuario');
    });

    // Registro (si es necesario)
    Route::get('/register', fn() => view('auth.register'))->name('register');
    Route::post('/register', function (Request $request) {
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
});

// Logout
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login')->with('message', 'Sesión cerrada exitosamente');
})->name('logout');

// Ruta de desarrollo para login rápido (solo para desarrollo)
Route::get('/quick-login', function () {
    if (app()->environment('local')) {
        $user = \App\Models\Usuario::where('usuario', 'admin')->first();
        if ($user) {
            Auth::login($user);
            return redirect('/dashboard');
        }
    }
    return redirect()->route('login');
})->name('quick-login');

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS (Requieren Autenticación)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    /*
    |--------------------------------------------------------------------------
    | MÓDULO DE VENTAS
    |--------------------------------------------------------------------------
    */
    // CRUD básico de ventas
    Route::resource('ventas', VentaController::class);
    
    // Acciones especiales de ventas
    Route::prefix('ventas')->name('ventas.')->group(function () {
        Route::get('{venta}/confirm-cancel', [VentaController::class, 'confirmCancel'])->name('confirm-cancel');
        Route::patch('{venta}/cancel', [VentaController::class, 'cancel'])->name('cancel');
        Route::get('{venta}/pdf', [VentaController::class, 'generarPDF'])->name('pdf');
    });
    
    // Detalle de ventas (si es necesario como recurso independiente)
    Route::resource('detalle_ventas', DetalleVentaController::class);
    
    /*
    |--------------------------------------------------------------------------
    | MÓDULO DE COMPROBANTES
    |--------------------------------------------------------------------------
    */
    Route::prefix('comprobantes')->name('comprobantes.')->group(function () {
        Route::resource('electronicos', ComprobanteElectronicoController::class);
        Route::resource('archivos', ComprobanteArchivoController::class);
    });
    
    /*
    |--------------------------------------------------------------------------
    | MÓDULO DE INVENTARIO
    |--------------------------------------------------------------------------
    */
    // Productos
    Route::resource('productos', ProductoController::class);
    Route::resource('marcas', MarcaController::class);
    Route::resource('categorias', CategoriaController::class);
    
    /*
    |--------------------------------------------------------------------------
    | MÓDULO DE CONTACTOS
    |--------------------------------------------------------------------------
    */
    Route::resource('clientes', ClienteController::class);
    Route::resource('proveedores', ProveedorController::class)->parameters(['proveedores' => 'proveedor']);
    
    /*
    |--------------------------------------------------------------------------
    | MÓDULO DE CONFIGURACIÓN
    |--------------------------------------------------------------------------
    */
    Route::resource('monedas', MonedaController::class);
    
    /*
    |--------------------------------------------------------------------------
    | API ENDPOINTS (Para AJAX y funcionalidades dinámicas)
    |--------------------------------------------------------------------------
    */
    Route::prefix('api')->name('api.')->group(function () {
        // Búsquedas de clientes
        Route::prefix('clientes')->name('clientes.')->group(function () {
            Route::get('/', [ClienteController::class, 'buscar'])->name('buscar');
            Route::get('buscar-documento', [ClienteController::class, 'buscarPorDocumento'])->name('buscar-documento');
        });
        
        // Búsquedas de productos
        Route::prefix('productos')->name('productos.')->group(function () {
            Route::get('/', [ProductoController::class, 'buscar'])->name('buscar');
            Route::get('{id}', [ProductoController::class, 'getDetails'])->name('details');
        });
    });
});

// Ruta para cerrar sesión
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login')->with('message', 'Sesión cerrada exitosamente');
})->name('logout');
