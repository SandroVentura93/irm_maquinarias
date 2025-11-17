<?php

require_once 'vendor/autoload.php';

// Cargar Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Venta;
use App\Models\TipoComprobante;

echo "=== RECTIFICACIÓN DE COTIZACIONES SEGÚN SEEDER ===\n\n";

try {
    // Mapeo exacto según el seeder
    $tiposSeederOrder = [
        1 => ['codigo_sunat' => '01', 'descripcion' => 'Factura'],
        2 => ['codigo_sunat' => '03', 'descripcion' => 'Boleta de Venta'],
        3 => ['codigo_sunat' => '07', 'descripcion' => 'Nota de Crédito'],
        4 => ['codigo_sunat' => '08', 'descripcion' => 'Nota de Débito'],
        5 => ['codigo_sunat' => '09', 'descripcion' => 'Guía de Remisión'],
        6 => ['codigo_sunat' => '12', 'descripcion' => 'Ticket de Máquina Registradora'],
        7 => ['codigo_sunat' => '14', 'descripcion' => 'Recibo por Honorarios'],
        8 => ['codigo_sunat' => 'CT', 'descripcion' => 'Cotización'],
    ];

    echo "1️⃣ MAPEO SEGÚN SEEDER:\n";
    foreach ($tiposSeederOrder as $id => $tipo) {
        echo "   ID {$id}: {$tipo['descripcion']} (Código: {$tipo['codigo_sunat']})\n";
    }

    // Confirmar que Cotización está en ID 8
    echo "\n2️⃣ CONFIRMACIÓN COTIZACIÓN = ID 8:\n";
    $tipoCotizacion = TipoComprobante::find(8);
    
    if ($tipoCotizacion && $tipoCotizacion->codigo_sunat === 'CT') {
        echo "   ✅ CORRECTO: ID 8 = {$tipoCotizacion->descripcion} (Código: {$tipoCotizacion->codigo_sunat})\n";
    } else {
        echo "   ❌ ERROR: ID 8 no corresponde a Cotización\n";
        if ($tipoCotizacion) {
            echo "   Encontrado: {$tipoCotizacion->descripcion} (Código: {$tipoCotizacion->codigo_sunat})\n";
        }
        exit;
    }

    // Obtener cotizaciones actuales
    echo "\n3️⃣ COTIZACIONES ACTUALES:\n";
    $cotizaciones = Venta::where('id_tipo_comprobante', 8)
        ->orderBy('id_venta')
        ->get();
        
    echo "   Total encontradas: {$cotizaciones->count()}\n\n";

    if ($cotizaciones->count() === 0) {
        echo "   ℹ️ No hay cotizaciones para rectificar\n";
        exit;
    }

    // Mostrar estado actual
    echo "   ESTADO ACTUAL:\n";
    foreach ($cotizaciones as $index => $cotizacion) {
        echo "   • Venta ID {$cotizacion->id_venta}:\n";
        echo "     serie: '{$cotizacion->serie}'\n";
        echo "     numero: '{$cotizacion->numero}'\n";
        echo "     serie_numero: '{$cotizacion->serie_numero}'\n\n";
    }

    // Aplicar correcciones
    echo "4️⃣ APLICANDO RECTIFICACIONES:\n\n";
    
    foreach ($cotizaciones as $index => $cotizacion) {
        $numeroConsecutivo = $index + 1;
        $numeroFormateado = str_pad($numeroConsecutivo, 8, '0', STR_PAD_LEFT);
        $serieNumeroCorrecta = 'COT-' . $numeroFormateado;
        
        echo "--- Cotización Venta ID {$cotizacion->id_venta} ---\n";
        
        // Preparar datos de corrección
        $datosCorrección = [];
        $cambios = [];
        
        // Corregir serie
        if ($cotizacion->serie !== 'COT') {
            $datosCorrección['serie'] = 'COT';
            $cambios[] = "serie: '{$cotizacion->serie}' → 'COT'";
        }
        
        // Corregir número
        if ($cotizacion->numero !== $numeroFormateado) {
            $datosCorrección['numero'] = $numeroFormateado;
            $cambios[] = "numero: '{$cotizacion->numero}' → '{$numeroFormateado}'";
        }
        
        // Corregir serie_numero
        if ($cotizacion->serie_numero !== $serieNumeroCorrecta) {
            $datosCorrección['serie_numero'] = $serieNumeroCorrecta;
            $cambios[] = "serie_numero: '{$cotizacion->serie_numero}' → '{$serieNumeroCorrecta}'";
        }
        
        // Aplicar cambios
        if (!empty($datosCorrección)) {
            echo "   Aplicando cambios:\n";
            foreach ($cambios as $cambio) {
                echo "   • {$cambio}\n";
            }
            
            $cotizacion->update($datosCorrección);
            echo "   ✅ Cambios aplicados correctamente\n";
        } else {
            echo "   ✅ Ya está correcto, sin cambios necesarios\n";
        }
        
        echo "\n";
    }

    // Verificación final
    echo "5️⃣ VERIFICACIÓN FINAL:\n";
    $cotizacionesCorregidas = Venta::where('id_tipo_comprobante', 8)
        ->orderBy('id_venta')
        ->get();
        
    echo "   ESTADO DESPUÉS DE CORRECCIÓN:\n";
    foreach ($cotizacionesCorregidas as $cotizacion) {
        $cliente = $cotizacion->cliente ? $cotizacion->cliente->nombre : 'Sin cliente';
        echo "   ✅ {$cotizacion->serie_numero} - {$cliente} - S/ {$cotizacion->total}\n";
    }

    echo "\n🎉 RECTIFICACIÓN COMPLETADA EXITOSAMENTE\n";
    echo "📋 Todas las cotizaciones ahora siguen el formato correcto según el seeder\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "📍 Línea: " . $e->getLine() . "\n";
}

echo "\n=== PROCESO TERMINADO ===\n";
?>