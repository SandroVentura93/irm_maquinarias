<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ActualizarTipoCambio extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tipo-cambio:actualizar {--forzar : Forzar actualización ignorando caché}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza el tipo de cambio USD/PEN desde APIs externas';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Iniciando actualización del tipo de cambio USD/PEN...');
        
        try {
            // Si se fuerza, limpiar cache
            if ($this->option('forzar')) {
                Cache::forget('tipo_cambio_usd_pen');
                Cache::forget('tipo_cambio_usd_pen_info');
                $this->info('Cache limpiado - Forzando actualización...');
            }
            
            $tipoCambio = $this->obtenerTipoCambio();
            
            if ($tipoCambio) {
                $this->info("✅ Tipo de cambio actualizado exitosamente: S/ {$tipoCambio}");
                
                // Guardar en cache por 4 horas para comando automático
                Cache::put('tipo_cambio_usd_pen', $tipoCambio, 14400);
                
                return Command::SUCCESS;
            } else {
                $this->error('❌ No se pudo obtener el tipo de cambio de ninguna API');
                return Command::FAILURE;
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Error al actualizar tipo de cambio: ' . $e->getMessage());
            Log::error('Error en comando tipo-cambio:actualizar', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return Command::FAILURE;
        }
    }
    
    /**
     * Obtener tipo de cambio desde APIs externas
     */
    private function obtenerTipoCambio()
    {
        $apis = [
            'SUNAT' => function() {
                $this->line('🔍 Consultando SUNAT...');
                $fechaHoy = now()->format('Y-m-d');
                $response = Http::timeout(10)
                    ->withOptions(['verify' => false]) // Temporal para desarrollo
                    ->get("https://api.apis.net.pe/v1/tipo-cambio-sunat", [
                    'fecha' => $fechaHoy
                ]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['compra']) && $data['compra'] > 0) {
                        $tipoCambio = ($data['compra'] + $data['venta']) / 2;
                        $this->info("✅ SUNAT - Compra: {$data['compra']} | Venta: {$data['venta']} | Promedio: " . round($tipoCambio, 2));
                        
                        Cache::put('tipo_cambio_usd_pen_info', [
                            'fuente' => 'SUNAT',
                            'fecha_actualizacion' => now(),
                            'cache_hit' => false
                        ], 14400);
                        
                        return round($tipoCambio, 2);
                    }
                }
                return null;
            },
            
            'BCRP' => function() {
                $this->line('🔍 Consultando BCRP...');
                $response = Http::timeout(10)
                    ->withOptions(['verify' => false]) // Temporal para desarrollo
                    ->get('https://estadisticas.bcrp.gob.pe/estadisticas/series/api/PD04638PD/json');
                
                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['config']['series'][0]['values']) && !empty($data['config']['series'][0]['values'])) {
                        $ultimoValor = end($data['config']['series'][0]['values']);
                        if (isset($ultimoValor[1]) && $ultimoValor[1] > 0) {
                            $tipoCambio = floatval($ultimoValor[1]);
                            $this->info("✅ BCRP - Tipo de cambio: {$tipoCambio}");
                            
                            Cache::put('tipo_cambio_usd_pen_info', [
                                'fuente' => 'BCRP',
                                'fecha_actualizacion' => now(),
                                'cache_hit' => false
                            ], 14400);
                            
                            return round($tipoCambio, 2);
                        }
                    }
                }
                return null;
            },
            
            'ExchangeRate-API' => function() {
                $this->line('🔍 Consultando ExchangeRate-API...');
                $response = Http::timeout(10)
                    ->withOptions(['verify' => false]) // Temporal para desarrollo
                    ->get('https://api.exchangerate-api.com/v4/latest/USD');
                
                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['rates']['PEN']) && $data['rates']['PEN'] > 0) {
                        $tipoCambio = $data['rates']['PEN'];
                        $this->info("✅ ExchangeRate-API - Tipo de cambio: {$tipoCambio}");
                        
                        Cache::put('tipo_cambio_usd_pen_info', [
                            'fuente' => 'ExchangeRate-API',
                            'fecha_actualizacion' => now(),
                            'cache_hit' => false
                        ], 14400);
                        
                        return round($tipoCambio, 2);
                    }
                }
                return null;
            }
        ];
        
        // Intentar cada API en orden de prioridad
        foreach ($apis as $nombre => $api) {
            try {
                $resultado = $api();
                if ($resultado) {
                    return $resultado;
                }
                $this->warn("⚠️  {$nombre} no retornó datos válidos");
            } catch (\Exception $e) {
                $this->warn("⚠️  Error en {$nombre}: " . $e->getMessage());
            }
        }
        
        return null;
    }
}
