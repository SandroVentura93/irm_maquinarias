<?php

// Script de prueba para crear una venta con descuento
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

echo "=== PRUEBA DE VENTA CON DESCUENTO ===\n";

try {
    DB::beginTransaction();
    
    // Crear una venta de prueba
    $venta = new Venta();
    $venta->fecha = '2024-11-07 11:45:00';
    $venta->id_cliente = 1; // Constructora Los Andes S.A.C.
    $venta->id_tipo_comprobante = 1;
    $venta->serie = 'B001';
    $venta->numero = '00000001';
    $venta->id_moneda = 1;
    $venta->subtotal = 0;
    $venta->igv = 0;
    $venta->total = 0;
    $venta->save();
    
    echo "Venta creada con ID: " . $venta->id_venta . "\n";
    
    // Agregar productos con descuento
    $productos = [
        [
            'id_producto' => 1,
            'cantidad' => 2,
            'precio_unitario' => 65000.00,
            'descuento_porcentaje' => 10,
            'precio_final' => 58500.00, // 65000 - (65000 * 0.10)
        ],
        [
            'id_producto' => 2,
            'cantidad' => 1,
            'precio_unitario' => 55000.00,
            'descuento_porcentaje' => 5,
            'precio_final' => 52250.00, // 55000 - (55000 * 0.05)
        ]
    ];
    
    $subtotal = 0;
    $total_descuento = 0;
    
    foreach ($productos as $prod) {
        $total_linea = $prod['precio_final'] * $prod['cantidad'];
        $subtotal_linea = $total_linea / 1.18; // Sin IGV
        $igv_linea = $total_linea - $subtotal_linea; // IGV calculado
        
        $detalle = new DetalleVenta();
        $detalle->id_venta = $venta->id_venta;
        $detalle->id_producto = $prod['id_producto'];
        $detalle->cantidad = $prod['cantidad'];
        $detalle->precio_unitario = $prod['precio_unitario'];
        $detalle->descuento_porcentaje = $prod['descuento_porcentaje'];
        $detalle->precio_final = $prod['precio_final'];
        $detalle->subtotal = $subtotal_linea;
        $detalle->igv = $igv_linea;
        $detalle->total = $total_linea;
        $detalle->save();
        
        echo "Detalle agregado - Producto ID: {$prod['id_producto']}, Cantidad: {$prod['cantidad']}, Precio: {$prod['precio_unitario']}, Descuento: {$prod['descuento_porcentaje']}%, Precio Final: {$prod['precio_final']}\n";
        
        $subtotal += ($prod['precio_unitario'] * $prod['cantidad']);
        $total_descuento += (($prod['precio_unitario'] - $prod['precio_final']) * $prod['cantidad']);
        
        // Actualizar stock del producto
        $producto = Producto::find($prod['id_producto']);
        if ($producto) {
            $producto->stock_actual -= $prod['cantidad'];
            $producto->save();
            echo "Stock actualizado para producto ID: {$prod['id_producto']}, nuevo stock: {$producto->stock_actual}\n";
        }
    }
    
    // Actualizar totales de la venta
    $total_final = $subtotal - $total_descuento;
    $venta->subtotal = $total_final / 1.18; // Sin IGV
    $venta->igv = $total_final - $venta->subtotal; // IGV calculado
    $venta->total = $total_final;
    $venta->save();
    
    echo "Venta actualizada - Subtotal: {$venta->subtotal}, IGV: {$venta->igv}, Total: {$venta->total}, Descuento aplicado: {$total_descuento}\n";
    
    DB::commit();
    echo "=== VENTA GUARDADA EXITOSAMENTE ===\n";
    
} catch (Exception $e) {
    DB::rollback();
    echo "ERROR: " . $e->getMessage() . "\n";
}