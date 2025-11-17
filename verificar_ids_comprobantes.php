<?php

require_once 'vendor/autoload.php';

// Cargar Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TipoComprobante;
use App\Models\Venta;

echo "=== VERIFICACIÓN DE IDS DE COMPROBANTES ===\n\n";

try {
    // 1. Mostrar el mapeo actual según el seeder
    echo "1. MAPEO SEGÚN SEEDER (TipoComprobantesSeeder.php):\n";
    $tiposSeeder = [
        ['codigo_sunat' => '01', 'descripcion' => 'Factura'],
        ['codigo_sunat' => '03', 'descripcion' => 'Boleta de Venta'],
        ['codigo_sunat' => '07', 'descripcion' => 'Nota de Crédito'],
        ['codigo_sunat' => '08', 'descripcion' => 'Nota de Débito'],
        ['codigo_sunat' => '09', 'descripcion' => 'Guía de Remisión'],
        ['codigo_sunat' => '12', 'descripcion' => 'Ticket de Máquina Registradora'],
        ['codigo_sunat' => '14', 'descripcion' => 'Recibo por Honorarios'],
        ['codigo_sunat' => 'CT', 'descripcion' => 'Cotización'],
    ];

    foreach ($tiposSeeder as $index => $tipo) {
        $idEsperado = $index + 1;
        echo "   ID {$idEsperado} -> Código: {$tipo['codigo_sunat']} -> {$tipo['descripcion']}\n";
    }

    // 2. Verificar estado actual en base de datos
    echo "\n2. ESTADO ACTUAL EN BASE DE DATOS:\n";
    $tiposDB = TipoComprobante::orderBy('id_tipo_comprobante')->get();
    
    foreach ($tiposDB as $tipo) {
        echo "   ID {$tipo->id_tipo_comprobante} -> Código: {$tipo->codigo_sunat} -> {$tipo->descripcion}\n";
    }

    // 3. Verificar correspondencias
    echo "\n3. VERIFICACIÓN DE CORRESPONDENCIAS:\n";
    $problemasEncontrados = [];
    
    foreach ($tiposSeeder as $index => $tipoEsperado) {
        $idEsperado = $index + 1;
        $tipoDB = TipoComprobante::find($idEsperado);
        
        if (!$tipoDB) {
            $problemasEncontrados[] = "ID {$idEsperado}: No existe en BD (esperado: {$tipoEsperado['descripcion']})";
        } elseif ($tipoDB->codigo_sunat !== $tipoEsperado['codigo_sunat']) {
            $problemasEncontrados[] = "ID {$idEsperado}: Código incorrecto (esperado: {$tipoEsperado['codigo_sunat']}, encontrado: {$tipoDB->codigo_sunat})";
        } elseif ($tipoDB->descripcion !== $tipoEsperado['descripcion']) {
            $problemasEncontrados[] = "ID {$idEsperado}: Descripción incorrecta (esperado: {$tipoEsperado['descripcion']}, encontrado: {$tipoDB->descripcion})";
        } else {
            echo "   ✅ ID {$idEsperado}: {$tipoDB->descripcion} (Código: {$tipoDB->codigo_sunat}) - CORRECTO\n";
        }
    }

    if (!empty($problemasEncontrados)) {
        echo "\n⚠️ PROBLEMAS ENCONTRADOS:\n";
        foreach ($problemasEncontrados as $problema) {
            echo "   • {$problema}\n";
        }
    }

    // 4. Verificar ventas por tipo de comprobante
    echo "\n4. ESTADÍSTICAS DE VENTAS POR TIPO:\n";
    foreach ($tiposDB as $tipo) {
        $countVentas = Venta::where('id_tipo_comprobante', $tipo->id_tipo_comprobante)->count();
        echo "   {$tipo->descripcion} (ID {$tipo->id_tipo_comprobante}): {$countVentas} ventas\n";
        
        if ($countVentas > 0) {
            // Mostrar ejemplos
            $ejemplos = Venta::where('id_tipo_comprobante', $tipo->id_tipo_comprobante)
                ->select('id_venta', 'serie', 'numero', 'serie_numero', 'total')
                ->limit(3)
                ->get();
                
            foreach ($ejemplos as $venta) {
                echo "     → ID {$venta->id_venta}: {$venta->serie_numero} (S/ {$venta->total})\n";
            }
        }
    }

    // 5. Verificación específica de cotizaciones
    echo "\n5. ANÁLISIS DETALLADO DE COTIZACIONES (ID 8):\n";
    $tipoCotizacion = TipoComprobante::find(8);
    
    if ($tipoCotizacion) {
        echo "   ✅ Tipo encontrado: {$tipoCotizacion->descripcion} (Código: {$tipoCotizacion->codigo_sunat})\n";
        
        $cotizaciones = Venta::where('id_tipo_comprobante', 8)
            ->orderBy('id_venta')
            ->get();
            
        echo "   📊 Total cotizaciones: {$cotizaciones->count()}\n";
        
        if ($cotizaciones->count() > 0) {
            echo "   📋 Detalles:\n";
            foreach ($cotizaciones as $index => $cotizacion) {
                $numeroEsperado = $index + 1;
                $serieEsperada = 'COT-' . str_pad($numeroEsperado, 8, '0', STR_PAD_LEFT);
                
                echo "     • ID {$cotizacion->id_venta}:\n";
                echo "       Serie: '{$cotizacion->serie}' (esperada: 'COT')\n";
                echo "       Número: '{$cotizacion->numero}' (esperado: " . str_pad($numeroEsperado, 8, '0', STR_PAD_LEFT) . ")\n";
                echo "       Serie-Número: '{$cotizacion->serie_numero}' (esperado: '{$serieEsperada}')\n";
                
                // Verificar problemas
                $problemasDetectados = [];
                if (empty($cotizacion->serie_numero)) {
                    $problemasDetectados[] = "serie_numero vacío";
                }
                if ($cotizacion->serie !== 'COT') {
                    $problemasDetectados[] = "serie incorrecta";
                }
                if ($cotizacion->serie_numero !== $serieEsperada) {
                    $problemasDetectados[] = "formato serie_numero incorrecto";
                }
                
                if (!empty($problemasDetectados)) {
                    echo "       ❌ PROBLEMAS: " . implode(', ', $problemasDetectados) . "\n";
                } else {
                    echo "       ✅ CORRECTO\n";
                }
                echo "\n";
            }
        }
    } else {
        echo "   ❌ Tipo Cotización no encontrado en ID 8\n";
    }

    echo "\n=== RESUMEN ===\n";
    echo "• El seeder define 8 tipos de comprobante con IDs del 1 al 8\n";
    echo "• Las cotizaciones deben estar en ID 8 con código 'CT'\n";
    echo "• Cada venta debe tener serie_numero con formato apropiado\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== VERIFICACIÓN COMPLETADA ===\n";
?>