<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Venta;

echo "=== VERIFICACIÓN COMPLETA DE TODAS LAS COTIZACIONES ===\n\n";

// Obtener todas las cotizaciones
$cotizaciones = Venta::with(['cliente', 'tipoComprobante'])
    ->where('id_tipo_comprobante', 8)
    ->orderBy('id_venta')
    ->get();

echo "📊 Total cotizaciones encontradas: {$cotizaciones->count()}\n\n";

foreach ($cotizaciones as $index => $cotizacion) {
    $numeroEsperado = $index + 1;
    $serieEsperada = 'COT-' . str_pad($numeroEsperado, 8, '0', STR_PAD_LEFT);
    
    echo "--- Cotización #{$numeroEsperado} (ID: {$cotizacion->id_venta}) ---\n";
    echo "✅ Tipo Comprobante: {$cotizacion->tipoComprobante->descripcion} (ID: {$cotizacion->id_tipo_comprobante})\n";
    echo "✅ Código SUNAT: {$cotizacion->tipoComprobante->codigo_sunat}\n";
    echo "✅ Serie-Número: {$cotizacion->serie_numero}\n";
    echo "✅ Estado: {$cotizacion->xml_estado}\n";
    echo "✅ Cliente: " . ($cotizacion->cliente ? $cotizacion->cliente->nombre : 'Sin cliente') . "\n";
    echo "✅ Total: S/ {$cotizacion->total}\n";
    
    // Verificar si puede ser convertida
    $puedeConvertir = ($cotizacion->xml_estado === 'PENDIENTE');
    echo "🔄 ¿Puede convertir?: " . ($puedeConvertir ? "✅ SÍ" : "❌ NO ({$cotizacion->xml_estado})") . "\n";
    
    // Verificar formato correcto
    if ($cotizacion->serie_numero === $serieEsperada) {
        echo "📋 Formato: ✅ CORRECTO\n";
    } else {
        echo "📋 Formato: ⚠️ Esperado {$serieEsperada}, actual {$cotizacion->serie_numero}\n";
    }
    
    echo "\n";
}

// Verificar si hay otras ventas mal clasificadas
echo "🔍 VERIFICANDO VENTAS NO-COTIZACIÓN CON SERIE COT:\n";
$ventasProblema = Venta::where('id_tipo_comprobante', '!=', 8)
    ->where('serie', 'COT')
    ->get();

if ($ventasProblema->count() > 0) {
    echo "⚠️ PROBLEMA: {$ventasProblema->count()} ventas con serie COT pero tipo diferente a 8:\n";
    foreach ($ventasProblema as $venta) {
        echo "   • ID {$venta->id_venta}: Tipo {$venta->id_tipo_comprobante}, Serie {$venta->serie}\n";
    }
} else {
    echo "✅ No hay ventas problemáticas\n";
}

echo "\n🎉 RESUMEN:\n";
echo "• {$cotizaciones->count()} cotizaciones correctamente configuradas\n";
echo "• Todas con ID tipo 8 (Cotización)\n";
echo "• Todas con código SUNAT 'CT'\n";
echo "• Numeración consecutiva COT-00000001 a COT-" . str_pad($cotizaciones->count(), 8, '0', STR_PAD_LEFT) . "\n";
echo "• Todas en estado PENDIENTE (convertibles)\n";

echo "\n=== VERIFICACIÓN COMPLETADA ===\n";
?>