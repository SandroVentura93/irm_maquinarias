<?php

/**
 * Script para comparar stock ANTES y DESPUÉS de crear cotización
 * Uso: Ejecuta ANTES y DESPUÉS de crear la cotización
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Producto;

$archivo = __DIR__ . '/stock_snapshot.json';

// Si no existe el archivo, crear snapshot
if (!file_exists($archivo)) {
    echo "\n📸 Creando SNAPSHOT del stock actual...\n\n";
    
    $productos = Producto::select('id_producto', 'descripcion', 'stock_actual')
        ->orderBy('id_producto')
        ->get();
    
    $snapshot = [];
    foreach ($productos as $p) {
        $snapshot[$p->id_producto] = [
            'descripcion' => $p->descripcion,
            'stock' => $p->stock_actual
        ];
        echo "   {$p->id_producto}: {$p->descripcion} = {$p->stock_actual}\n";
    }
    
    file_put_contents($archivo, json_encode($snapshot, JSON_PRETTY_PRINT));
    
    echo "\n✅ Snapshot guardado en: stock_snapshot.json\n";
    echo "\n🎯 SIGUIENTE PASO:\n";
    echo "   1. CREA UNA COTIZACIÓN ahora\n";
    echo "   2. Ejecuta este script nuevamente: php comparar_stock.php\n\n";
    
} else {
    // Comparar con el snapshot anterior
    echo "\n🔍 COMPARANDO stock actual con snapshot anterior...\n\n";
    
    $snapshotAnterior = json_decode(file_get_contents($archivo), true);
    $productos = Producto::select('id_producto', 'descripcion', 'stock_actual')
        ->orderBy('id_producto')
        ->get();
    
    $cambios = [];
    $sinCambios = 0;
    
    foreach ($productos as $p) {
        $stockAnterior = $snapshotAnterior[$p->id_producto]['stock'] ?? null;
        $stockActual = $p->stock_actual;
        
        if ($stockAnterior !== null && $stockAnterior != $stockActual) {
            $diferencia = $stockActual - $stockAnterior;
            $cambios[] = [
                'id' => $p->id_producto,
                'nombre' => $p->descripcion,
                'antes' => $stockAnterior,
                'despues' => $stockActual,
                'diferencia' => $diferencia
            ];
        } else {
            $sinCambios++;
        }
    }
    
    if (count($cambios) > 0) {
        echo "❌ SE DETECTARON CAMBIOS EN EL STOCK:\n\n";
        foreach ($cambios as $c) {
            $simbolo = $c['diferencia'] < 0 ? '📉' : '📈';
            $color = $c['diferencia'] < 0 ? 'DISMINUYÓ' : 'AUMENTÓ';
            echo "   $simbolo Producto ID {$c['id']}: {$c['nombre']}\n";
            echo "      Stock anterior: {$c['antes']}\n";
            echo "      Stock actual: {$c['despues']}\n";
            echo "      Diferencia: {$c['diferencia']} ($color)\n\n";
        }
        
        echo "╔════════════════════════════════════════════════════════╗\n";
        echo "║  ⚠️  PROBLEMA CONFIRMADO                               ║\n";
        echo "║  El stock SÍ está cambiando al crear cotizaciones     ║\n";
        echo "╚════════════════════════════════════════════════════════╝\n\n";
        
    } else {
        echo "✅ NO HAY CAMBIOS EN EL STOCK\n\n";
        echo "   Productos verificados: {$sinCambios}\n";
        echo "   Productos con cambios: 0\n\n";
        
        echo "╔════════════════════════════════════════════════════════╗\n";
        echo "║  ✅ TODO CORRECTO                                      ║\n";
        echo "║  Las cotizaciones NO están afectando el stock         ║\n";
        echo "╚════════════════════════════════════════════════════════╝\n\n";
    }
    
    // Eliminar el snapshot para la próxima prueba
    unlink($archivo);
    echo "📝 Snapshot eliminado. Ejecuta nuevamente para crear uno nuevo.\n\n";
}
