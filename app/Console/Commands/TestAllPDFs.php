<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Venta;
use App\Models\TipoComprobante;

class TestAllPDFs extends Command
{
    protected $signature = 'test:all-pdfs';
    protected $description = 'Verificar que todos los PDFs funcionen correctamente';

    public function handle()
    {
        $this->info('🔍 Verificando todos los templates PDF...');
        
        // Verificar que todos los templates existen
        $templates = [
            'factura' => 'resources/views/comprobantes/factura.blade.php',
            'boleta' => 'resources/views/comprobantes/boleta.blade.php', 
            'cotizacion' => 'resources/views/comprobantes/cotizacion.blade.php',
            'nota_credito' => 'resources/views/comprobantes/nota_credito.blade.php',
            'nota_debito' => 'resources/views/comprobantes/nota_debito.blade.php',
            'guia_remision' => 'resources/views/comprobantes/guia_remision.blade.php',
            'ticket' => 'resources/views/comprobantes/ticket.blade.php',
            'recibo_honorarios' => 'resources/views/comprobantes/recibo_honorarios.blade.php',
            'pdf' => 'resources/views/comprobantes/pdf.blade.php'
        ];
        
        $this->line('');
        $this->info('📄 Verificando templates:');
        foreach ($templates as $name => $path) {
            $exists = file_exists(base_path($path));
            $this->line("  {$name}: " . ($exists ? '✅' : '❌') . " {$path}");
        }
        
        // Verificar tipos de comprobante en BD
        $this->line('');
        $this->info('💾 Tipos de comprobante en base de datos:');
        $tipos = TipoComprobante::orderBy('codigo_sunat')->get();
        foreach ($tipos as $tipo) {
            $this->line("  ID:{$tipo->id_tipo_comprobante} | Código:{$tipo->codigo_sunat} | {$tipo->descripcion}");
        }
        
        // Verificar partial de logo
        $logoPartial = base_path('resources/views/comprobantes/partials/logo.blade.php');
        $this->line('');
        $this->info('🖼️ Logo partial: ' . (file_exists($logoPartial) ? '✅ Existe' : '❌ No existe'));
        
        // Verificar que todos los templates usan el partial
        $this->line('');
        $this->info('🔗 Verificando uso del partial de logo:');
        foreach ($templates as $name => $path) {
            if (file_exists(base_path($path))) {
                $content = file_get_contents(base_path($path));
                $usesPartial = strpos($content, "@include('comprobantes.partials.logo')") !== false;
                $this->line("  {$name}: " . ($usesPartial ? '✅ Usa partial' : '⚠️ No usa partial'));
            }
        }
        
        // Verificar ventas existentes para testing
        $this->line('');
        $this->info('📊 Ventas disponibles para testing:');
        $ventas = Venta::with('tipoComprobante')->latest()->take(5)->get();
        foreach ($ventas as $venta) {
            $tipo = $venta->tipoComprobante ? $venta->tipoComprobante->descripcion : 'Sin tipo';
            $this->line("  ID:{$venta->id_venta} | {$venta->serie}-{$venta->numero} | {$tipo} | S/{$venta->total}");
        }
        
        $this->line('');
        $this->comment('💡 Para probar PDFs, usa las URLs:');
        $this->line('   Ver: /pdf/comprobante/{id}/view');
        $this->line('   Descargar: /pdf/comprobante/{id}/download');
        
        return 0;
    }
}