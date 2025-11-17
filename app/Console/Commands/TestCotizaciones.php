<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Venta;
use App\Models\TipoComprobante;

class TestCotizaciones extends Command
{
    protected $signature = 'test:cotizaciones';
    protected $description = 'Verificar cotizaciones del sistema';

    public function handle()
    {
        $this->info('🔍 VERIFICANDO COTIZACIONES');
        
        try {
            // Buscar tipo de comprobante para cotizaciones
            $tipoCotizacion = TipoComprobante::where('descripcion', 'LIKE', '%Cotiz%')
                ->orWhere('codigo_sunat', 'CT')
                ->first();
                
            if (!$tipoCotizacion) {
                $this->warn('No se encontró el tipo de comprobante para cotizaciones');
                
                // Mostrar todos los tipos disponibles
                $this->line('Tipos de comprobante disponibles:');
                $tipos = TipoComprobante::all();
                foreach ($tipos as $tipo) {
                    $this->line("  • {$tipo->descripcion} (ID: {$tipo->id_tipo_comprobante}, Código: {$tipo->codigo_sunat})");
                }
                return;
            }
            
            $this->info("✅ Tipo cotización encontrado: {$tipoCotizacion->descripcion}");
            
            // Buscar cotizaciones
            $cotizaciones = Venta::where('id_tipo_comprobante', $tipoCotizacion->id_tipo_comprobante)
                ->with('cliente')
                ->orderBy('id_venta')
                ->get();
                
            $this->info("📊 Total cotizaciones: {$cotizaciones->count()}");
            
            if ($cotizaciones->count() > 0) {
                $this->line("\n📋 Detalles de cotizaciones:");
                
                foreach ($cotizaciones as $cotizacion) {
                    $cliente = $cotizacion->cliente ? $cotizacion->cliente->nombre : 'Sin cliente';
                    
                    $this->line("  • ID: {$cotizacion->id_venta}");
                    $this->line("    Serie: '{$cotizacion->serie}'");
                    $this->line("    Número: '{$cotizacion->numero}'");
                    $this->line("    Serie-Número: '{$cotizacion->serie_numero}'");
                    $this->line("    Cliente: {$cliente}");
                    $this->line("    Total: S/ {$cotizacion->total}");
                    $this->line("    Fecha: {$cotizacion->fecha}");
                    $this->line("");
                }
                
                // Verificar problemas
                $problemasEncontrados = [];
                
                foreach ($cotizaciones as $cotizacion) {
                    if (empty($cotizacion->serie_numero)) {
                        $problemasEncontrados[] = "ID {$cotizacion->id_venta}: serie_numero vacío";
                    }
                    
                    if (!str_contains($cotizacion->serie_numero, 'COT')) {
                        $problemasEncontrados[] = "ID {$cotizacion->id_venta}: formato incorrecto '{$cotizacion->serie_numero}'";
                    }
                }
                
                if (!empty($problemasEncontrados)) {
                    $this->warn("\n⚠️ Problemas encontrados:");
                    foreach ($problemasEncontrados as $problema) {
                        $this->line("  • {$problema}");
                    }
                } else {
                    $this->info("\n✅ Todas las cotizaciones tienen formato correcto");
                }
            }
            
        } catch (\Exception $e) {
            $this->error("❌ Error: {$e->getMessage()}");
        }
    }
}