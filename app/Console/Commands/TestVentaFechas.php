<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Venta;

class TestVentaFechas extends Command
{
    protected $signature = 'test:venta-fechas';
    protected $description = 'Verificar que las fechas de ventas funcionen correctamente';

    public function handle()
    {
        $this->info('🧪 VERIFICANDO MANEJO DE FECHAS EN VENTAS');
        $this->line('');
        
        try {
            // Probar consulta básica
            $this->info('1️⃣ Probando consulta básica...');
            $ventaCount = Venta::count();
            $this->line("   ✅ Total de ventas: {$ventaCount}");
            
            // Probar consulta con select específico
            $this->info('2️⃣ Probando select con fecha...');
            $ventas = Venta::select('id_venta', 'serie', 'numero', 'fecha', 'total')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
                
            $this->line("   ✅ Consulta exitosa, {$ventas->count()} ventas obtenidas");
            
            // Mostrar detalles de ventas
            $this->info('3️⃣ Detalles de últimas ventas:');
            foreach ($ventas as $venta) {
                $fecha = $venta->fecha ? $venta->fecha->format('d/m/Y H:i') : 'Sin fecha';
                $this->line("   • Venta {$venta->id_venta}: {$venta->serie}-{$venta->numero} - {$fecha} - S/ {$venta->total}");
            }
            
            // Probar con relaciones
            $this->info('4️⃣ Probando con relaciones...');
            $ventaConRelaciones = Venta::with(['cliente', 'tipoComprobante'])
                ->select('id_venta', 'id_cliente', 'id_tipo_comprobante', 'serie', 'numero', 'fecha', 'total')
                ->first();
                
            if ($ventaConRelaciones) {
                $fecha = $ventaConRelaciones->fecha ? $ventaConRelaciones->fecha->format('d/m/Y H:i') : 'Sin fecha';
                $cliente = $ventaConRelaciones->cliente->nombre ?? 'Sin cliente';
                $tipo = $ventaConRelaciones->tipoComprobante->descripcion ?? 'Sin tipo';
                
                $this->line("   ✅ Venta con relaciones:");
                $this->line("      ID: {$ventaConRelaciones->id_venta}");
                $this->line("      Cliente: {$cliente}");
                $this->line("      Tipo: {$tipo}");
                $this->line("      Fecha: {$fecha}");
                $this->line("      Total: S/ {$ventaConRelaciones->total}");
            }
            
            $this->line('');
            $this->comment('🎉 ¡VERIFICACIÓN DE FECHAS EXITOSA!');
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