<?php

require_once 'vendor/autoload.php';

// Cargar Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TipoComprobante;
use App\Models\Venta;

echo "=== VERIFICACIÓN DE COTIZACIONES ===\n\n";

try {
    // 1. Verificar tipos de comprobante
    echo "1. TIPOS DE COMPROBANTE:\n";
    $tipos = TipoComprobante::all();
    
    foreach ($tipos as $tipo) {
        echo "   ID: {$tipo->id_tipo_comprobante} - Código: {$tipo->codigo_sunat} - {$tipo->descripcion}\n";
    }
    
    // 2. Buscar el tipo cotización
    echo "\n2. BUSCAR TIPO COTIZACIÓN:\n";
    $tipoCotizacion = TipoComprobante::where('codigo_sunat', 'CT')->first();
    
    if ($tipoCotizacion) {
        echo "   ✅ Encontrado: ID {$tipoCotizacion->id_tipo_comprobante} - {$tipoCotizacion->descripcion}\n";
        
        // 3. Buscar cotizaciones
        echo "\n3. COTIZACIONES EN SISTEMA:\n";
        $cotizaciones = Venta::where('id_tipo_comprobante', $tipoCotizacion->id_tipo_comprobante)
            ->with('cliente')
            ->orderBy('id_venta')
            ->get();
            
        echo "   Total encontradas: {$cotizaciones->count()}\n";
        
        if ($cotizaciones->count() > 0) {
            echo "\n   DETALLES:\n";
            foreach ($cotizaciones as $cotizacion) {
                $cliente = $cotizacion->cliente ? $cotizacion->cliente->nombre : 'Sin cliente';
                echo "   • ID: {$cotizacion->id_venta}\n";
                echo "     Serie: '{$cotizacion->serie}'\n";
                echo "     Número: '{$cotizacion->numero}'\n"; 
                echo "     Serie-Número: '{$cotizacion->serie_numero}'\n";
                echo "     Cliente: {$cliente}\n";
                echo "     Total: S/ {$cotizacion->total}\n";
                echo "     Fecha: {$cotizacion->fecha}\n\n";
                
                // Verificar problemas
                $problemas = [];
                if (empty($cotizacion->serie_numero)) {
                    $problemas[] = "Serie-número vacío";
                }
                if (empty($cotizacion->serie)) {
                    $problemas[] = "Serie vacía";
                }
                if (empty($cotizacion->numero)) {
                    $problemas[] = "Número vacío";
                }
                
                if (!empty($problemas)) {
                    echo "     ⚠️ PROBLEMAS: " . implode(', ', $problemas) . "\n";
                }
            }
            
            // 4. Verificar numeración
            echo "\n4. VERIFICACIÓN DE NUMERACIÓN:\n";
            $numerosIncorrectos = 0;
            
            foreach ($cotizaciones as $index => $cotizacion) {
                $numeroEsperado = $index + 1;
                
                // Extraer número de la serie-número
                if (preg_match('/COT-(\d+)/', $cotizacion->serie_numero, $matches)) {
                    $numeroReal = (int) $matches[1];
                } else {
                    $numeroReal = 0;
                    $numerosIncorrectos++;
                    echo "   ⚠️ ID {$cotizacion->id_venta}: Formato incorrecto '{$cotizacion->serie_numero}'\n";
                }
                
                if ($numeroReal > 0 && $numeroReal != $numeroEsperado) {
                    $numerosIncorrectos++;
                    echo "   ⚠️ ID {$cotizacion->id_venta}: Esperado COT-" . str_pad($numeroEsperado, 8, '0', STR_PAD_LEFT) . ", encontrado {$cotizacion->serie_numero}\n";
                }
            }
            
            if ($numerosIncorrectos == 0) {
                echo "   ✅ Todas las cotizaciones tienen numeración correcta\n";
            } else {
                echo "   ⚠️ {$numerosIncorrectos} cotizaciones con problemas de numeración\n";
            }
        }
        
    } else {
        echo "   ❌ No se encontró el tipo 'Cotización'\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== VERIFICACIÓN COMPLETADA ===\n";
?>