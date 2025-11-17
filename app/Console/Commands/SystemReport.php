<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Venta;
use App\Models\TipoComprobante;
use Illuminate\Support\Facades\File;

class SystemReport extends Command
{
    protected $signature = 'system:report';
    protected $description = 'Generar reporte completo del sistema PDF';

    public function handle()
    {
        $this->info('📋 REPORTE COMPLETO DEL SISTEMA PDF');
        $this->line('============================================');
        $this->line('');
        
        // 1. Estado de Templates PDF
        $this->info('1️⃣ TEMPLATES PDF:');
        $templatePath = resource_path('views/comprobantes');
        $templates = [
            'factura.blade.php',
            'boleta.blade.php', 
            'nota_credito.blade.php',
            'nota_debito.blade.php',
            'ticket.blade.php',
            'recibo_honorarios.blade.php',
            'cotizacion.blade.php',
            'guia_remision.blade.php',
            'comprobante_retencion.blade.php'
        ];
        
        foreach ($templates as $template) {
            $exists = File::exists("{$templatePath}/{$template}");
            $status = $exists ? '✅' : '❌';
            $this->line("  {$status} {$template}");
        }
        
        // 2. Logo System
        $this->line('');
        $this->info('2️⃣ SISTEMA DE LOGO:');
        $logoPartial = resource_path('views/comprobantes/partials/logo.blade.php');
        $logoFile = public_path('images/logo.png');
        
        $partialExists = File::exists($logoPartial) ? '✅' : '❌';
        $logoExists = File::exists($logoFile) ? '✅' : '❌';
        
        $this->line("  {$partialExists} Partial logo.blade.php");
        $this->line("  {$logoExists} Logo PNG file");
        
        if ($logoExists) {
            $size = File::size($logoFile);
            $this->line("      Tamaño: " . number_format($size / 1024, 1) . " KB");
        }
        
        // 3. Base de datos
        $this->line('');
        $this->info('3️⃣ CONFIGURACIÓN BASE DE DATOS:');
        $tipos = TipoComprobante::all();
        $this->line("  📊 Tipos de comprobante registrados: {$tipos->count()}");
        
        foreach ($tipos as $tipo) {
            $ventasCount = Venta::whereHas('tipoComprobante', function($q) use ($tipo) {
                $q->where('codigo_sunat', $tipo->codigo_sunat);
            })->count();
            
            $this->line("    {$tipo->codigo_sunat} - {$tipo->descripcion} ({$ventasCount} ventas)");
        }
        
        // 4. Extensiones PHP requeridas
        $this->line('');
        $this->info('4️⃣ EXTENSIONES PHP:');
        
        $extensions = [
            'gd' => 'Procesamiento de imágenes (logo)',
            'mbstring' => 'Strings multibyte',
            'dom' => 'Manipulación XML/HTML',
            'xml' => 'Parser XML'
        ];
        
        foreach ($extensions as $ext => $description) {
            $loaded = extension_loaded($ext) ? '✅' : '❌';
            $this->line("  {$loaded} {$ext} - {$description}");
        }
        
        // 5. URLs de prueba
        $this->line('');
        $this->info('5️⃣ URLS DE PRUEBA DISPONIBLES:');
        
        $ventasPorTipo = [];
        foreach ($tipos as $tipo) {
            $venta = Venta::whereHas('tipoComprobante', function($q) use ($tipo) {
                $q->where('codigo_sunat', $tipo->codigo_sunat);
            })->first();
            
            if ($venta) {
                $this->line("  📄 {$tipo->descripcion}:");
                $this->line("      http://127.0.0.1:8001/pdf/comprobante/{$venta->id_venta}/view");
                $this->line("      http://127.0.0.1:8001/pdf/comprobante/{$venta->id_venta}/download");
                $this->line('');
            }
        }
        
        // 6. Resumen final
        $this->line('');
        $this->info('6️⃣ RESUMEN DEL SISTEMA:');
        
        $templatesOk = count(array_filter($templates, function($t) use ($templatePath) {
            return File::exists("{$templatePath}/{$t}");
        }));
        
        $logoOk = File::exists($logoPartial) && File::exists($logoFile);
        $gdOk = extension_loaded('gd');
        $ventasTotal = Venta::count();
        
        $this->line("  📁 Templates: {$templatesOk}/" . count($templates) . " disponibles");
        $this->line("  🖼️  Logo: " . ($logoOk ? '✅ Configurado' : '❌ No configurado'));
        $this->line("  🔧 PHP GD: " . ($gdOk ? '✅ Habilitado' : '❌ Deshabilitado'));
        $this->line("  📊 Ventas: {$ventasTotal} registros");
        $this->line("  🎯 Tipos: {$tipos->count()} configurados");
        
        $this->line('');
        if ($templatesOk === count($templates) && $logoOk && $gdOk) {
            $this->comment('🎉 SISTEMA PDF COMPLETAMENTE FUNCIONAL');
            $this->comment('   Todos los componentes están correctamente configurados');
        } else {
            $this->error('⚠️  REVISAR CONFIGURACIÓN');
            $this->error('   Algunos componentes necesitan atención');
        }
        
        return 0;
    }
}