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
    ReportesController
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
            return redirect()->intended('/');
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
            return redirect('/');
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
    
    // Ruta raíz - Página de inicio
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', function() {
        return redirect('/');
    })->name('dashboard');
    
    /*
    |--------------------------------------------------------------------------
    | MÓDULO DE VENTAS
    | Permisos: Administrador(1), Gerente(2), Vendedor(3), Contador(5-solo lectura)
    |--------------------------------------------------------------------------
    */
    
    // Crear, editar ventas - Admin, Gerente y Vendedor (DEBE IR PRIMERO)
    Route::middleware(['role:1,2,3'])->group(function () {
        Route::get('ventas/create', [VentaController::class, 'create'])->name('ventas.create');
        Route::post('ventas', [VentaController::class, 'store'])->name('ventas.store');
        Route::get('ventas/{venta}/edit', [VentaController::class, 'edit'])->name('ventas.edit');
        Route::put('ventas/{venta}', [VentaController::class, 'update'])->name('ventas.update');
        
        // Ruta alternativa personalizada para nueva venta
        Route::get('/venta/create', [VentaController::class, 'create'])->name('venta.create');
    });
    
    // Eliminar ventas - SOLO ADMINISTRADOR
    Route::middleware(['admin'])->group(function () {
        Route::delete('ventas/{venta}', [VentaController::class, 'destroy'])->name('ventas.destroy');
    });
    
    // Ver ventas - Todos los roles con acceso a ventas
    Route::middleware(['role:1,2,3,5'])->group(function () {
        Route::get('ventas', [VentaController::class, 'index'])->name('ventas.index');
        Route::get('ventas/{venta}', [VentaController::class, 'show'])->name('ventas.show');
    });
    
    // Acciones especiales de ventas - Admin, Gerente y Vendedor
    Route::middleware(['role:1,2,3'])->prefix('ventas')->name('ventas.')->group(function () {
        Route::post('pago', [VentaController::class, 'registrarPago'])->name('pago');
        Route::get('{venta}/confirm-cancel', [VentaController::class, 'confirmCancel'])->name('confirm-cancel');
        Route::patch('{venta}/cancel', [VentaController::class, 'cancel'])->name('cancel');
        Route::get('{venta}/pdf', [VentaController::class, 'generarPDF'])->name('pdf');
        Route::post('{venta}/convertir', [VentaController::class, 'convertirCotizacion'])->name('convertir');
        Route::get('{venta}/convertir-factura', [VentaController::class, 'convertirAFactura'])->name('convertir-factura');
        Route::get('{venta}/convertir-boleta', [VentaController::class, 'convertirABoleta'])->name('convertir-boleta');
        Route::get('{venta}/xml', [VentaController::class, 'generarXML'])->name('xml');
        Route::get('{venta}/xml-download', [VentaController::class, 'descargarXML'])->name('xml-download');
        
        Route::post('crear-cotizacion-prueba', [VentaController::class, 'crearCotizacionPrueba'])->name('crear-cotizacion-prueba');
        Route::get('tipo-cambio', [VentaController::class, 'obtenerTipoCambioActual'])->name('tipo-cambio');
        Route::post('tipo-cambio/forzar', [VentaController::class, 'actualizarTipoCambioForzado'])->name('tipo-cambio-forzar');
    });
    
    /*
    |--------------------------------------------------------------------------
    | GENERACIÓN DE PDFs PARA TODOS LOS COMPROBANTES
    | Permisos: Todos los roles con acceso a ventas
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:1,2,3,5'])->prefix('pdf')->name('pdf.')->group(function () {
        Route::get('comprobante/{venta}/download', [PdfController::class, 'generatePdf'])->name('download');
        Route::get('comprobante/{venta}/view', [PdfController::class, 'viewPdf'])->name('view');
        Route::get('comprobante/{venta}/qr', [PdfController::class, 'generarQR'])->name('qr');
        Route::post('lote/generar', [PdfController::class, 'generarLotePdfs'])->name('lote.generar');
        Route::post('comprobante/{venta}/enviar-email', [PdfController::class, 'enviarPorEmail'])->name('enviar-email');
        Route::get('estadisticas', [PdfController::class, 'estadisticasPdf'])->name('estadisticas');
        Route::get('debug/tipos-comprobante', [PdfController::class, 'debugTiposComprobante'])->name('debug.tipos');
    });
    
    // Detalle de ventas - Ver y editar para Admin, Gerente y Vendedor
    Route::middleware(['role:1,2,3'])->group(function () {
        Route::get('detalle_ventas', [DetalleVentaController::class, 'index'])->name('detalle_ventas.index');
        Route::get('detalle_ventas/create', [DetalleVentaController::class, 'create'])->name('detalle_ventas.create');
        Route::post('detalle_ventas', [DetalleVentaController::class, 'store'])->name('detalle_ventas.store');
        Route::get('detalle_ventas/{detalle_venta}', [DetalleVentaController::class, 'show'])->name('detalle_ventas.show');
        Route::get('detalle_ventas/{detalle_venta}/edit', [DetalleVentaController::class, 'edit'])->name('detalle_ventas.edit');
        Route::put('detalle_ventas/{detalle_venta}', [DetalleVentaController::class, 'update'])->name('detalle_ventas.update');
    });
    
    // Eliminar detalle_ventas - SOLO ADMINISTRADOR
    Route::middleware(['admin'])->delete('detalle_ventas/{detalle_venta}', [DetalleVentaController::class, 'destroy'])->name('detalle_ventas.destroy');
    
    /*
    |--------------------------------------------------------------------------
    | MÓDULO DE COMPROBANTES
    | Permisos: Administrador(1), Gerente(2), Contador(5)
    |--------------------------------------------------------------------------
    */
    // Ver y gestionar comprobantes - Admin, Gerente, Contador
    Route::middleware(['role:1,2,5'])->prefix('comprobantes')->name('comprobantes.')->group(function () {
        // Comprobantes electrónicos
        Route::get('electronicos', [ComprobanteElectronicoController::class, 'index'])->name('electronicos.index');
        Route::get('electronicos/create', [ComprobanteElectronicoController::class, 'create'])->name('electronicos.create');
        Route::post('electronicos', [ComprobanteElectronicoController::class, 'store'])->name('electronicos.store');
        Route::get('electronicos/{electronico}', [ComprobanteElectronicoController::class, 'show'])->name('electronicos.show');
        Route::get('electronicos/{electronico}/edit', [ComprobanteElectronicoController::class, 'edit'])->name('electronicos.edit');
        Route::put('electronicos/{electronico}', [ComprobanteElectronicoController::class, 'update'])->name('electronicos.update');
        
        // Archivos de comprobantes
        Route::get('archivos', [ComprobanteArchivoController::class, 'index'])->name('archivos.index');
        Route::get('archivos/create', [ComprobanteArchivoController::class, 'create'])->name('archivos.create');
        Route::post('archivos', [ComprobanteArchivoController::class, 'store'])->name('archivos.store');
        Route::get('archivos/{archivo}', [ComprobanteArchivoController::class, 'show'])->name('archivos.show');
        Route::get('archivos/{archivo}/edit', [ComprobanteArchivoController::class, 'edit'])->name('archivos.edit');
        Route::put('archivos/{archivo}', [ComprobanteArchivoController::class, 'update'])->name('archivos.update');
    });
    
    // Eliminar comprobantes - SOLO ADMINISTRADOR
    Route::middleware(['admin'])->prefix('comprobantes')->name('comprobantes.')->group(function () {
        Route::delete('electronicos/{electronico}', [ComprobanteElectronicoController::class, 'destroy'])->name('electronicos.destroy');
        Route::delete('archivos/{archivo}', [ComprobanteArchivoController::class, 'destroy'])->name('archivos.destroy');
    });
    
    /*
    |--------------------------------------------------------------------------
    | MÓDULO DE INVENTARIO
    | Permisos: Administrador(1), Gerente(2), Vendedor(3), Almacenero(4)
    |--------------------------------------------------------------------------
    */
    // Gestión completa de productos - Admin, Gerente, Vendedor y Almacenero
    Route::middleware(['role:1,2,3,4'])->group(function () {
        // Productos
        Route::get('productos', [ProductoController::class, 'index'])->name('productos.index');
        Route::get('productos/create', [ProductoController::class, 'create'])->name('productos.create');
        Route::post('productos', [ProductoController::class, 'store'])->name('productos.store');
        Route::get('productos/{producto}', [ProductoController::class, 'show'])->name('productos.show');
        Route::get('productos/{producto}/edit', [ProductoController::class, 'edit'])->name('productos.edit');
        Route::put('productos/{producto}', [ProductoController::class, 'update'])->name('productos.update');
        
        // Marcas
        Route::get('marcas', [MarcaController::class, 'index'])->name('marcas.index');
        Route::get('marcas/create', [MarcaController::class, 'create'])->name('marcas.create');
        Route::post('marcas', [MarcaController::class, 'store'])->name('marcas.store');
        Route::get('marcas/{marca}', [MarcaController::class, 'show'])->name('marcas.show');
        Route::get('marcas/{marca}/edit', [MarcaController::class, 'edit'])->name('marcas.edit');
        Route::put('marcas/{marca}', [MarcaController::class, 'update'])->name('marcas.update');
        
        // Categorías
        Route::get('categorias', [CategoriaController::class, 'index'])->name('categorias.index');
        Route::get('categorias/create', [CategoriaController::class, 'create'])->name('categorias.create');
        Route::post('categorias', [CategoriaController::class, 'store'])->name('categorias.store');
        Route::get('categorias/{categoria}', [CategoriaController::class, 'show'])->name('categorias.show');
        Route::get('categorias/{categoria}/edit', [CategoriaController::class, 'edit'])->name('categorias.edit');
        Route::put('categorias/{categoria}', [CategoriaController::class, 'update'])->name('categorias.update');
    });
    
    // Eliminar productos, marcas y categorías - SOLO ADMINISTRADOR
    Route::middleware(['admin'])->group(function () {
        Route::delete('productos/{producto}', [ProductoController::class, 'destroy'])->name('productos.destroy');
        Route::delete('marcas/{marca}', [MarcaController::class, 'destroy'])->name('marcas.destroy');
        Route::delete('categorias/{categoria}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');
    });
    
    /*
    |--------------------------------------------------------------------------
    | MÓDULO DE CONTACTOS
    | Clientes: Administrador(1), Gerente(2), Vendedor(3)
    | Proveedores: Administrador(1), Gerente(2), Almacenero(4)
    |--------------------------------------------------------------------------
    */
    // Ver y gestionar clientes - Admin, Gerente, Vendedor
    Route::middleware(['role:1,2,3'])->group(function () {
        Route::get('clientes', [ClienteController::class, 'index'])->name('clientes.index');
        Route::get('clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
        Route::post('clientes', [ClienteController::class, 'store'])->name('clientes.store');
        Route::get('clientes/{cliente}', [ClienteController::class, 'show'])->name('clientes.show');
        Route::get('clientes/{cliente}/edit', [ClienteController::class, 'edit'])->name('clientes.edit');
        Route::put('clientes/{cliente}', [ClienteController::class, 'update'])->name('clientes.update');
    });
    
    // Ver y gestionar proveedores - Admin, Gerente, Almacenero
    Route::middleware(['role:1,2,4'])->group(function () {
        Route::get('proveedores', [ProveedorController::class, 'index'])->name('proveedores.index');
        Route::get('proveedores/create', [ProveedorController::class, 'create'])->name('proveedores.create');
        Route::post('proveedores', [ProveedorController::class, 'store'])->name('proveedores.store');
        Route::get('proveedores/{proveedor}', [ProveedorController::class, 'show'])->name('proveedores.show');
        Route::get('proveedores/{proveedor}/edit', [ProveedorController::class, 'edit'])->name('proveedores.edit');
        Route::put('proveedores/{proveedor}', [ProveedorController::class, 'update'])->name('proveedores.update');
    });
    
    // Eliminar clientes y proveedores - SOLO ADMINISTRADOR
    Route::middleware(['admin'])->group(function () {
        Route::delete('clientes/{cliente}', [ClienteController::class, 'destroy'])->name('clientes.destroy');
        Route::delete('proveedores/{proveedor}', [ProveedorController::class, 'destroy'])->name('proveedores.destroy');
    });
    
    /*
    |--------------------------------------------------------------------------
    | MÓDULO DE CONFIGURACIÓN
    | Permisos: Administrador(1), Gerente(2)
    |--------------------------------------------------------------------------
    */
    // Ver y gestionar monedas - Admin, Gerente
    Route::middleware(['role:1,2'])->group(function () {
        Route::get('monedas', [MonedaController::class, 'index'])->name('monedas.index');
        Route::get('monedas/create', [MonedaController::class, 'create'])->name('monedas.create');
        Route::post('monedas', [MonedaController::class, 'store'])->name('monedas.store');
        Route::get('monedas/{moneda}', [MonedaController::class, 'show'])->name('monedas.show');
        Route::get('monedas/{moneda}/edit', [MonedaController::class, 'edit'])->name('monedas.edit');
        Route::put('monedas/{moneda}', [MonedaController::class, 'update'])->name('monedas.update');
    });
    
    // Eliminar monedas - SOLO ADMINISTRADOR
    Route::middleware(['admin'])->delete('monedas/{moneda}', [MonedaController::class, 'destroy'])->name('monedas.destroy');
    
    /*
    |--------------------------------------------------------------------------
    | API ENDPOINTS (Para AJAX y funcionalidades dinámicas)
    | Permisos: Según el módulo
    |--------------------------------------------------------------------------
    */
    Route::prefix('api')->name('api.')->group(function () {
        // API de ventas - Admin, Gerente, Vendedor
        Route::middleware(['role:1,2,3'])->prefix('ventas')->name('ventas.')->group(function () {
            Route::post('guardar', [VentaController::class, 'guardarVenta'])->name('guardar');
        });
    });
    
    /*
    |--------------------------------------------------------------------------
    | ROL Y USUARIO (Solo Administradores)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['admin'])->group(function () {
        Route::resource('roles', RolController::class);
        Route::resource('usuarios', UsuarioController::class);
    });
    
    /*
    |--------------------------------------------------------------------------
    | REPORTES Y EXPORTACIONES
    | Permisos: Administrador(1), Gerente(2), Contador(5)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:1,2,5'])->prefix('reportes')->name('reportes.')->group(function () {
        // Reporte Anual
        Route::get('/anual', [ReportesController::class, 'anual'])->name('anual');
        Route::get('/anual/pdf', [ReportesController::class, 'exportarAnualPdf'])->name('anual.pdf');
        Route::get('/anual/excel', [ReportesController::class, 'exportarAnualExcel'])->name('anual.excel');
        
        // Reporte Diario
        Route::get('/diario', [ReportesController::class, 'diario'])->name('diario');
        Route::get('/diario/pdf', [ReportesController::class, 'exportarPdf'])->name('diario.pdf');
        Route::get('/diario/excel', [ReportesController::class, 'exportarExcel'])->name('diario.excel');

        // Reporte Semestral
        Route::get('/semestral', [ReportesController::class, 'semestral'])->name('semestral');
        Route::get('/semestral/pdf', [ReportesController::class, 'exportarSemestralPdf'])->name('semestral.pdf');
        Route::get('/semestral/excel', [ReportesController::class, 'exportarSemestralExcel'])->name('semestral.excel');
    });
    
    // Reporte Semanal fuera del grupo 'reportes'
    Route::middleware(['role:1,2,5'])->group(function () {
        Route::get('/semanal', [ReportesController::class, 'semanal'])->name('semanal');
        Route::get('/semanal/pdf', [ReportesController::class, 'exportarSemanalPdf'])->name('semanal.pdf');
        Route::get('/semanal/excel', [ReportesController::class, 'exportarSemanalExcel'])->name('semanal.excel');
    });
    
    // Ruta para compatibilidad /reportes/semanal
    Route::get('/reportes/semanal', function() {
        return redirect('/semanal');
    });
    
    // Reporte Mensual
    Route::middleware(['role:1,2,5'])->group(function () {
        Route::get('/mensual', [ReportesController::class, 'mensual'])->name('mensual');
        Route::get('/mensual/pdf', [ReportesController::class, 'exportarMensualPdf'])->name('mensual.pdf');
        Route::get('/mensual/excel', [ReportesController::class, 'exportarMensualExcel'])->name('mensual.excel');
    });

    // Reporte Trimestral
    Route::middleware(['role:1,2,5'])->group(function () {
        Route::get('/trimestral', [ReportesController::class, 'trimestral'])->name('trimestral');
        Route::get('/trimestral/pdf', [ReportesController::class, 'exportarTrimestralPdf'])->name('trimestral.pdf');
        Route::get('/trimestral/excel', [ReportesController::class, 'exportarTrimestralExcel'])->name('trimestral.excel');
    });
    
    /*
    |--------------------------------------------------------------------------
    | MÓDULO DE COMPRAS
    | Permisos: Administrador(1), Gerente(2), Almacenero(4)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:1,2,4'])->group(function () {
        // API para productos por proveedor
        Route::get('compras/productos-por-proveedor/{id_proveedor}', [\App\Http\Controllers\CompraController::class, 'productosPorProveedor'])->name('compras.productos-por-proveedor');
        
        // Compras - CRUD excepto DELETE
        Route::get('compras', [\App\Http\Controllers\CompraController::class, 'index'])->name('compras.index');
        Route::get('compras/create', [\App\Http\Controllers\CompraController::class, 'create'])->name('compras.create');
        Route::post('compras', [\App\Http\Controllers\CompraController::class, 'store'])->name('compras.store');
        Route::get('compras/{compra}', [\App\Http\Controllers\CompraController::class, 'show'])->name('compras.show');
        Route::get('compras/{compra}/edit', [\App\Http\Controllers\CompraController::class, 'edit'])->name('compras.edit');
        Route::put('compras/{compra}', [\App\Http\Controllers\CompraController::class, 'update'])->name('compras.update');
        
        // Detalle compras - CRUD excepto DELETE
        Route::get('detalle_compras', [\App\Http\Controllers\DetalleCompraController::class, 'index'])->name('detalle_compras.index');
        Route::get('detalle_compras/create', [\App\Http\Controllers\DetalleCompraController::class, 'create'])->name('detalle_compras.create');
        Route::post('detalle_compras', [\App\Http\Controllers\DetalleCompraController::class, 'store'])->name('detalle_compras.store');
        Route::get('detalle_compras/{detalle_compra}', [\App\Http\Controllers\DetalleCompraController::class, 'show'])->name('detalle_compras.show');
        Route::get('detalle_compras/{detalle_compra}/edit', [\App\Http\Controllers\DetalleCompraController::class, 'edit'])->name('detalle_compras.edit');
        Route::put('detalle_compras/{detalle_compra}', [\App\Http\Controllers\DetalleCompraController::class, 'update'])->name('detalle_compras.update');
    });
    
    // Eliminar compras y detalles - SOLO ADMINISTRADOR
    Route::middleware(['admin'])->group(function () {
        Route::delete('compras/{compra}', [\App\Http\Controllers\CompraController::class, 'destroy'])->name('compras.destroy');
        Route::delete('detalle_compras/{detalle_compra}', [\App\Http\Controllers\DetalleCompraController::class, 'destroy'])->name('detalle_compras.destroy');
    });
});
