<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Venta;

echo "=== VERIFICACIÓN VENTA ID 37 ===\n\n";

$venta = Venta::with(['cliente', 'tipoComprobante'])->find(37);

if ($venta) {
    echo "✅ Venta ID 37 encontrada:\n";
    echo "   • ID Venta: {$venta->id_venta}\n";
    echo "   • ID Tipo Comprobante: {$venta->id_tipo_comprobante}\n";
    echo "   • Tipo: " . ($venta->tipoComprobante ? $venta->tipoComprobante->descripcion : 'N/A') . "\n";
    echo "   • Código SUNAT: " . ($venta->tipoComprobante ? $venta->tipoComprobante->codigo_sunat : 'N/A') . "\n";
    echo "   • Serie: {$venta->serie}\n";
    echo "   • Número: {$venta->numero}\n";
    echo "   • Serie-Número: {$venta->serie_numero}\n";
    echo "   • Estado XML: {$venta->xml_estado}\n";
    echo "   • Cliente: " . ($venta->cliente ? $venta->cliente->nombre : 'Sin cliente') . "\n";
    echo "   • Total: S/ {$venta->total}\n";
    
    // Verificar si cumple criterios de cotización
    echo "\n🔍 VERIFICACIÓN DE CRITERIOS:\n";
    
    $esCotizacion1 = ($venta->id_tipo_comprobante == 8);
    $esCotizacion2 = ($venta->tipoComprobante && stripos($venta->tipoComprobante->descripcion, 'cotiz') !== false);
    $esCotizacion3 = ($venta->tipoComprobante && stripos($venta->tipoComprobante->codigo_sunat, 'CT') !== false);
    $esCotizacion4 = (stripos($venta->serie, 'COT') !== false);
    
    echo "   • ID tipo == 8: " . ($esCotizacion1 ? "✅ SÍ" : "❌ NO") . "\n";
    echo "   • Descripción contiene 'cotiz': " . ($esCotizacion2 ? "✅ SÍ" : "❌ NO") . "\n";
    echo "   • Código SUNAT contiene 'CT': " . ($esCotizacion3 ? "✅ SÍ" : "❌ NO") . "\n";
    echo "   • Serie contiene 'COT': " . ($esCotizacion4 ? "✅ SÍ" : "❌ NO") . "\n";
    
    $esCotizacionFinal = ($esCotizacion1 || $esCotizacion2 || $esCotizacion3 || $esCotizacion4);
    echo "   • ES COTIZACIÓN: " . ($esCotizacionFinal ? "✅ SÍ" : "❌ NO") . "\n";
    
    // Verificar si puede ser convertida
    echo "\n🔄 VERIFICACIÓN DE CONVERSIÓN:\n";
    if ($esCotizacionFinal && $venta->xml_estado === 'PENDIENTE') {
        echo "   ✅ PUEDE SER CONVERTIDA (es cotización y está pendiente)\n";
    } elseif (!$esCotizacionFinal) {
        echo "   ❌ NO PUEDE SER CONVERTIDA: No es cotización\n";
    } elseif ($venta->xml_estado !== 'PENDIENTE') {
        echo "   ❌ NO PUEDE SER CONVERTIDA: Estado no es PENDIENTE (actual: {$venta->xml_estado})\n";
    }
    
} else {
    echo "❌ Venta ID 37 no encontrada\n";
}

echo "\n=== FIN VERIFICACIÓN ===\n";
?>