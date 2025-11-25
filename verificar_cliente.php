<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== VERIFICACIÓN DE DATOS DEL CLIENTE ===\n\n";

// Buscar cliente
$cliente = \App\Models\Cliente::where('numero_documento', '12345678')->first();

if (!$cliente) {
    echo "Cliente con documento 12345678 no encontrado\n";
    exit;
}

echo "Cliente: {$cliente->nombre}\n";
echo "ID: {$cliente->id_cliente}\n\n";

// Obtener todas las ventas
$ventas = \App\Models\Venta::where('id_cliente', $cliente->id_cliente)->get();

echo "Total de ventas: {$ventas->count()}\n\n";

foreach ($ventas as $venta) {
    echo "Venta ID: {$venta->id_venta}\n";
    echo "  Estado: {$venta->xml_estado}\n";
    echo "  Total: S/ " . number_format($venta->total, 2) . "\n";
    echo "  Saldo: S/ " . number_format($venta->saldo, 2) . "\n";
    echo "  Cancelado: S/ " . number_format($venta->total - $venta->saldo, 2) . "\n";
    echo "  ---\n";
}

// Calcular total según lógica del dashboard
$ventas_consideradas = \App\Models\Venta::where('id_cliente', $cliente->id_cliente)
    ->whereIn('xml_estado', ['ACEPTADO', 'PENDIENTE'])
    ->get();

$total_gastado = $ventas_consideradas->sum(function($venta) {
    if ($venta->xml_estado == 'ACEPTADO') {
        return $venta->total;
    } else {
        return $venta->total - $venta->saldo;
    }
});

echo "\n=== CÁLCULO SEGÚN DASHBOARD ===\n";
echo "Ventas consideradas (ACEPTADO/PENDIENTE): {$ventas_consideradas->count()}\n";
echo "Total gastado: S/ " . number_format($total_gastado, 2) . "\n";
