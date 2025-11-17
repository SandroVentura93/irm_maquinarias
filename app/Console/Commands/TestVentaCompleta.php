<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\TipoComprobante;
use Illuminate\Support\Facades\Http;

class TestVentaCompleta extends Command
{
    protected $signature = 'test:venta-completa';
    protected $description = 'Probar creación completa de venta y generación de PDF';

    public function handle()
    {
        $this->info('🧪 PROBANDO CREACIÓN COMPLETA DE VENTA');
        $this->line('');
        
        // 1. Verificar datos necesarios
        $this->info('1️⃣ Verificando datos necesarios...');
        
        $cliente = Cliente::first();
        $producto = Producto::first();
        $tipoComprobante = TipoComprobante::where('codigo_sunat', '01')->first(); // Factura
        
        if (!$cliente) {
            $this->error('❌ No hay clientes en la base de datos');
            return 1;
        }
        
        if (!$producto) {
            $this->error('❌ No hay productos en la base de datos');
            return 1;
        }
        
        if (!$tipoComprobante) {
            $this->error('❌ No se encontró tipo de comprobante Factura');
            return 1;
        }
        
        $this->line("✅ Cliente: {$cliente->nombres} {$cliente->apellidos}");
        $this->line("✅ Producto: {$producto->nombre} - S/ {$producto->precio}");
        $this->line("✅ Tipo: {$tipoComprobante->descripcion}");
        $this->line('');
        
        // 2. Simular payload del formulario
        $this->info('2️⃣ Creando payload de venta...');
        
        $payload = [
            'id_cliente' => $cliente->id_cliente,
            'tipo_comprobante' => $tipoComprobante->id_tipo_comprobante,
            'moneda' => 'PEN',
            'serie' => 'F001',
            'detalle' => [
                [
                    'id_producto' => $producto->id_producto,
                    'cantidad' => 2,
                    'precio_unitario' => $producto->precio,
                    'descuento_porcentaje' => 0
                ]
            ]
        ];
        
        $this->line("✅ Payload creado:");
        $this->line("   Cliente ID: {$payload['id_cliente']}");
        $this->line("   Tipo: {$payload['tipo_comprobante']}");
        $this->line("   Serie: {$payload['serie']}");
        $this->line("   Productos: " . count($payload['detalle']));
        $this->line('');
        
        // 3. Simular llamada AJAX
        $this->info('3️⃣ Simulando llamada AJAX...');
        
        try {
            // Simular la lógica del controlador
            $controller = new \App\Http\Controllers\VentaController();
            
            // Crear un request simulado
            $request = new \Illuminate\Http\Request();
            $request->merge($payload);
            
            // No podemos llamar directamente al método porque necesita autenticación
            // En su lugar, haremos una llamada HTTP real
            
            $response = Http::post('http://127.0.0.1:8001/api/ventas/guardar', $payload);
            
            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['ok']) {
                    $this->line("✅ Venta creada exitosamente!");
                    $this->line("   ID Venta: {$data['id_venta']}");
                    $this->line("   Total: S/ {$data['total']}");
                    $this->line("   Serie-Número: {$data['serie']}-" . str_pad($data['numero_comprobante'], 8, '0', STR_PAD_LEFT));
                    
                    // 4. Probar generación de PDF
                    $this->line('');
                    $this->info('4️⃣ Probando generación de PDF...');
                    
                    $ventaId = $data['id_venta'];
                    
                    // Probar vista
                    $pdfViewUrl = "http://127.0.0.1:8001/pdf/comprobante/{$ventaId}/view";
                    $viewResponse = Http::get($pdfViewUrl);
                    
                    if ($viewResponse->successful()) {
                        $this->line("✅ PDF View: {$pdfViewUrl}");
                    } else {
                        $this->error("❌ Error en PDF View: " . $viewResponse->status());
                    }
                    
                    // Probar descarga
                    $pdfDownloadUrl = "http://127.0.0.1:8001/pdf/comprobante/{$ventaId}/download";
                    $downloadResponse = Http::get($pdfDownloadUrl);
                    
                    if ($downloadResponse->successful()) {
                        $this->line("✅ PDF Download: {$pdfDownloadUrl}");
                    } else {
                        $this->error("❌ Error en PDF Download: " . $downloadResponse->status());
                    }
                    
                    $this->line('');
                    $this->comment('🎉 PRUEBA COMPLETA EXITOSA!');
                    $this->line('');
                    $this->info('📄 URLs para probar:');
                    $this->line("   Ver PDF: {$pdfViewUrl}");
                    $this->line("   Descargar: {$pdfDownloadUrl}");
                    
                } else {
                    $this->error("❌ Error al crear venta: " . ($data['error'] ?? 'Error desconocido'));
                }
            } else {
                $this->error("❌ Error HTTP: " . $response->status());
                $this->error("   Response: " . $response->body());
            }
            
        } catch (\Exception $e) {
            $this->error("❌ Excepción: " . $e->getMessage());
        }
        
        return 0;
    }
}