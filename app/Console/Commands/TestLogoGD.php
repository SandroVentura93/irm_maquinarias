<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestLogoGD extends Command
{
    protected $signature = 'test:logo-gd';
    protected $description = 'Verificar que GD y el logo PNG funcionan correctamente';

    public function handle()
    {
        $this->info('🔍 Verificando configuración de logo...');
        
        // Verificar GD
        $gdLoaded = extension_loaded('gd');
        $this->info("GD Extension: " . ($gdLoaded ? '✅ Habilitada' : '❌ No disponible'));
        
        // Verificar logo
        $logoPath = public_path('images/logo.png');
        $logoExists = file_exists($logoPath);
        $this->info("Logo archivo: " . ($logoExists ? '✅ Existe' : '❌ No encontrado'));
        
        if ($logoExists) {
            $fileInfo = getimagesize($logoPath);
            $this->info("Dimensiones: {$fileInfo[0]} x {$fileInfo[1]} px");
            $this->info("Tipo: {$fileInfo['mime']}");
            $this->info("Tamaño: " . round(filesize($logoPath) / 1024, 2) . " KB");
        }
        
        // Intentar crear base64
        if ($gdLoaded && $logoExists) {
            try {
                $content = file_get_contents($logoPath);
                $base64 = base64_encode($content);
                $this->info("Base64: ✅ Generado correctamente (" . strlen($base64) . " caracteres)");
                
                // Mostrar los primeros caracteres
                $this->line("Inicio: " . substr($base64, 0, 50) . "...");
                
                $this->success('🎉 ¡Logo PNG listo para usar en PDFs!');
            } catch (Exception $e) {
                $this->error("Error generando base64: " . $e->getMessage());
            }
        } else {
            $this->warn('⚠️ No se puede procesar el PNG sin GD');
        }
        
        return 0;
    }
}