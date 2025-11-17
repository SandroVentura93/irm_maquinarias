<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class OptimizeSystem extends Command
{
    protected $signature = 'system:optimize {--force : Forzar limpieza completa}';
    protected $description = '⚡ Optimizar y limpiar el sistema completo';

    public function handle()
    {
        $this->info('🚀 INICIANDO OPTIMIZACIÓN DEL SISTEMA');
        $this->line('');
        
        $force = $this->option('force');
        
        // 1. Limpiar cache de aplicación
        $this->info('1️⃣ Limpiando cache de aplicación...');
        Cache::flush();
        $this->line('   ✅ Cache de aplicación limpiado');
        
        // 2. Limpiar cache de configuración
        $this->info('2️⃣ Optimizando configuración...');
        Artisan::call('config:clear');
        if (!$force) {
            Artisan::call('config:cache');
        }
        $this->line('   ✅ Configuración optimizada');
        
        // 3. Limpiar cache de rutas
        $this->info('3️⃣ Optimizando rutas...');
        Artisan::call('route:clear');
        if (!$force) {
            Artisan::call('route:cache');
        }
        $this->line('   ✅ Rutas optimizadas');
        
        // 4. Limpiar cache de vistas
        $this->info('4️⃣ Optimizando vistas...');
        Artisan::call('view:clear');
        if (!$force) {
            Artisan::call('view:cache');
        }
        $this->line('   ✅ Vistas optimizadas');
        
        // 5. Optimizar autoload
        $this->info('5️⃣ Optimizando autoload...');
        Artisan::call('optimize');
        $this->line('   ✅ Autoload optimizado');
        
        // 6. Limpiar logs antiguos (opcional)
        if ($force) {
            $this->info('6️⃣ Limpiando logs antiguos...');
            $logPath = storage_path('logs');
            $files = glob($logPath . '/*.log');
            $cleaned = 0;
            
            foreach ($files as $file) {
                if (filemtime($file) < strtotime('-7 days')) {
                    unlink($file);
                    $cleaned++;
                }
            }
            
            $this->line("   ✅ {$cleaned} archivos de log antiguos eliminados");
        }
        
        // 7. Mostrar información del sistema
        $this->line('');
        $this->info('📊 ESTADO ACTUAL DEL SISTEMA:');
        
        // Memoria PHP
        $memoryUsage = round(memory_get_usage(true) / 1024 / 1024, 2);
        $memoryLimit = ini_get('memory_limit');
        $this->line("   🧠 Memoria PHP: {$memoryUsage} MB (Límite: {$memoryLimit})");
        
        // Espacio en disco
        $diskFree = round(disk_free_space('.') / 1024 / 1024 / 1024, 2);
        $diskTotal = round(disk_total_space('.') / 1024 / 1024 / 1024, 2);
        $this->line("   💾 Espacio libre: {$diskFree} GB de {$diskTotal} GB");
        
        // Extensions críticas
        $extensions = ['gd', 'pdo_mysql', 'mbstring', 'openssl'];
        $this->line("   🔧 Extensions PHP:");
        foreach ($extensions as $ext) {
            $status = extension_loaded($ext) ? '✅' : '❌';
            $this->line("      {$status} {$ext}");
        }
        
        // Cache stats
        try {
            $cacheSize = count(Cache::getStore()->getRedis()->keys('*'));
            $this->line("   🗄️  Entradas en cache: {$cacheSize}");
        } catch (\Exception $e) {
            $this->line("   🗄️  Cache: Configurado (file driver)");
        }
        
        $this->line('');
        $this->comment('🎉 ¡SISTEMA OPTIMIZADO EXITOSAMENTE!');
        
        if (!$force) {
            $this->line('');
            $this->warn('💡 Tip: Use --force para limpieza más profunda');
        }
        
        return 0;
    }
}