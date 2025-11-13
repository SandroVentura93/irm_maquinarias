<?php

namespace Tests\Feature;

use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\TipoComprobante;
use App\Models\Moneda;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VentaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_new_venta()
    {
        // Crear datos necesarios
        $cliente = Cliente::factory()->create();
        $producto = Producto::factory()->create(['stock_actual' => 10]);
        $tipoComprobante = TipoComprobante::factory()->create();
        $moneda = Moneda::factory()->create();

        // Datos de la venta
        $data = [
            'fecha' => now()->toDateTimeString(),
            'id_cliente' => $cliente->id_cliente,
            'id_tipo_comprobante' => $tipoComprobante->id_tipo_comprobante,
            'serie' => 'F001',
            'numero' => '000123',
            'id_moneda' => $moneda->id_moneda,
            'productos' => [
                [
                    'producto_id' => $producto->id_producto,
                    'cantidad' => 2,
                    'precio_unitario' => $producto->precio_venta,
                    'descuento_porcentaje' => 0,
                    'precio_final' => $producto->precio_venta * 2,
                ],
            ],
        ];

        // Enviar solicitud POST
        $response = $this->post(route('ventas.store'), $data);

        // Verificar respuesta
        $response->assertRedirect(route('ventas.index'));
        $this->assertDatabaseHas('ventas', [
            'id_cliente' => $cliente->id_cliente,
            'total' => $producto->precio_venta * 2,
        ]);
        $this->assertDatabaseHas('detalle_ventas', [
            'id_producto' => $producto->id_producto,
            'cantidad' => 2,
        ]);

        // Verificar stock actualizado
        $this->assertDatabaseHas('productos', [
            'id_producto' => $producto->id_producto,
            'stock_actual' => 8, // 10 - 2
        ]);
    }
}