<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DEBUG DASHBOARD TOP CLIENTES ===\n\n";

// Ejecutar la misma lógica del controlador
$top_clientes = \App\Models\Venta::select('id_cliente')
                               ->with('cliente')
                               ->whereIn('xml_estado', ['ACEPTADO', 'PENDIENTE'])
                               ->get();

echo "Total ventas ACEPTADO/PENDIENTE: {$top_clientes->count()}\n\n";

$agrupados = $top_clientes->groupBy('id_cliente');

echo "Clientes únicos: {$agrupados->count()}\n\n";

$resultado = $agrupados->map(function($ventas) {
    echo "Cliente ID: {$ventas->first()->id_cliente} - {$ventas->first()->cliente->nombre}\n";
    echo "  Ventas: {$ventas->count()}\n";
    
    $total_gastado = $ventas->sum(function($venta) {
        if ($venta->xml_estado == 'ACEPTADO') {
            echo "    Venta ID {$venta->id_venta} (ACEPTADO): Total = {$venta->total}\n";
            return $venta->total;
        } else {
            $cancelado = $venta->total - $venta->saldo;
            echo "    Venta ID {$venta->id_venta} (PENDIENTE): Total = {$venta->total}, Saldo = {$venta->saldo}, Cancelado = {$cancelado}\n";
            return $cancelado;
        }
    });
    
    echo "  TOTAL GASTADO: S/ " . number_format($total_gastado, 2) . "\n\n";
    
    return (object)[
        'cliente' => $ventas->first()->cliente,
        'total_compras' => $ventas->count(),
        'total_gastado' => $total_gastado
    ];
});

$ordenados = $resultado->sortByDesc('total_gastado')->take(5)->values();

echo "\n=== TOP 5 CLIENTES ===\n";
foreach ($ordenados as $index => $cliente_data) {
    echo ($index + 1) . ". {$cliente_data->cliente->nombre}\n";
    echo "   Compras: {$cliente_data->total_compras}\n";
    echo "   Gastado: S/ " . number_format($cliente_data->total_gastado, 2) . "\n\n";
}
