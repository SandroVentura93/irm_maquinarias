<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Venta;

class DebugVenta extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:venta {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug venta details';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $id = $this->argument('id');
        
        $venta = Venta::with(['cliente', 'detalleVentas.producto', 'vendedor'])->find($id);

        if($venta) {
            $this->info("Venta encontrada: " . $venta->serie . "-" . $venta->numero);
            $this->info("Cliente: " . ($venta->cliente ? $venta->cliente->nombre : 'Sin cliente'));
            $this->info("Detalles: " . $venta->detalleVentas->count());
            
            foreach($venta->detalleVentas as $detalle) {
                $this->line("- Producto: " . ($detalle->producto ? $detalle->producto->descripcion : 'Sin producto'));
                $this->line("  Código: " . ($detalle->producto ? $detalle->producto->codigo : 'Sin código'));
                $this->line("  Cantidad: " . $detalle->cantidad);
                $this->line("  Precio: " . $detalle->precio_unitario);
                $this->line("  Total: " . $detalle->total);
                if($detalle->producto) {
                    $this->line("  Stock actual: " . $detalle->producto->stock_actual);
                    $this->line("  Stock después: " . ($detalle->producto->stock_actual + $detalle->cantidad));
                } else {
                    $this->error("  ¡PRODUCTO NO CARGADO!");
                }
                $this->line("---");
            }
        } else {
            $this->error("Venta no encontrada");
        }
        
        return 0;
    }
}
