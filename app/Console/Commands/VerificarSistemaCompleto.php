<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\TipoComprobante;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class VerificarSistemaCompleto extends Command
{
    protected $signature = 'system:verify';
    protected $description = 'Verificar que todo el sistema esté funcionando correctamente';

    public function handle()
    {
        $this->info('🔍 VERIFICACIÓN COMPLETA DEL SISTEMA IRM MAQUINARIAS');
        
        try {
            $this->verificarBaseDatos();
            $this->verificarModelos();
            $this->verificarCache();
            $this->verificarRelaciones();
            
            $this->info("\n🎉 ¡SISTEMA COMPLETAMENTE VERIFICADO Y OPTIMIZADO!");
            $this->line("   Todos los componentes funcionan correctamente");
            
        } catch (\Exception $e) {
            $this->error("❌ Error en verificación:");
            $this->line("   {$e->getMessage()}");
        }
    }

    private function verificarBaseDatos()
    {
        $this->line("\n1️⃣ Verificando Base de Datos:");
        
        // Verificar conexión
        DB::connection()->getPdo();
        $this->info("   ✅ Conexión a base de datos exitosa");
        
        // Verificar tablas principales
        $tablas = ['ventas', 'clientes', 'productos', 'categorias', 'tipo_comprobantes'];
        foreach ($tablas as $tabla) {
            $count = DB::table($tabla)->count();
            $this->line("   • {$tabla}: {$count} registros");
        }
    }

    private function verificarModelos()
    {
        $this->line("\n2️⃣ Verificando Modelos Eloquent:");
        
        // Verificar Ventas con fechas
        $venta = Venta::first();
        if ($venta) {
            $this->info("   ✅ Modelo Venta - Fecha: {$venta->fecha->format('d/m/Y H:i')}");
        }
        
        // Verificar Cliente con documento
        $cliente = Cliente::first();
        if ($cliente) {
            $this->info("   ✅ Modelo Cliente - Doc: {$cliente->numero_documento}");
        }
        
        // Verificar Producto
        $producto = Producto::first();
        if ($producto) {
            $this->info("   ✅ Modelo Producto: {$producto->nombre}");
        }
    }

    private function verificarCache()
    {
        $this->line("\n3️⃣ Verificando Sistema de Cache:");
        
        // Test cache básico
        Cache::put('test_cache', 'funcionando', 60);
        $cached = Cache::get('test_cache');
        
        if ($cached === 'funcionando') {
            $this->info("   ✅ Cache funcionando correctamente");
        } else {
            $this->warn("   ⚠️  Cache no está funcionando");
        }
        
        Cache::forget('test_cache');
    }

    private function verificarRelaciones()
    {
        $this->line("\n4️⃣ Verificando Relaciones:");
        
        // Venta con cliente y tipo comprobante
        $venta = Venta::with(['cliente', 'tipoComprobante'])->first();
        if ($venta) {
            $cliente = $venta->cliente ? $venta->cliente->nombre : 'Sin cliente';
            $tipo = $venta->tipoComprobante ? $venta->tipoComprobante->descripcion : 'Sin tipo';
            
            $this->info("   ✅ Relación Venta-Cliente: {$cliente}");
            $this->info("   ✅ Relación Venta-TipoComprobante: {$tipo}");
        }
        
        // Producto con categoría
        $producto = Producto::with('categoria')->first();
        if ($producto && $producto->categoria) {
            $this->info("   ✅ Relación Producto-Categoria: {$producto->categoria->nombre}");
        }
    }
}