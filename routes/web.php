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

// Ruta de prueba para PDF
Route::get('/test-pdf', function() {
    try {
        // Datos de prueba completos para el template de comprobantes
        $data = [
            'venta' => (object) [
                'id' => 1,
                'serie' => 'V001',
                'numero' => '000001',
                'fecha' => now(),
                'fecha_venta' => now(),
                'subtotal' => 84.75,
                'igv' => 15.25,
                'total' => 100.00,
                'xml_estado' => 'ACEPTADO',
                'qr_hash' => null,
                'cliente' => (object) [
                    'razon_social' => 'Cliente de Prueba S.A.C.',
                    'nombre' => 'Cliente de Prueba',
                    'tipo_documento' => 'RUC',
                    'numero_documento' => '20123456789',
                    'direccion' => 'Av. Los Constructores 123',
                    'telefono' => '987654321'
                ]
            ],
            'cliente' => (object) [
                'razon_social' => 'Cliente de Prueba S.A.C.',
                'nombre' => 'Cliente de Prueba',
                'tipo_documento' => 'RUC',
                'numero_documento' => '20123456789',
                'direccion' => 'Av. Los Constructores 123',
                'telefono' => '987654321'
            ],
            'detalles' => collect([
                (object) [
                    'producto' => (object) [
                        'codigo' => 'PROD001',
                        'descripcion' => 'Producto de Prueba 1'
                    ],
                    'cantidad' => 2,
                    'precio_unitario' => 30.00,
                    'descuento_porcentaje' => 0,
                    'precio_final' => 30.00,
                    'total' => 60.00
                ],
                (object) [
                    'producto' => (object) [
                        'codigo' => 'PROD002',
                        'descripcion' => 'Producto de Prueba 2'
                    ],
                    'cantidad' => 1,
                    'precio_unitario' => 40.00,
                    'descuento_porcentaje' => 0,
                    'precio_final' => 40.00,
                    'total' => 40.00
                ]
            ]),
            'fecha' => now(),
            'tipoComprobante' => (object) ['descripcion' => 'COMPROBANTE DE VENTA'],
            'moneda' => (object) ['descripcion' => 'Soles'],
            'descuentoTotal' => 0,
            'totalEnLetras' => 'CIEN CON 00/100 SOLES'
        ];
        
        $pdf = PDF::loadView('comprobantes.pdf', $data);
        return $pdf->stream('test-comprobante.pdf');
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

// Ruta de prueba directa para PDF con venta específica
Route::get('/test-pdf-venta/{id}', function($id) {
    $controller = new App\Http\Controllers\VentaController();
    return $controller->generarPDF($id);
});

// Ruta de prueba para confirm-cancel
Route::get('/test-confirm-cancel/{id}', function($id) {
    $controller = new App\Http\Controllers\VentaController();
    return $controller->confirmCancel($id);
});

// Ruta de prueba para cancel (POST)
Route::post('/test-cancel/{id}', function($id) {
    $controller = new App\Http\Controllers\VentaController();
    $request = new Illuminate\Http\Request();
    $request->merge(['motivo' => 'Anulación de prueba']);
    return $controller->cancel($request, $id);
});

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
    
    // Ruta específica para crear ventas (formulario personalizado)
    // Route::get('/ventas/crear', [VentaController::class, 'create'])->name('ventas.create');
    
    // Ruta alternativa personalizada para nueva venta
    Route::get('/venta/create', [VentaController::class, 'create'])->name('venta.create');
    
    // Acciones especiales de ventas
    Route::prefix('ventas')->name('ventas.')->group(function () {
        Route::get('{venta}/confirm-cancel', [VentaController::class, 'confirmCancel'])->name('confirm-cancel');
        Route::patch('{venta}/cancel', [VentaController::class, 'cancel'])->name('cancel');
        Route::get('{venta}/pdf', [VentaController::class, 'generarPDF'])->name('pdf');
        Route::post('{venta}/convertir', [VentaController::class, 'convertirCotizacion'])->name('convertir');
        Route::get('{venta}/convertir-factura', [VentaController::class, 'convertirAFactura'])->name('convertir-factura');
        Route::get('{venta}/convertir-boleta', [VentaController::class, 'convertirABoleta'])->name('convertir-boleta');
        Route::get('{venta}/xml', [VentaController::class, 'generarXML'])->name('xml');
        Route::get('{venta}/xml-download', [VentaController::class, 'descargarXML'])->name('xml-download');
        Route::get('tipo-cambio', [VentaController::class, 'obtenerTipoCambioActual'])->name('tipo-cambio');
        Route::post('tipo-cambio/forzar', [VentaController::class, 'actualizarTipoCambioForzado'])->name('tipo-cambio-forzar');
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
    // Simplificar - solo mantener lo esencial
    Route::prefix('api')->name('api.')->group(function () {
        // API de ventas
        Route::prefix('ventas')->name('ventas.')->group(function () {
            Route::post('guardar', [VentaController::class, 'guardarVenta'])->name('guardar');
        });
    });
});

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS DE API (Para búsquedas AJAX sin autenticación)
|--------------------------------------------------------------------------
*/
// Búsqueda de productos para formulario de ventas (sin autenticación)
Route::get('/api/productos/search', [VentaController::class, 'buscarProducto'])->name('api.productos.search');

// Búsqueda de clientes para formulario de ventas (sin autenticación) 
Route::get('/api/clientes/search', [VentaController::class, 'buscarCliente'])->name('api.clientes.search');

// Obtener siguiente número de comprobante
Route::get('/api/ventas/siguiente-numero', [VentaController::class, 'siguienteNumero'])->name('api.ventas.siguiente-numero');

// Ruta para cerrar sesión
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login')->with('message', 'Sesión cerrada exitosamente');
})->name('logout');

// Ruta para registrar un nuevo cliente
Route::post('/api/clientes', [ClienteController::class, 'store'])->name('api.clientes.store');
