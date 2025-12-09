<?php

use Illuminate\Support\Facades\DB;

require 'vendor/autoload.php';

// Configuración de Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// ID del proveedor a verificar
$id_proveedor = 1; // Cambiar según sea necesario

// Consulta para obtener productos asociados al proveedor
$productos = DB::table('productos')
    ->where('id_proveedor', $id_proveedor)
    ->select('id_producto', 'codigo', 'descripcion')
    ->get();

if ($productos->isEmpty()) {
    echo "No se encontraron productos para el proveedor con ID: $id_proveedor\n";
} else {
    echo "Productos encontrados para el proveedor con ID: $id_proveedor:\n";
    foreach ($productos as $producto) {
        echo "- ID: {$producto->id_producto}, Código: {$producto->codigo}, Descripción: {$producto->descripcion}\n";
    }
}
