<?php

/**
 * Script para corregir el stock que se descontó incorrectamente
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Producto;

echo "\n🔧 Corrigiendo stock del producto 10...\n\n";

$producto = Producto::find(10);

if ($producto) {
    echo "Producto: {$producto->descripcion}\n";
    echo "Stock actual: {$producto->stock_actual}\n";
    
    $producto->stock_actual = 34; // Restaurar al valor correcto
    $producto->save();
    
    echo "Stock corregido: {$producto->stock_actual}\n\n";
    echo "✅ Stock restaurado correctamente\n\n";
} else {
    echo "❌ No se encontró el producto\n\n";
}
