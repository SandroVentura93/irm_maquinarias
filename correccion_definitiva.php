<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Venta;
use Illuminate\Support\Facades\DB;

echo "=== CORRECCIÓN FINAL DE COTIZACIONES ===\n\n";
echo "Ahora que entendemos que serie_numero es un accessor, vamos a corregir serie y numero\n\n";

// Obtener cotizaciones
$cotizaciones = Venta::where('id_tipo_comprobante', 8)
    ->orderBy('id_venta')
    ->get();

echo "Total cotizaciones a corregir: {$cotizaciones->count()}\n\n";

foreach ($cotizaciones as $index => $cotizacion) {
    $numeroConsecutivo = $index + 1;
    $numeroFormateado = str_pad($numeroConsecutivo, 8, '0', STR_PAD_LEFT);
    
    echo "Cotización ID {$cotizacion->id_venta}:\n";
    echo "  Estado actual:\n";
    echo "    serie: '{$cotizacion->serie}'\n";
    echo "    numero: '{$cotizacion->numero}'\n";
    echo "    serie_numero (calculado): '{$cotizacion->serie_numero}'\n";
    
    // Definir valores correctos
    $serieCorrecta = 'COT';
    $numeroCorreto = $numeroFormateado;
    
    echo "  Valores correctos:\n";
    echo "    serie: '{$serieCorrecta}'\n"; 
    echo "    numero: '{$numeroCorreto}'\n";
    echo "    serie_numero (esperado): 'COT-{$numeroCorreto}'\n";
    
    // Aplicar corrección si es necesario
    $needsUpdate = false;
    $updateData = [];
    
    if ($cotizacion->serie !== $serieCorrecta) {
        $updateData['serie'] = $serieCorrecta;
        $needsUpdate = true;
    }
    
    if ($cotizacion->numero !== $numeroCorreto) {
        $updateData['numero'] = $numeroCorreto;
        $needsUpdate = true;
    }
    
    if ($needsUpdate) {
        DB::table('ventas')
            ->where('id_venta', $cotizacion->id_venta)
            ->update($updateData);
        echo "  ✅ CORREGIDO\n";
    } else {
        echo "  ✅ YA ESTABA CORRECTO\n";
    }
    
    echo "\n";
}

echo "🔍 VERIFICACIÓN FINAL:\n";

// Recargar y verificar
$cotizacionesCorregidas = Venta::where('id_tipo_comprobante', 8)
    ->with('cliente')
    ->orderBy('id_venta')
    ->get();

foreach ($cotizacionesCorregidas as $cotizacion) {
    $cliente = $cotizacion->cliente ? $cotizacion->cliente->nombre : 'Sin cliente';
    echo "✅ {$cotizacion->serie_numero} - {$cliente} - S/ {$cotizacion->total}\n";
}

echo "\n🎉 ¡COTIZACIONES RECTIFICADAS EXITOSAMENTE!\n";
echo "📋 El accessor serie_numero ahora genera automáticamente el formato COT-00000001, etc.\n";
?>