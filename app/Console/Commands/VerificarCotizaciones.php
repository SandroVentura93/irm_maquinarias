<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Venta;
use App\Models\TipoComprobante;
use Illuminate\Support\Facades\DB;

class VerificarCotizaciones extends Command
{
    protected $signature = 'verify:cotizaciones';
    protected $description = 'Verificar y rectificar IDs y numeración de cotizaciones';

    public function handle()
    {
        $this->info('📋 VERIFICANDO COTIZACIONES');
        
        try {
            // 1. Verificar tipo de comprobante para cotizaciones
            $this->line("\n1️⃣ Verificando tipo de comprobante 'Cotización':");
            
            $tipoCotizacion = TipoComprobante::where('descripcion', 'Cotización')->first();
            if ($tipoCotizacion) {
                $this->info("   ✅ Tipo Cotización encontrado:");
                $this->line("      • ID: {$tipoCotizacion->id_tipo_comprobante}");
                $this->line("      • Descripción: {$tipoCotizacion->descripcion}");
                $this->line("      • Código SUNAT: {$tipoCotizacion->codigo_sunat}");
            } else {
                $this->error("   ❌ No se encontró el tipo de comprobante 'Cotización'");
                return;
            }
            
            // 2. Buscar todas las cotizaciones
            $this->line("\n2️⃣ Buscando todas las cotizaciones:");
            
            $cotizaciones = Venta::where('id_tipo_comprobante', $tipoCotizacion->id_tipo_comprobante)
                ->orderBy('id_venta')
                ->get();
                
            $this->info("   📊 Total de cotizaciones encontradas: {$cotizaciones->count()}");
            
            if ($cotizaciones->count() === 0) {
                $this->warn("   ⚠️  No hay cotizaciones en el sistema");
                return;
            }
            
            // 3. Verificar detalles de cada cotización
            $this->line("\n3️⃣ Detalles de cotizaciones:");
            $problemasEncontrados = [];
            
            foreach ($cotizaciones as $index => $cotizacion) {
                $numero = $index + 1;
                $this->line("   📄 Cotización #{$numero}:");
                $this->line("      • ID Venta: {$cotizacion->id_venta}");
                $this->line("      • Serie: {$cotizacion->serie}");
                $this->line("      • Número: {$cotizacion->numero}");
                $this->line("      • Serie-Número: {$cotizacion->serie_numero}");
                $this->line("      • Fecha: {$cotizacion->fecha}");
                $this->line("      • Total: S/ {$cotizacion->total}");
                
                // Verificar problemas comunes
                if (empty($cotizacion->serie)) {
                    $problemasEncontrados[] = "Cotización ID {$cotizacion->id_venta}: Serie vacía";
                }
                
                if (empty($cotizacion->numero)) {
                    $problemasEncontrados[] = "Cotización ID {$cotizacion->id_venta}: Número vacío";
                }
                
                if (empty($cotizacion->serie_numero)) {
                    $problemasEncontrados[] = "Cotización ID {$cotizacion->id_venta}: Serie-Número vacío";
                }
                
                $this->line("");
            }
            
            // 4. Verificar numeración consecutiva
            $this->line("4️⃣ Verificando numeración consecutiva:");
            
            $numerosEsperados = [];
            $numerosReales = [];
            
            foreach ($cotizaciones as $index => $cotizacion) {
                $numeroEsperado = $index + 1;
                $numeroReal = (int) str_replace(['COT-', '0'], '', $cotizacion->numero);
                
                $numerosEsperados[] = $numeroEsperado;
                $numerosReales[] = $numeroReal ?: $numeroEsperado;
                
                if ($numeroReal !== $numeroEsperado) {
                    $problemasEncontrados[] = "Cotización ID {$cotizacion->id_venta}: Número esperado {$numeroEsperado}, encontrado {$numeroReal}";
                }
            }
            
            // 5. Reportar problemas
            if (!empty($problemasEncontrados)) {
                $this->line("\n⚠️  PROBLEMAS ENCONTRADOS:");
                foreach ($problemasEncontrados as $problema) {
                    $this->warn("   • {$problema}");
                }
                
                // Ofrecer corrección
                $this->line("\n🔧 ¿Desea corregir automáticamente estos problemas?");
                $this->line("   Se renumerarán las cotizaciones con formato correcto COT-00000001, COT-00000002, etc.");
                
            } else {
                $this->info("\n✅ Todas las cotizaciones tienen numeración correcta");
            }
            
            // 6. Verificar cliente asociado
            $this->line("\n5️⃣ Verificando clientes asociados:");
            $cotizacionesSinCliente = $cotizaciones->where('id_cliente', null)->count();
            
            if ($cotizacionesSinCliente > 0) {
                $this->warn("   ⚠️  {$cotizacionesSinCliente} cotizaciones sin cliente asociado");
            } else {
                $this->info("   ✅ Todas las cotizaciones tienen cliente asociado");
            }
            
            $this->info("\n📋 VERIFICACIÓN DE COTIZACIONES COMPLETADA");
            
        } catch (\Exception $e) {
            $this->error("❌ Error en verificación:");
            $this->line("   {$e->getMessage()}");
            $this->line("   Línea: {$e->getLine()}");
            $this->line("   Archivo: {$e->getFile()}");
        }
    }
}