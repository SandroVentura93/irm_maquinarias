<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Venta;

echo "=== VERIFICACIÓN FINAL DE COTIZACIONES ===\n\n";

$cotizaciones = Venta::where('id_tipo_comprobante', 8)
    ->with('cliente')
    ->orderBy('id_venta')
    ->get();

echo "Total cotizaciones: {$cotizaciones->count()}\n\n";

foreach ($cotizaciones as $index => $cotizacion) {
    $numeroEsperado = $index + 1;
    $cliente = $cotizacion->cliente ? $cotizacion->cliente->nombre : 'Sin cliente';
    
    echo "Cotización #{$numeroEsperado}:\n";
    echo "  ID Venta: {$cotizacion->id_venta}\n";
    echo "  Serie: '{$cotizacion->serie}'\n";
    echo "  Número: '{$cotizacion->numero}'\n";
    echo "  Serie-Número: '{$cotizacion->serie_numero}'\n";
    echo "  Cliente: {$cliente}\n";
    echo "  Total: S/ {$cotizacion->total}\n";
    
    // Verificar formato correcto
    $formatoCorrecto = 'COT-' . str_pad($numeroEsperado, 8, '0', STR_PAD_LEFT);
    if ($cotizacion->serie_numero === $formatoCorrecto) {
        echo "  ✅ FORMATO CORRECTO\n";
    } else {
        echo "  ❌ Esperado: {$formatoCorrecto}, Actual: '{$cotizacion->serie_numero}'\n";
    }
    
    echo "\n";
}

echo "=== FIN VERIFICACIÓN ===\n";
?>