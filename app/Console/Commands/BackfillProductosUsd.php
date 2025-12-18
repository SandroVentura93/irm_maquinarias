<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Producto;

class BackfillProductosUsd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'productos:backfill-usd {--tipo-cambio=3.8}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rellena precio_compra_usd y precio_venta_usd a partir de precios en PEN y un tipo de cambio';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tc = (float) $this->option('tipo-cambio');
        if ($tc <= 0) {
            $this->error('El tipo de cambio debe ser mayor que 0');
            return 1;
        }

        $this->info("Iniciando backfill con tipo de cambio: {$tc}");

        Producto::chunkById(200, function($productos) use ($tc) {
            foreach ($productos as $p) {
                $p->precio_compra_usd = $tc ? round(($p->precio_compra ?? 0) / $tc, 2) : 0.00;
                $p->precio_venta_usd = $tc ? round(($p->precio_venta ?? 0) / $tc, 2) : 0.00;
                $p->save();
            }
            $this->info("Procesadas: " . count($productos));
        });

        $this->info('Backfill completado');
        return 0;
    }
}
