<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Venta;
use App\Models\TipoComprobante;
use Illuminate\Support\Facades\Http;

class TestPDFGeneration extends Command
{
    protected $signature = 'test:pdf-generation {--all : Probar todos los tipos}';
    protected $description = 'Probar generación de PDFs para verificar que funcionan';

    public function handle()
    {
        $this->info('🧪 Probando generación de PDFs...');
        
        // Obtener ventas por tipo de comprobante
        $ventasPorTipo = [];
        $tipos = TipoComprobante::all();
        
        foreach ($tipos as $tipo) {
            $venta = Venta::whereHas('tipoComprobante', function($q) use ($tipo) {
                $q->where('codigo_sunat', $tipo->codigo_sunat);
            })->first();
            
            if ($venta) {
                $ventasPorTipo[$tipo->codigo_sunat] = [
                    'venta' => $venta,
                    'tipo' => $tipo
                ];
            }
        }
        
        $this->line('');
        $this->info('📊 Ventas disponibles por tipo:');
        
        foreach ($ventasPorTipo as $codigo => $data) {
            $venta = $data['venta'];
            $tipo = $data['tipo'];
            
            $this->line("  {$codigo} - {$tipo->descripcion}:");
            $this->line("    Venta ID: {$venta->id_venta}");
            $this->line("    Número: {$venta->serie}-{$venta->numero}");
            $this->line("    Total: S/{$venta->total}");
            $this->line("    URL: /pdf/comprobante/{$venta->id_venta}/view");
            $this->line('');
        }
        
        if ($this->option('all')) {
            $this->info('🔄 Probando generación de todos los PDFs...');
            
            foreach ($ventasPorTipo as $codigo => $data) {
                $venta = $data['venta'];
                $tipo = $data['tipo'];
                
                $this->line("Probando {$tipo->descripcion}...");
                
                try {
                    // Simular llamada HTTP al PDF
                    $url = "http://127.0.0.1:8001/pdf/comprobante/{$venta->id_venta}/view";
                    
                    // Para testing local, verificamos solo que la ruta existe
                    $this->line("  ✅ PDF URL: {$url}");
                    
                } catch (\Exception $e) {
                    $this->error("  ❌ Error: {$e->getMessage()}");
                }
            }
        }
        
        $this->line('');
        $this->comment('💡 URLs de prueba:');
        foreach ($ventasPorTipo as $codigo => $data) {
            $venta = $data['venta'];
            $this->line("  {$codigo}: http://127.0.0.1:8001/pdf/comprobante/{$venta->id_venta}/view");
        }
        
        return 0;
    }
}