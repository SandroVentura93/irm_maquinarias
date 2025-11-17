<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Venta;
use Illuminate\Support\Facades\DB;

echo "=== CORRECCIÓN FINAL DIRECTA ===\n\n";

// Obtener cotizaciones
$cotizaciones = Venta::where('id_tipo_comprobante', 8)
    ->orderBy('id_venta')
    ->get();

echo "Aplicando corrección directa con UPDATE SQL...\n\n";

foreach ($cotizaciones as $index => $cotizacion) {
    $numeroConsecutivo = $index + 1;
    $serieNumeroCorrecta = 'COT-' . str_pad($numeroConsecutivo, 8, '0', STR_PAD_LEFT);
    
    echo "Corrigiendo ID {$cotizacion->id_venta}: {$serieNumeroCorrecta}\n";
    
    // Update directo usando DB
    DB::table('ventas')
        ->where('id_venta', $cotizacion->id_venta)
        ->update(['serie_numero' => $serieNumeroCorrecta]);
}

echo "\n✅ Corrección aplicada. Verificando...\n\n";

// Verificar nuevamente
$cotizacionesCorregidas = Venta::where('id_tipo_comprobante', 8)
    ->with('cliente')
    ->orderBy('id_venta')
    ->get();

foreach ($cotizacionesCorregidas as $cotizacion) {
    $cliente = $cotizacion->cliente ? $cotizacion->cliente->nombre : 'Sin cliente';
    echo "✅ {$cotizacion->serie_numero} - {$cliente} - S/ {$cotizacion->total}\n";
}

echo "\n🎉 ¡COTIZACIONES TOTALMENTE CORREGIDAS!\n";
?>