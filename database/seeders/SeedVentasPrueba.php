<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Cliente;
use App\Models\Moneda;
use App\Models\Producto;

class SeedVentasPrueba extends Seeder
{
    public function run(): void
    {
        DB::beginTransaction();
        try {
            $cliente = Cliente::first();
            if (!$cliente) {
                throw new \RuntimeException('No existe cliente para pruebas');
            }

            $producto = Producto::first();
            if (!$producto) {
                throw new \RuntimeException('No existe producto para pruebas');
            }

            $monedaPen = Moneda::where('codigo_iso', 'PEN')->first();
            $monedaUsd = Moneda::where('codigo_iso', 'USD')->first();
            if (!$monedaPen || !$monedaUsd) {
                throw new \RuntimeException('Faltan monedas PEN/USD para pruebas');
            }

            // Venta PEN
            $ventaPen = Venta::create([
                'id_cliente' => $cliente->id_cliente,
                'fecha' => now(),
                'subtotal' => 100.00,
                'igv' => round(100.00 * 0.18, 2),
                'total' => 100.00 + round(100.00 * 0.18, 2),
                'id_moneda' => $monedaPen->id_moneda,
                'numero' => 'PEN-PRUEBA-001',
                'xml_estado' => 'ACEPTADO',
            ]);

            DetalleVenta::create([
                'id_venta' => $ventaPen->id_venta,
                'id_producto' => $producto->id_producto,
                'cantidad' => 1,
                'precio_unitario' => 100.00,
                'descuento_porcentaje' => 0,
            ]);

            // Venta USD
            $ventaUsd = Venta::create([
                'id_cliente' => $cliente->id_cliente,
                'fecha' => now(),
                'subtotal' => 200.00,
                'igv' => round(200.00 * 0.18, 2),
                'total' => 200.00 + round(200.00 * 0.18, 2),
                'id_moneda' => $monedaUsd->id_moneda,
                'numero' => 'USD-PRUEBA-001',
                'xml_estado' => 'ACEPTADO',
            ]);

            DetalleVenta::create([
                'id_venta' => $ventaUsd->id_venta,
                'id_producto' => $producto->id_producto,
                'cantidad' => 1,
                'precio_unitario' => 200.00,
                'descuento_porcentaje' => 0,
            ]);

            DB::commit();
            Log::info('SeedVentasPrueba completado', ['pen' => $ventaPen->id_venta, 'usd' => $ventaUsd->id_venta]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('SeedVentasPrueba fallo', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
