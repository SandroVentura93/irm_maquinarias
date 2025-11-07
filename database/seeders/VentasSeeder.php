<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VentasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Limpiar ventas existentes (opcional)
        DB::table('detalle_ventas')->delete();
        DB::table('ventas')->delete();
        
        // Obtener IDs existentes
        $clientes = DB::table('clientes')->pluck('id_cliente')->toArray();
        $usuarios = DB::table('users')->pluck('id')->toArray();
        $productos = DB::table('productos')->pluck('id_producto')->toArray();
        
        if (empty($clientes) || empty($usuarios) || empty($productos)) {
            $this->command->error('Faltan datos base. Ejecuta primero los seeders de clientes, usuarios y productos.');
            return;
        }
        
        $ventas = [];
        $detalleVentas = [];
        $fechaBase = Carbon::now()->subDays(30);
        
        // Generar 15 ventas variadas
        for ($i = 1; $i <= 15; $i++) {
            $fecha = $fechaBase->copy()->addDays(rand(0, 30));
            $cliente = $clientes[array_rand($clientes)];
            $vendedor = $usuarios[array_rand($usuarios)];
            
            // Determinar tipo de comprobante y serie
            $tipoComprobante = rand(1, 2); // 1=Factura, 2=Boleta
            $serie = $tipoComprobante == 1 ? 'F001' : 'B001';
            
            // Estados variados
            $estados = ['PENDIENTE', 'ENVIADO', 'ACEPTADO', 'PENDIENTE', 'ACEPTADO'];
            $estado = $estados[array_rand($estados)];
            
            // Crear venta
            $venta = [
                'id_venta' => $i + 2, // Empezar desde 3 porque ya tenemos 1 y 2
                'id_cliente' => $cliente,
                'id_vendedor' => $vendedor,
                'id_moneda' => 1, // Soles
                'id_tipo_comprobante' => $tipoComprobante,
                'serie' => $serie,
                'numero' => str_pad($i + 2, 8, '0', STR_PAD_LEFT),
                'fecha' => $fecha->format('Y-m-d H:i:s'),
                'subtotal' => 0, // Se calculará después
                'igv' => 0,
                'total' => 0,
                'xml_estado' => $estado,
                'created_at' => $fecha,
                'updated_at' => $fecha,
            ];
            
            // Generar productos para esta venta
            $numProductos = rand(1, 4); // Entre 1 y 4 productos por venta
            $productosSeleccionados = array_rand($productos, min($numProductos, count($productos)));
            if (!is_array($productosSeleccionados)) {
                $productosSeleccionados = [$productosSeleccionados];
            }
            
            $totalVenta = 0;
            $detalleId = count($detalleVentas) + 3; // Continuar desde el último detalle
            
            foreach ($productosSeleccionados as $prodIndex) {
                $producto = $productos[$prodIndex];
                $cantidad = rand(1, 3);
                
                // Obtener precio del producto
                $precioUnitario = DB::table('productos')
                    ->where('id_producto', $producto)
                    ->value('precio_venta');
                
                // Descuento aleatorio
                $descuentoPorcentaje = rand(0, 20); // 0% a 20%
                $precioFinal = $precioUnitario * (1 - $descuentoPorcentaje / 100);
                
                $totalLinea = $precioFinal * $cantidad;
                $subtotalLinea = $totalLinea / 1.18;
                $igvLinea = $totalLinea - $subtotalLinea;
                
                $detalle = [
                    'id_detalle' => $detalleId++,
                    'id_venta' => $venta['id_venta'],
                    'id_producto' => $producto,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precioUnitario,
                    'descuento_porcentaje' => $descuentoPorcentaje,
                    'precio_final' => $precioFinal,
                    'subtotal' => $subtotalLinea,
                    'igv' => $igvLinea,
                    'total' => $totalLinea,
                    'created_at' => $fecha,
                    'updated_at' => $fecha,
                ];
                
                $detalleVentas[] = $detalle;
                $totalVenta += $totalLinea;
            }
            
            // Actualizar totales de la venta
            $venta['subtotal'] = $totalVenta / 1.18;
            $venta['igv'] = $totalVenta - $venta['subtotal'];
            $venta['total'] = $totalVenta;
            
            $ventas[] = $venta;
        }
        
        // Insertar ventas
        DB::table('ventas')->insert($ventas);
        $this->command->info('Insertadas ' . count($ventas) . ' ventas');
        
        // Insertar detalles
        DB::table('detalle_ventas')->insert($detalleVentas);
        $this->command->info('Insertados ' . count($detalleVentas) . ' detalles de venta');
        
        // Algunas ventas anuladas (2-3 ventas)
        $ventasParaAnular = array_slice($ventas, 0, 3);
        foreach ($ventasParaAnular as $venta) {
            if ($venta['xml_estado'] !== 'ANULADO') {
                DB::table('ventas')
                    ->where('id_venta', $venta['id_venta'])
                    ->update([
                        'xml_estado' => 'ANULADO',
                        'fecha_anulacion' => Carbon::now(),
                        'motivo_anulacion' => 'Anulación de prueba - Seeder'
                    ]);
            }
        }
        
        $this->command->info('Anuladas 3 ventas como ejemplo');
        $this->command->info('Seeder de ventas completado exitosamente!');
    }
}
