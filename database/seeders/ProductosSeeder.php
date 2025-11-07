<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productos = [
            // Motores
            [
                'id_categoria' => 1, 'id_marca' => 1, 'id_proveedor' => 1,
                'codigo' => 'MOT001', 'numero_parte' => 'C15-550HP',
                'descripcion' => 'Motor Diesel C15 550HP para Excavadora',
                'modelo' => 'C15ACERT', 'peso' => 1200.00, 'ubicacion' => 'A-01-01',
                'stock_actual' => 5, 'stock_minimo' => 2, 'precio_compra' => 45000.00, 'precio_venta' => 65000.00,
                'importado' => true, 'activo' => true
            ],
            [
                'id_categoria' => 1, 'id_marca' => 2, 'id_proveedor' => 2,
                'codigo' => 'MOT002', 'numero_parte' => 'SAA6D170E',
                'descripcion' => 'Motor Komatsu SAA6D170E-5 para PC450',
                'modelo' => 'SAA6D170E-5', 'peso' => 850.00, 'ubicacion' => 'A-01-02',
                'stock_actual' => 3, 'stock_minimo' => 1, 'precio_compra' => 38000.00, 'precio_venta' => 55000.00,
                'importado' => true, 'activo' => true
            ],
            
            // Transmisiones
            [
                'id_categoria' => 2, 'id_marca' => 1, 'id_proveedor' => 1,
                'codigo' => 'TRA001', 'numero_parte' => '301-2249',
                'descripcion' => 'Transmisión Automática CAT 777F',
                'modelo' => 'Allison CLT755', 'peso' => 650.00, 'ubicacion' => 'B-02-01',
                'stock_actual' => 2, 'stock_minimo' => 1, 'precio_compra' => 28000.00, 'precio_venta' => 42000.00,
                'importado' => true, 'activo' => true
            ],
            
            // Sistema Hidráulico
            [
                'id_categoria' => 3, 'id_marca' => 3, 'id_proveedor' => 3,
                'codigo' => 'HID001', 'numero_parte' => '14531129',
                'descripcion' => 'Bomba Hidráulica Principal Volvo EC210',
                'modelo' => 'A8V107SR', 'peso' => 45.50, 'ubicacion' => 'C-03-01',
                'stock_actual' => 8, 'stock_minimo' => 3, 'precio_compra' => 8500.00, 'precio_venta' => 12500.00,
                'importado' => true, 'activo' => true
            ],
            [
                'id_categoria' => 3, 'id_marca' => 1, 'id_proveedor' => 1,
                'codigo' => 'HID002', 'numero_parte' => '320-3064',
                'descripcion' => 'Cilindro Hidráulico de Pluma CAT 320D',
                'modelo' => '320D', 'peso' => 125.00, 'ubicacion' => 'C-03-02',
                'stock_actual' => 6, 'stock_minimo' => 2, 'precio_compra' => 3200.00, 'precio_venta' => 4800.00,
                'importado' => true, 'activo' => true
            ],
            
            // Filtros
            [
                'id_categoria' => 6, 'id_marca' => 1, 'id_proveedor' => 1,
                'codigo' => 'FIL001', 'numero_parte' => '1R-0756',
                'descripcion' => 'Filtro de Aceite de Motor CAT',
                'modelo' => '1R-0756', 'peso' => 1.20, 'ubicacion' => 'F-06-01',
                'stock_actual' => 50, 'stock_minimo' => 20, 'precio_compra' => 45.00, 'precio_venta' => 75.00,
                'importado' => false, 'activo' => true
            ],
            [
                'id_categoria' => 6, 'id_marca' => 2, 'id_proveedor' => 2,
                'codigo' => 'FIL002', 'numero_parte' => '600-185-4100',
                'descripcion' => 'Filtro de Aire Komatsu PC200',
                'modelo' => 'AF25557', 'peso' => 2.80, 'ubicacion' => 'F-06-02',
                'stock_actual' => 35, 'stock_minimo' => 15, 'precio_compra' => 85.00, 'precio_venta' => 130.00,
                'importado' => false, 'activo' => true
            ],
            
            // Llantas
            [
                'id_categoria' => 5, 'id_marca' => 4, 'id_proveedor' => 4,
                'codigo' => 'LLA001', 'numero_parte' => '23.5R25',
                'descripcion' => 'Llanta Radial 23.5R25 para Cargador Frontal',
                'modelo' => 'XMINE D2', 'peso' => 450.00, 'ubicacion' => 'E-05-01',
                'stock_actual' => 12, 'stock_minimo' => 4, 'precio_compra' => 1850.00, 'precio_venta' => 2650.00,
                'importado' => true, 'activo' => true
            ],
            
            // Sistema Eléctrico
            [
                'id_categoria' => 7, 'id_marca' => 1, 'id_proveedor' => 1,
                'codigo' => 'ELE001', 'numero_parte' => '385-2739',
                'descripcion' => 'Alternador 24V 100A CAT C15',
                'modelo' => 'C15ACERT', 'peso' => 15.50, 'ubicacion' => 'G-07-01',
                'stock_actual' => 10, 'stock_minimo' => 3, 'precio_compra' => 650.00, 'precio_venta' => 950.00,
                'importado' => true, 'activo' => true
            ],
            
            // Lubricantes
            [
                'id_categoria' => 9, 'id_marca' => 1, 'id_proveedor' => 5,
                'codigo' => 'LUB001', 'numero_parte' => 'DEO-ULS',
                'descripcion' => 'Aceite de Motor CAT DEO-ULS 15W-40',
                'modelo' => '15W-40', 'peso' => 18.90, 'ubicacion' => 'I-09-01',
                'stock_actual' => 100, 'stock_minimo' => 30, 'precio_compra' => 85.00, 'precio_venta' => 125.00,
                'importado' => false, 'activo' => true
            ],
        ];

        foreach ($productos as $producto) {
            $producto['created_at'] = Carbon::now();
            $producto['updated_at'] = Carbon::now();
            DB::table('productos')->insert($producto);
        }
    }
}
