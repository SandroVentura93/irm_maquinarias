<?php

/**
 * Script para DEPURAR por qué las cotizaciones siguen descontando stock
 * Fecha: 25 de Noviembre de 2025
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Producto;
use App\Models\Venta;
use App\Models\TipoComprobante;
use App\Models\DetalleVenta;

echo "\n╔═══════════════════════════════════════════════════════════╗\n";
echo "║  DEPURACIÓN: ¿Por qué la cotización descuenta stock?    ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n\n";

// 1. Verificar tipo de comprobante
echo "1️⃣  Verificando tipo de comprobante Cotización...\n";
$cotizacion = TipoComprobante::where('codigo_sunat', 'CT')->first();

if (!$cotizacion) {
    echo "   ❌ ERROR: No se encontró el tipo de comprobante con código 'CT'\n";
    exit(1);
}

echo "   ✅ Tipo de comprobante:\n";
echo "      ID: {$cotizacion->id_tipo_comprobante}\n";
echo "      Código SUNAT: '{$cotizacion->codigo_sunat}'\n";
echo "      Descripción: {$cotizacion->descripcion}\n";

// 2. Buscar la última cotización creada
echo "\n2️⃣  Buscando la última cotización creada...\n";
$ultimaCotizacion = Venta::where('id_tipo_comprobante', $cotizacion->id_tipo_comprobante)
    ->orderBy('created_at', 'desc')
    ->first();

if (!$ultimaCotizacion) {
    echo "   ⚠️  No se encontraron cotizaciones en el sistema\n";
    echo "   Crea una cotización y vuelve a ejecutar este script\n";
    exit(0);
}

echo "   ✅ Última cotización:\n";
echo "      ID: {$ultimaCotizacion->id_venta}\n";
echo "      Serie-Número: {$ultimaCotizacion->serie}-{$ultimaCotizacion->numero}\n";
echo "      Fecha: {$ultimaCotizacion->created_at}\n";
echo "      Estado: {$ultimaCotizacion->xml_estado}\n";

// 3. Verificar los productos de la cotización
echo "\n3️⃣  Productos en la cotización:\n";
$detalles = DetalleVenta::where('id_venta', $ultimaCotizacion->id_venta)->get();

if ($detalles->count() == 0) {
    echo "   ⚠️  No hay productos en esta cotización\n";
    exit(0);
}

foreach ($detalles as $detalle) {
    $producto = Producto::find($detalle->id_producto);
    if ($producto) {
        echo "\n   📦 Producto: {$producto->descripcion}\n";
        echo "      ID: {$producto->id_producto}\n";
        echo "      Cantidad en cotización: {$detalle->cantidad}\n";
        echo "      Stock actual: {$producto->stock_actual}\n";
    }
}

// 4. Revisar los logs recientes
echo "\n4️⃣  Revisando logs del sistema...\n";
$logFile = storage_path('logs/laravel.log');

if (file_exists($logFile)) {
    $lines = file($logFile);
    $recentLogs = array_slice($lines, -100); // Últimas 100 líneas
    
    $cotizacionLogs = [];
    foreach ($recentLogs as $line) {
        if (strpos($line, '[CONTROL STOCK]') !== false || 
            strpos($line, '[DEBUG STOCK]') !== false ||
            strpos($line, 'COTIZACIÓN') !== false ||
            strpos($line, 'codigo_sunat') !== false) {
            $cotizacionLogs[] = $line;
        }
    }
    
    if (count($cotizacionLogs) > 0) {
        echo "   📋 Logs relacionados con stock (últimos 20):\n";
        $recent = array_slice($cotizacionLogs, -20);
        foreach ($recent as $log) {
            echo "      " . trim($log) . "\n";
        }
    } else {
        echo "   ⚠️  No se encontraron logs recientes sobre control de stock\n";
    }
} else {
    echo "   ⚠️  No se pudo acceder al archivo de logs\n";
}

// 5. Simular la validación
echo "\n5️⃣  Simulando la validación del código...\n";

$tipoComprobanteDB = TipoComprobante::where('id_tipo_comprobante', $ultimaCotizacion->id_tipo_comprobante)->first();
$descuentaStock = false;

if ($tipoComprobanteDB) {
    $codigo = strtoupper($tipoComprobanteDB->codigo_sunat ?? '');
    echo "   - Código obtenido de BD: '{$codigo}'\n";
    echo "   - Comparando con 'CT': " . ($codigo === 'CT' ? "✅ IGUAL" : "❌ DIFERENTE") . "\n";
    
    if ($codigo === 'CT') {
        $descuentaStock = false;
        echo "   ✅ Resultado: NO debería descontar stock\n";
    } elseif (in_array($codigo, ['01', '03', '12'])) {
        $descuentaStock = true;
        echo "   ❌ Resultado: SÍ descontaría stock (ERROR)\n";
    } else {
        echo "   ⚠️  Resultado: Código no reconocido\n";
    }
} else {
    echo "   ❌ ERROR: No se pudo obtener el tipo de comprobante\n";
}

// 6. DIAGNÓSTICO
echo "\n╔═══════════════════════════════════════════════════════════╗\n";
echo "║                      DIAGNÓSTICO                          ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n\n";

if (!$descuentaStock) {
    echo "✅ La validación del código es CORRECTA\n";
    echo "   El código 'CT' está siendo identificado correctamente\n\n";
    
    echo "🔍 POSIBLES CAUSAS del problema:\n\n";
    
    echo "A) Existe otro código que ejecuta el descuento\n";
    echo "   - Revisa si hay eventos, observers o listeners en el modelo Venta\n";
    echo "   - Busca: app/Models/Venta.php\n";
    echo "   - Busca: app/Observers/VentaObserver.php\n\n";
    
    echo "B) El frontend está enviando un tipo de comprobante incorrecto\n";
    echo "   - Verifica en el formulario que se envíe el ID correcto: {$cotizacion->id_tipo_comprobante}\n";
    echo "   - O el código: 'CT' o 'Cotización'\n\n";
    
    echo "C) Hay un trigger o procedimiento en la base de datos\n";
    echo "   - Revisa si la BD tiene triggers automáticos\n\n";
    
    echo "D) Hay JavaScript que hace una llamada adicional\n";
    echo "   - Revisa el código JavaScript del formulario de ventas\n\n";
    
} else {
    echo "❌ ERROR EN LA VALIDACIÓN\n";
    echo "   El código está siendo validado INCORRECTAMENTE\n";
    echo "   La cotización está siendo tratada como comprobante de venta\n\n";
}

echo "╔═══════════════════════════════════════════════════════════╗\n";
echo "║                   SIGUIENTE PASO                          ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n\n";

echo "1. Revisa el modelo Venta:\n";
echo "   php artisan tinker\n";
echo "   >>> \$v = App\\Models\\Venta::find({$ultimaCotizacion->id_venta})\n";
echo "   >>> \$v->tipoComprobante\n\n";

echo "2. Crea una nueva cotización y observa los logs:\n";
echo "   tail -f storage/logs/laravel.log | grep 'CONTROL STOCK'\n\n";

echo "3. Ejecuta este script inmediatamente después de crear la cotización\n\n";
