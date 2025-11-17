<?php

require_once 'vendor/autoload.php';

// Cargar Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Venta;
use App\Models\TipoComprobante;
use App\Models\Cliente;

echo "=== RECTIFICACIÓN DE COTIZACIONES ===\n\n";

try {
    // 1. Buscar tipo cotización
    $tipoCotizacion = TipoComprobante::where('codigo_sunat', 'CT')->first();
    
    if (!$tipoCotizacion) {
        echo "❌ No se encontró el tipo 'Cotización'\n";
        exit;
    }
    
    echo "✅ Tipo Cotización encontrado: ID {$tipoCotizacion->id_tipo_comprobante}\n";
    
    // 2. Obtener todas las cotizaciones
    $cotizaciones = Venta::where('id_tipo_comprobante', $tipoCotizacion->id_tipo_comprobante)
        ->orderBy('id_venta')
        ->get();
        
    echo "📊 Total cotizaciones a rectificar: {$cotizaciones->count()}\n\n";
    
    // 3. Rectificar cada cotización
    echo "🔧 INICIANDO RECTIFICACIÓN:\n";
    
    foreach ($cotizaciones as $index => $cotizacion) {
        echo "\n--- Cotización ID: {$cotizacion->id_venta} ---\n";
        
        // Calcular número consecutivo
        $numeroConsecutivo = $index + 1;
        
        // Preparar datos corregidos
        $datosCorrección = [];
        
        // 1. Corregir serie_numero
        $serieNumeroCorrecta = 'COT-' . str_pad($numeroConsecutivo, 8, '0', STR_PAD_LEFT);
        if (empty($cotizacion->serie_numero) || $cotizacion->serie_numero !== $serieNumeroCorrecta) {
            $datosCorrección['serie_numero'] = $serieNumeroCorrecta;
            echo "   ✏️ serie_numero: '{$cotizacion->serie_numero}' → '{$serieNumeroCorrecta}'\n";
        }
        
        // 2. Corregir serie
        if ($cotizacion->serie !== 'COT') {
            $datosCorrección['serie'] = 'COT';
            echo "   ✏️ serie: '{$cotizacion->serie}' → 'COT'\n";
        }
        
        // 3. Corregir número
        $numeroCorreto = str_pad($numeroConsecutivo, 8, '0', STR_PAD_LEFT);
        if ($cotizacion->numero !== $numeroCorreto) {
            $datosCorrección['numero'] = $numeroCorreto;
            echo "   ✏️ numero: '{$cotizacion->numero}' → '{$numeroCorreto}'\n";
        }
        
        // 4. Verificar cliente problemático
        if ($cotizacion->id_cliente) {
            $cliente = Cliente::find($cotizacion->id_cliente);
            $nombreCliente = $cliente ? $cliente->nombre : 'NULL';
            if (!$cliente || $nombreCliente === 's' || strlen($nombreCliente) < 3) {
                echo "   ⚠️ Cliente problemático detectado: '{$nombreCliente}'\n";
                echo "   💡 Recomendación: Asignar cliente válido manualmente\n";
                
                // Buscar un cliente válido como alternativa
                $clienteValido = Cliente::where('nombre', '!=', 's')
                    ->whereRaw('LENGTH(nombre) > 3')
                    ->first();
                    
                if ($clienteValido) {
                    echo "   💡 Cliente sugerido: {$clienteValido->nombre} (ID: {$clienteValido->id_cliente})\n";
                }
            }
        }
        
        // 5. Aplicar correcciones
        if (!empty($datosCorrección)) {
            $cotizacion->update($datosCorrección);
            echo "   ✅ Cambios aplicados correctamente\n";
        } else {
            echo "   ✅ Sin cambios necesarios\n";
        }
    }
    
    // 4. Verificación final
    echo "\n\n🔍 VERIFICACIÓN FINAL:\n";
    
    $cotizacionesCorregidas = Venta::where('id_tipo_comprobante', $tipoCotizacion->id_tipo_comprobante)
        ->orderBy('id_venta')
        ->get();
        
    foreach ($cotizacionesCorregidas as $cotizacion) {
        $cliente = $cotizacion->cliente ? $cotizacion->cliente->nombre : 'Sin cliente';
        echo "   • ID {$cotizacion->id_venta}: {$cotizacion->serie_numero} - {$cliente} - S/ {$cotizacion->total}\n";
    }
    
    echo "\n✅ RECTIFICACIÓN COMPLETADA EXITOSAMENTE\n";
    echo "📋 Todas las cotizaciones ahora tienen:\n";
    echo "   • serie_numero con formato COT-00000001, COT-00000002, etc.\n";
    echo "   • Numeración consecutiva correcta\n";
    echo "   • Serie 'COT' estandarizada\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "📍 Línea: " . $e->getLine() . "\n";
    echo "📂 Archivo: " . $e->getFile() . "\n";
}

echo "\n=== PROCESO COMPLETADO ===\n";
?>