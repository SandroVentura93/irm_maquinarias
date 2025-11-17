<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Venta;

class TestVentasConClientes extends Command
{
    protected $signature = 'test:ventas-clientes';
    protected $description = 'Probar consulta de ventas con clientes usando numero_documento';

    public function handle()
    {
        $this->info('🧪 PROBANDO VENTAS CON CLIENTES');

        try {
            $this->line("\n1️⃣ Probando la misma consulta del VentaController...");
            
            $ventas = Venta::with(['cliente:id_cliente,nombre,numero_documento', 'tipoComprobante:id_tipo_comprobante,descripcion,codigo_sunat'])
                ->paginate(10);

            $this->info("   ✅ Consulta exitosa: {$ventas->total()} ventas encontradas");

            $this->line("\n2️⃣ Mostrando primeras 5 ventas con datos de cliente:");
            foreach ($ventas->take(5) as $venta) {
                $clienteInfo = $venta->cliente ? 
                    "{$venta->cliente->nombre} (Doc: {$venta->cliente->numero_documento})" : 
                    'Sin cliente';
                    
                $this->line("   • {$venta->serie_numero} - {$clienteInfo}");
            }

            $this->line("\n3️⃣ Verificando integridad de datos:");
            $ventasSinCliente = Venta::whereNull('id_cliente')->count();
            $ventasConCliente = Venta::whereNotNull('id_cliente')->count();
            
            $this->line("   • Ventas con cliente: {$ventasConCliente}");
            $this->line("   • Ventas sin cliente: {$ventasSinCliente}");

            $this->info("\n🎉 ¡PRUEBA COMPLETADA EXITOSAMENTE!");
            $this->line("   La consulta del VentaController funciona perfectamente");

        } catch (\Exception $e) {
            $this->error("❌ Error en la prueba:");
            $this->line("   {$e->getMessage()}");
            $this->line("   Línea: {$e->getLine()}");
            $this->line("   Archivo: {$e->getFile()}");
        }
    }
}