<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cliente;
use App\Models\Venta;

class TestClienteColumns extends Command
{
    protected $signature = 'test:cliente-columns';
    protected $description = 'Verificar que las columnas de cliente funcionen correctamente';

    public function handle()
    {
        $this->info('🧪 VERIFICANDO COLUMNAS DE CLIENTE');
        $this->line('');
        
        try {
            // 1. Probar consulta básica de Cliente
            $this->info('1️⃣ Probando consulta básica de Cliente...');
            $clienteCount = Cliente::count();
            $this->line("   ✅ Total de clientes: {$clienteCount}");
            
            // 2. Probar select específico de campos
            $this->info('2️⃣ Probando select específico...');
            $clientes = Cliente::select('id_cliente', 'nombre', 'numero_documento', 'tipo_documento')
                ->limit(5)
                ->get();
                
            $this->line("   ✅ Consulta exitosa, {$clientes->count()} clientes obtenidos");
            
            // 3. Mostrar detalles de clientes
            $this->info('3️⃣ Detalles de primeros clientes:');
            foreach ($clientes as $cliente) {
                $this->line("   • ID {$cliente->id_cliente}: {$cliente->nombre} - {$cliente->numero_documento} ({$cliente->tipo_documento})");
            }
            
            // 4. Probar relación Venta -> Cliente
            $this->info('4️⃣ Probando relación Venta -> Cliente...');
            $ventasConClientes = Venta::with(['cliente:id_cliente,nombre,numero_documento'])
                ->select('id_venta', 'id_cliente', 'serie', 'numero', 'total')
                ->limit(3)
                ->get();
                
            $this->line("   ✅ Consulta de relación exitosa, {$ventasConClientes->count()} ventas obtenidas");
            
            foreach ($ventasConClientes as $venta) {
                $cliente = $venta->cliente;
                $clienteNombre = $cliente ? $cliente->nombre : 'Sin cliente';
                $clienteDoc = $cliente ? $cliente->numero_documento : 'N/A';
                
                $this->line("   • Venta {$venta->id_venta}: {$venta->serie}-{$venta->numero} - Cliente: {$clienteNombre} ({$clienteDoc})");
            }
            
            // 5. Verificar método index del VentaController
            $this->info('5️⃣ Probando método index de VentaController...');
            
            $controller = new \App\Http\Controllers\VentaController();
            
            // Simular request
            $request = new \Illuminate\Http\Request();
            
            // Esto debería funcionar sin errores ahora
            $response = $controller->index();
            
            $this->line("   ✅ Método index ejecutado sin errores");
            
            $this->line('');
            $this->comment('🎉 ¡VERIFICACIÓN DE COLUMNAS DE CLIENTE EXITOSA!');
            $this->comment('   Todas las consultas funcionan correctamente');
            
        } catch (\Exception $e) {
            $this->line('');
            $this->error('❌ ERROR EN VERIFICACIÓN:');
            $this->error("   Mensaje: {$e->getMessage()}");
            $this->error("   Archivo: {$e->getFile()}");
            $this->error("   Línea: {$e->getLine()}");
            
            return 1;
        }
        
        return 0;
    }
}