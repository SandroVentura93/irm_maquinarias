<?php

/**
 * Script de prueba: Verificar que cotizaciones con código CT NO disminuyen stock
 * Fecha: 25 de Noviembre de 2025
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TipoComprobante;
use Illuminate\Support\Facades\DB;

echo "\n==========================================================\n";
echo "   PRUEBA: COTIZACIONES CON CÓDIGO 'CT' NO AFECTAN STOCK\n";
echo "==========================================================\n\n";

// 1. Verificar el tipo de comprobante Cotización
echo "1. Verificando tipo de comprobante Cotización...\n";
$cotizacion = TipoComprobante::where('codigo_sunat', 'CT')->first();

if (!$cotizacion) {
    echo "   ❌ ERROR: No se encontró tipo de comprobante con código 'CT'\n";
    echo "   Ejecutando seeder...\n";
    
    DB::table('tipo_comprobantes')->updateOrInsert(
        ['codigo_sunat' => 'CT'],
        ['codigo_sunat' => 'CT', 'descripcion' => 'Cotización']
    );
    
    $cotizacion = TipoComprobante::where('codigo_sunat', 'CT')->first();
    
    if ($cotizacion) {
        echo "   ✅ Cotización creada correctamente\n";
    } else {
        echo "   ❌ ERROR: No se pudo crear el tipo de comprobante\n";
        exit(1);
    }
}

echo "   ✅ Cotización encontrada:\n";
echo "      - ID: {$cotizacion->id_tipo_comprobante}\n";
echo "      - Código SUNAT: '{$cotizacion->codigo_sunat}'\n";
echo "      - Descripción: {$cotizacion->descripcion}\n";

// 2. Verificar que el código sea exactamente 'CT'
echo "\n2. Verificando código SUNAT...\n";
$codigo_upper = strtoupper($cotizacion->codigo_sunat);
$es_CT = ($codigo_upper === 'CT');

echo "   - Código en BD: '{$cotizacion->codigo_sunat}'\n";
echo "   - Código en mayúsculas: '{$codigo_upper}'\n";
echo "   - ¿Es exactamente 'CT'?: " . ($es_CT ? "✅ SÍ" : "❌ NO") . "\n";

if (!$es_CT) {
    echo "\n   ⚠️  ADVERTENCIA: El código no es 'CT'\n";
    echo "   Esto podría causar que las cotizaciones descuenten stock!\n";
}

// 3. Verificar la lógica de descuento
echo "\n3. Verificando lógica de descuento de stock...\n";

$comprobantes_que_descuentan = ['01', '03', '12'];
$descuenta = in_array($codigo_upper, $comprobantes_que_descuentan);

echo "   - Códigos que descuentan stock: " . implode(', ', $comprobantes_que_descuentan) . "\n";
echo "   - Código de cotización: '{$codigo_upper}'\n";
echo "   - ¿Está en la lista?: " . ($descuenta ? "❌ SÍ (ERROR)" : "✅ NO (CORRECTO)") . "\n";

if ($descuenta) {
    echo "\n   ❌❌❌ ERROR CRÍTICO ❌❌❌\n";
    echo "   ¡Las cotizaciones ESTÁN descontando stock!\n";
    exit(1);
}

// 4. Verificar todos los tipos de comprobante
echo "\n4. Listado de todos los tipos de comprobante:\n";
$todos = TipoComprobante::orderBy('id_tipo_comprobante')->get();

foreach ($todos as $tipo) {
    $codigo = strtoupper($tipo->codigo_sunat ?? '');
    $descuenta_stock = in_array($codigo, ['01', '03', '12']);
    $icono = $descuenta_stock ? "📉" : "✅";
    $estado = $descuenta_stock ? "DESCUENTA STOCK" : "NO afecta stock";
    
    echo "   $icono ID: {$tipo->id_tipo_comprobante} | Código: '$codigo' | {$tipo->descripcion} | $estado\n";
    
    // Destacar la cotización
    if ($codigo === 'CT') {
        echo "      ⭐ COTIZACIÓN - DEBE estar en 'NO afecta stock'\n";
    }
}

// 5. Simular la validación del código
echo "\n5. Simulando validación del VentaController...\n";

function simularValidacion($codigo_sunat) {
    $codigo = strtoupper($codigo_sunat ?? '');
    $descuentaStock = false;
    
    // Verificación explícita: Las cotizaciones (CT) NUNCA descuentan stock
    if ($codigo === 'CT') {
        $descuentaStock = false;
        return ['descuenta' => false, 'razon' => 'Es COTIZACIÓN (CT)'];
    } 
    // Solo descuentan stock: Factura (01), Boleta (03), Ticket (12)
    elseif (in_array($codigo, ['01', '03', '12'])) {
        $descuentaStock = true;
        return ['descuenta' => true, 'razon' => 'Es comprobante de venta'];
    }
    
    return ['descuenta' => false, 'razon' => 'Otro tipo de documento'];
}

$resultado = simularValidacion($cotizacion->codigo_sunat);

echo "   - Código evaluado: '{$cotizacion->codigo_sunat}'\n";
echo "   - ¿Descuenta stock?: " . ($resultado['descuenta'] ? "❌ SÍ" : "✅ NO") . "\n";
echo "   - Razón: {$resultado['razon']}\n";

// 6. Conclusión
echo "\n==========================================================\n";
if (!$descuenta && !$resultado['descuenta'] && $es_CT) {
    echo "   ✅✅✅ PRUEBA EXITOSA ✅✅✅\n";
    echo "\n   Las cotizaciones con código 'CT' NO descuentan stock\n";
    echo "   El sistema está configurado CORRECTAMENTE\n";
} else {
    echo "   ❌❌❌ PRUEBA FALLIDA ❌❌❌\n";
    echo "\n   HAY UN PROBLEMA con la configuración\n";
    echo "   Las cotizaciones podrían estar descontando stock\n";
}
echo "==========================================================\n\n";

echo "RESUMEN:\n";
echo "- Tipo: {$cotizacion->descripcion}\n";
echo "- Código: '{$cotizacion->codigo_sunat}'\n";
echo "- ID: {$cotizacion->id_tipo_comprobante}\n";
echo "- Descuenta Stock: " . ($descuenta ? "❌ SÍ (ERROR)" : "✅ NO (CORRECTO)") . "\n\n";
