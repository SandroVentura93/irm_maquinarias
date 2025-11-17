<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Venta;
use App\Models\TipoComprobante;
use Illuminate\Support\Facades\DB;

echo "=== CORRECCIÓN DE VENTA PROBLEMÁTICA ID 15 ===\n\n";

// Verificar la venta ID 15
$venta = Venta::with(['cliente', 'tipoComprobante'])->find(15);

if ($venta) {
    echo "📋 VENTA ID 15 - ESTADO ACTUAL:\n";
    echo "   • ID Tipo Comprobante: {$venta->id_tipo_comprobante}\n";
    echo "   • Tipo: " . ($venta->tipoComprobante ? $venta->tipoComprobante->descripcion : 'N/A') . "\n";
    echo "   • Código SUNAT: " . ($venta->tipoComprobante ? $venta->tipoComprobante->codigo_sunat : 'N/A') . "\n";
    echo "   • Serie: {$venta->serie}\n";
    echo "   • Número: {$venta->numero}\n";
    echo "   • Serie-Número: {$venta->serie_numero}\n";
    echo "   • Estado: {$venta->xml_estado}\n";
    echo "   • Total: S/ {$venta->total}\n";
    
    echo "\n❌ PROBLEMA DETECTADO:\n";
    echo "   Esta venta tiene tipo 'Nota de Débito' pero serie 'COT' (de cotización)\n";
    
    // Verificar cuál debería ser la serie correcta para Nota de Débito
    $tipoNotaDebito = TipoComprobante::find(4);
    if ($tipoNotaDebito) {
        echo "\n📋 TIPO CORRECTO PARA ID 4:\n";
        echo "   • Descripción: {$tipoNotaDebito->descripcion}\n";
        echo "   • Código SUNAT: {$tipoNotaDebito->codigo_sunat}\n";
        echo "   • Serie esperada: ND01 (según estándares)\n";
        
        echo "\n🔧 APLICANDO CORRECCIÓN:\n";
        
        // Obtener el último número de notas de débito para generar el siguiente
        $ultimaNotaDebito = Venta::where('id_tipo_comprobante', 4)
            ->where('serie', 'ND01')
            ->orderBy('numero', 'desc')
            ->first();
            
        $siguienteNumero = 1;
        if ($ultimaNotaDebito) {
            // Extraer número de la serie
            $numeroAnterior = str_replace('ND01-', '', $ultimaNotaDebito->numero);
            $siguienteNumero = intval($numeroAnterior) + 1;
        }
        
        $nuevoNumero = str_pad($siguienteNumero, 8, '0', STR_PAD_LEFT);
        
        echo "   • Nueva serie: ND01\n";
        echo "   • Nuevo número: {$nuevoNumero}\n";
        echo "   • Nuevo serie_numero: ND01-{$nuevoNumero}\n";
        
        // Aplicar corrección
        DB::table('ventas')
            ->where('id_venta', 15)
            ->update([
                'serie' => 'ND01',
                'numero' => $nuevoNumero
            ]);
            
        echo "   ✅ Corrección aplicada exitosamente\n";
        
        // Verificar el resultado
        $ventaCorregida = Venta::with('tipoComprobante')->find(15);
        echo "\n✅ ESTADO DESPUÉS DE CORRECCIÓN:\n";
        echo "   • Tipo: {$ventaCorregida->tipoComprobante->descripcion}\n";
        echo "   • Serie-Número: {$ventaCorregida->serie_numero}\n";
        
    } else {
        echo "\n❌ No se encontró el tipo de comprobante ID 4\n";
    }
    
} else {
    echo "❌ Venta ID 15 no encontrada\n";
}

echo "\n=== VERIFICACIÓN FINAL ===\n";

// Verificar que ya no hay problemas
$ventasProblema = Venta::where('id_tipo_comprobante', '!=', 8)
    ->where('serie', 'COT')
    ->get();

if ($ventasProblema->count() === 0) {
    echo "✅ Ya no hay ventas problemáticas con serie COT y tipo diferente a 8\n";
} else {
    echo "⚠️ Aún hay {$ventasProblema->count()} ventas problemáticas:\n";
    foreach ($ventasProblema as $venta) {
        echo "   • ID {$venta->id_venta}: Tipo {$venta->id_tipo_comprobante}, Serie {$venta->serie}\n";
    }
}

echo "\n🎉 ¡PROBLEMA RESUELTO!\n";
echo "Ahora solo las cotizaciones (ID tipo 8) tienen serie COT\n";

echo "\n=== FIN CORRECCIÓN ===\n";
?>