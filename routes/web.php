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
    MonedaController,
    PdfController,
    RolController,
    UsuarioController,
    ReporteController,
    ReportePeriodoController
};

/*
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
        
        // Ruta temporal para testing de cotizaciones
        Route::post('crear-cotizacion-prueba', [VentaController::class, 'crearCotizacionPrueba'])->name('crear-cotizacion-prueba');
        Route::get('tipo-cambio', [VentaController::class, 'obtenerTipoCambioActual'])->name('tipo-cambio');
        Route::post('tipo-cambio/forzar', [VentaController::class, 'actualizarTipoCambioForzado'])->name('tipo-cambio-forzar');
    });
    
    /*
    |--------------------------------------------------------------------------
    | GENERACIÓN DE PDFs PARA TODOS LOS COMPROBANTES
    |--------------------------------------------------------------------------
    */
    Route::prefix('pdf')->name('pdf.')->group(function () {
        // Generar y descargar PDF de cualquier comprobante
        Route::get('comprobante/{venta}/download', [PdfController::class, 'generatePdf'])->name('download');
        
        // Ver PDF en el navegador sin descargar
        Route::get('comprobante/{venta}/view', [PdfController::class, 'viewPdf'])->name('view');
        
        // Generar código QR para comprobante electrónico
        Route::get('comprobante/{venta}/qr', [PdfController::class, 'generarQR'])->name('qr');
        
        // Funciones avanzadas de PDF
        Route::post('lote/generar', [PdfController::class, 'generarLotePdfs'])->name('lote.generar');
        Route::post('comprobante/{venta}/enviar-email', [PdfController::class, 'enviarPorEmail'])->name('enviar-email');
        Route::get('estadisticas', [PdfController::class, 'estadisticasPdf'])->name('estadisticas');
        
        // Debug: Ver mapeo de tipos de comprobante
        Route::get('debug/tipos-comprobante', [PdfController::class, 'debugTiposComprobante'])->name('debug.tipos');
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
    
    /*
    |--------------------------------------------------------------------------
    | ROL Y USUARIO
    |--------------------------------------------------------------------------
    */
    Route::resource('roles', RolController::class);
    Route::resource('usuarios', UsuarioController::class);
    
    /*
    |--------------------------------------------------------------------------
    | REPORTES Y EXPORTACIONES
    |--------------------------------------------------------------------------
    */
    Route::prefix('reportes')->name('reportes.')->group(function () {
        // Reportes básicos
        Route::get('ventas', [ReporteController::class, 'ventas'])->name('ventas');
        Route::get('productos-vendidos', [ReporteController::class, 'productosVendidos'])->name('productos-vendidos');
        
        // Exportaciones a Excel
        Route::get('exportar-ventas', [ReporteController::class, 'exportarVentas'])->name('exportar.ventas');
        Route::get('exportar-productos', [ReporteController::class, 'exportarProductos'])->name('exportar.productos');
        
        // Reportes por periodo usando procedimientos almacenados
        Route::get('periodos', [ReportePeriodoController::class, 'index'])->name('periodos');
        Route::get('periodos/export', [ReportePeriodoController::class, 'export'])->name('reportes.periodos.export');
    });
});
