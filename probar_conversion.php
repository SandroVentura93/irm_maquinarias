<?php

/**
 * Script para probar la conversión de cotización
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Venta;
use App\Models\Producto;

echo "\n🧪 PRUEBA DE CONVERSIÓN DE COTIZACIÓN\n\n";

// Buscar la última cotización pendiente
$cotizacion = Venta::where('id_tipo_comprobante', 8)
    ->where('xml_estado', 'PENDIENTE')
    ->orderBy('created_at', 'desc')
    ->first();

if (!$cotizacion) {
    echo "❌ No hay cotizaciones pendientes para probar\n";
    echo "   Crea una cotización primero\n\n";
    exit(0);
}

echo "📋 Cotización encontrada:\n";
echo "   ID: {$cotizacion->id_venta}\n";
echo "   Serie-Número: {$cotizacion->serie}-{$cotizacion->numero}\n";
echo "   Estado: {$cotizacion->xml_estado}\n";
echo "   Tipo Comprobante ID: {$cotizacion->id_tipo_comprobante}\n\n";

// Verificar productos
echo "📦 Productos en la cotización:\n";
$stockAntes = [];
foreach ($cotizacion->detalleVentas as $detalle) {
    $producto = Producto::find($detalle->id_producto);
    if ($producto) {
        $stockAntes[$producto->id_producto] = $producto->stock_actual;
        echo "   - {$producto->descripcion}\n";
        echo "     Cantidad: {$detalle->cantidad}\n";
        echo "     Stock actual: {$producto->stock_actual}\n\n";
    }
}

echo "⚙️  Intentando convertir a Factura...\n\n";

// Simular la conversión
try {
    DB::beginTransaction();
    
    $nuevoTipoId = 1; // Factura
    $nuevaSerie = 'F001';
    
    // Obtener siguiente número
    $ultimoNumero = Venta::where('serie', $nuevaSerie)
        ->where('id_tipo_comprobante', $nuevoTipoId)
        ->max('numero');
    
    if ($ultimoNumero && strpos($ultimoNumero, '-') !== false) {
        $ultimoNumero = explode('-', $ultimoNumero)[1];
    }
    $ultimoNumero = intval($ultimoNumero ?: 0);
    $siguienteNumero = $ultimoNumero + 1;
    $nuevoNumeroFormateado = 'F001-' . str_pad($siguienteNumero, 8, '0', STR_PAD_LEFT);
    
    echo "✅ Nuevo número calculado: $nuevoNumeroFormateado\n\n";
    
    // Actualizar la venta
    $cotizacion->update([
        'id_tipo_comprobante' => $nuevoTipoId,
        'serie' => $nuevaSerie,
        'numero' => $nuevoNumeroFormateado,
        'xml_estado' => 'PENDIENTE'
    ]);
    
    echo "✅ Venta actualizada\n\n";
    
    // Descontar stock
    echo "📉 Descontando stock...\n";
    foreach ($cotizacion->detalleVentas as $detalle) {
        Producto::where('id_producto', $detalle->id_producto)
            ->decrement('stock_actual', $detalle->cantidad);
        
        $producto = Producto::find($detalle->id_producto);
        echo "   - Producto {$detalle->id_producto}: {$stockAntes[$detalle->id_producto]} → {$producto->stock_actual}\n";
    }
    
    DB::commit();
    
    echo "\n✅✅✅ CONVERSIÓN EXITOSA ✅✅✅\n\n";
    echo "Cotización {$cotizacion->serie}-COT-00000018 convertida a Factura $nuevoNumeroFormateado\n\n";
    
} catch (\Exception $e) {
    DB::rollBack();
    echo "\n❌ ERROR: {$e->getMessage()}\n\n";
}
