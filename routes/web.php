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
    PdfController
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

// Ruta de test para configuración PDF
Route::get('/test-pdf-config', function () {
    echo "<h2>🔍 TEST DE CONFIGURACIÓN PDF</h2>";
    
    $controller = new \App\Http\Controllers\PdfController();
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('getConfiguracionTipoComprobante');
    $method->setAccessible(true);
    
    // Simular diferentes tipos de comprobante según el seeder
    $tiposAProbar = [
        (object) ['codigo_sunat' => '01', 'descripcion' => 'Factura'],
        (object) ['codigo_sunat' => '03', 'descripcion' => 'Boleta de Venta'],
        (object) ['codigo_sunat' => '07', 'descripcion' => 'Nota de Crédito'],
        (object) ['codigo_sunat' => '08', 'descripcion' => 'Nota de Débito'],
        (object) ['codigo_sunat' => '09', 'descripcion' => 'Guía de Remisión'],
        (object) ['codigo_sunat' => '12', 'descripcion' => 'Ticket de Máquina Registradora'],
        (object) ['codigo_sunat' => '14', 'descripcion' => 'Recibo por Honorarios'],
        (object) ['codigo_sunat' => 'CT', 'descripcion' => 'Cotización'],
    ];
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Código SUNAT</th><th>Descripción</th><th>Template</th><th>Estado</th></tr>";
    
    foreach ($tiposAProbar as $tipo) {
        $config = $method->invoke($controller, $tipo);
        $estado = $config ? "✅ OK" : "❌ ERROR";
        $template = $config ? $config['template'] : 'N/A';
        
        echo "<tr>";
        echo "<td>{$tipo->codigo_sunat}</td>";
        echo "<td>{$tipo->descripcion}</td>";
        echo "<td>{$template}</td>";
        echo "<td>{$estado}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<h3>🎯 RESULTADO:</h3>";
    echo "<p>Si todos muestran ✅ OK, la configuración está correcta.</p>";
});

// Ruta visual para ver el mapeo actual de la base de datos
Route::get('/debug-mapeo-bd', function () {
    $controller = new \App\Http\Controllers\PdfController();
    $mapeo = $controller->debugTiposComprobante();
    $datos = $mapeo->getData();
    
    echo "<h2>🗄️ MAPEO ACTUAL DE BASE DE DATOS</h2>";
    echo "<p><strong>Tabla:</strong> tipo_comprobantes</p>";
    echo "<p><strong>Primary Key:</strong> id_tipo_comprobante</p>";
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 20px 0;'>";
    echo "<tr style='background: #f0f0f0;'>";
    echo "<th>ID (BD)</th><th>Código SUNAT</th><th>Descripción (BD)</th><th>Template PDF</th><th>Título PDF</th><th>Estado</th>";
    echo "</tr>";
    
    foreach ($datos as $tipo) {
        $estado = ($tipo->template !== 'NO DEFINIDO') ? "✅ CONFIGURADO" : "❌ FALTA CONFIG";
        $colorFila = ($tipo->template !== 'NO DEFINIDO') ? "" : "style='background: #ffe6e6;'";
        
        echo "<tr {$colorFila}>";
        echo "<td><strong>{$tipo->id_bd}</strong></td>";
        echo "<td>{$tipo->codigo_sunat}</td>";
        echo "<td>{$tipo->descripcion_bd}</td>";
        echo "<td>{$tipo->template}</td>";
        echo "<td>{$tipo->titulo_pdf}</td>";
        echo "<td>{$estado}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<h3>🎯 ANÁLISIS:</h3>";
    echo "<p>• ✅ Verde = Tipo configurado correctamente en PdfController</p>";
    echo "<p>• ❌ Rojo = Falta configuración en PdfController</p>";
    echo "<p>• El sistema ahora usa los IDs exactos de la base de datos</p>";
});

// Ruta de debug para verificar tipos de comprobante
Route::get('/debug-comprobantes', function () {
    try {
        // Obtener todos los tipos de comprobante de la base de datos
        $tiposDB = DB::table('tipo_comprobantes')->get();
        
        echo "<h2>Tipos de Comprobante en Base de Datos:</h2>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Código SUNAT</th><th>Descripción</th></tr>";
        
        foreach ($tiposDB as $tipo) {
            echo "<tr>";
            echo "<td>{$tipo->id}</td>";
            echo "<td>{$tipo->codigo_sunat}</td>";
            echo "<td>{$tipo->descripcion}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Verificar la venta #26 específicamente
        echo "<h2>Venta #26 - Análisis:</h2>";
        $venta = \App\Models\Venta::with('tipoComprobante')->find(26);
        
        if ($venta) {
            echo "<p><strong>ID Venta:</strong> {$venta->id_venta}</p>";
            echo "<p><strong>ID Tipo Comprobante:</strong> {$venta->id_tipo_comprobante}</p>";
            echo "<p><strong>Tipo Comprobante Descripción:</strong> " . ($venta->tipoComprobante->descripcion ?? 'N/A') . "</p>";
            echo "<p><strong>Código SUNAT:</strong> " . ($venta->tipoComprobante->codigo_sunat ?? 'N/A') . "</p>";
        } else {
            echo "<p style='color: red;'>❌ Venta #26 no encontrada</p>";
        }
        
        return null;
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    }
});

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
