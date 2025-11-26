<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Venta;

// Buscar una cotización pendiente
$venta = Venta::where('id_tipo_comprobante', 8)
    ->where('xml_estado', 'PENDIENTE')
    ->first();

if ($venta) {
    echo "✅ Cotización encontrada:\n";
    echo "ID: {$venta->id_venta}\n";
    echo "Tipo Comprobante: {$venta->id_tipo_comprobante}\n";
    echo "Estado: {$venta->xml_estado}\n";
    echo "Serie: {$venta->serie}\n";
    echo "Número: {$venta->numero}\n";
    echo "\n";
    
    // Verificar que puede acceder a relaciones
    echo "Cliente: " . ($venta->cliente ? $venta->cliente->nombre : 'N/A') . "\n";
    echo "Tipo Comprobante Descripción: " . ($venta->tipoComprobante ? $venta->tipoComprobante->descripcion : 'N/A') . "\n";
} else {
    echo "❌ No se encontraron cotizaciones pendientes\n";
    
    // Mostrar todas las ventas
    $ventas = Venta::orderBy('created_at', 'desc')->limit(5)->get();
    echo "\nÚltimas 5 ventas:\n";
    foreach ($ventas as $v) {
        echo "- ID: {$v->id_venta} | Tipo: {$v->id_tipo_comprobante} | Estado: {$v->xml_estado} | {$v->serie}-{$v->numero}\n";
    }
}
