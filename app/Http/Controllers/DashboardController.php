<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Estadísticas básicas
            $clientes = \App\Models\Cliente::count();
            $productos = \App\Models\Producto::count();
            $monedas = \App\Models\Moneda::count();
            $alertas = \App\Models\Producto::where('stock_actual', '<', 10)->count();
            
            // Estadísticas de ventas
            $ventas_hoy = \App\Models\Venta::whereDate('fecha', today())->count();
            $ventas_mes = \App\Models\Venta::whereMonth('fecha', now()->month)
                                        ->whereYear('fecha', now()->year)
                                        ->count();
            $ventas_total = \App\Models\Venta::count();
            
            // Estadísticas financieras
            $ingresos_hoy = \App\Models\Venta::whereDate('fecha', today())
                                           ->where('xml_estado', '!=', 'ANULADO')
                                           ->sum('total') ?? 0;
            
            $ingresos_mes = \App\Models\Venta::whereMonth('fecha', now()->month)
                                           ->whereYear('fecha', now()->year)
                                           ->where('xml_estado', '!=', 'ANULADO')
                                           ->sum('total') ?? 0;
            
            $ingresos_total = \App\Models\Venta::where('xml_estado', '!=', 'ANULADO')
                                             ->sum('total') ?? 0;
            
            // Estadísticas por tipo de comprobante
            $facturas = \App\Models\Venta::where('id_tipo_comprobante', 1)->count();
            $boletas = \App\Models\Venta::where('id_tipo_comprobante', 2)->count();
            $cotizaciones = \App\Models\Venta::where('id_tipo_comprobante', 8)->count();
            
            // Top productos más vendidos
            $top_productos = \App\Models\DetalleVenta::select('id_producto', \DB::raw('SUM(cantidad) as total_vendido'), \DB::raw('SUM(cantidad * precio_unitario) as ingresos_generados'))
                                                    ->with('producto')
                                                    ->groupBy('id_producto')
                                                    ->orderBy('total_vendido', 'desc')
                                                    ->limit(5)
                                                    ->get();
            
            // Top clientes por compras
            $top_clientes = \App\Models\Venta::select('id_cliente', \DB::raw('COUNT(*) as total_compras'), \DB::raw('SUM(total) as total_gastado'))
                                           ->with('cliente')
                                           ->where('xml_estado', '!=', 'ANULADO')
                                           ->groupBy('id_cliente')
                                           ->orderBy('total_gastado', 'desc')
                                           ->limit(5)
                                           ->get();
            
            // Productos con stock bajo (menos de 10 unidades)
            $productos_stock_bajo = \App\Models\Producto::where('stock_actual', '<', 10)
                                                      ->where('stock_actual', '>', 0)
                                                      ->orderBy('stock_actual', 'asc')
                                                      ->limit(10)
                                                      ->get();
            
            // Productos sin stock
            $productos_sin_stock = \App\Models\Producto::where('stock_actual', '<=', 0)->count();
            
            // Ventas por estado
            $ventas_pendientes = \App\Models\Venta::where('xml_estado', 'PENDIENTE')->count();
            $ventas_aceptadas = \App\Models\Venta::where('xml_estado', 'ACEPTADO')->count();
            $ventas_anuladas = \App\Models\Venta::where('xml_estado', 'ANULADO')->count();
            
            // Gráfico de ventas últimos 7 días
            $ventas_semana = [];
            for ($i = 6; $i >= 0; $i--) {
                $fecha = now()->subDays($i);
                $ventas_semana[] = [
                    'fecha' => $fecha->format('d/m'),
                    'ventas' => \App\Models\Venta::whereDate('fecha', $fecha)->count(),
                    'ingresos' => \App\Models\Venta::whereDate('fecha', $fecha)
                                                 ->where('xml_estado', '!=', 'ANULADO')
                                                 ->sum('total') ?? 0
                ];
            }
            
            // Estadísticas mensuales (últimos 6 meses)
            $estadisticas_mensuales = [];
            for ($i = 5; $i >= 0; $i--) {
                $mes = now()->subMonths($i);
                $estadisticas_mensuales[] = [
                    'mes' => $mes->format('M Y'),
                    'ventas' => \App\Models\Venta::whereMonth('fecha', $mes->month)
                                               ->whereYear('fecha', $mes->year)
                                               ->count(),
                    'ingresos' => \App\Models\Venta::whereMonth('fecha', $mes->month)
                                                 ->whereYear('fecha', $mes->year)
                                                 ->where('xml_estado', '!=', 'ANULADO')
                                                 ->sum('total') ?? 0
                ];
            }
            
        } catch (\Exception $e) {
            // Si hay error, usar valores por defecto
            $clientes = 0;
            $productos = 0;
            $monedas = 0;
            $alertas = 0;
            $ventas_hoy = 0;
            $ventas_mes = 0;
            $ventas_total = 0;
            $ingresos_hoy = 0;
            $ingresos_mes = 0;
            $ingresos_total = 0;
            $facturas = 0;
            $boletas = 0;
            $cotizaciones = 0;
            $top_productos = collect();
            $top_clientes = collect();
            $productos_stock_bajo = collect();
            $productos_sin_stock = 0;
            $ventas_pendientes = 0;
            $ventas_aceptadas = 0;
            $ventas_anuladas = 0;
            $ventas_semana = [];
            $estadisticas_mensuales = [];
        }

        return view('dashboard.index', compact(
            'clientes', 'productos', 'monedas', 'alertas', 
            'ventas_hoy', 'ventas_mes', 'ventas_total',
            'ingresos_hoy', 'ingresos_mes', 'ingresos_total',
            'facturas', 'boletas', 'cotizaciones',
            'top_productos', 'top_clientes', 'productos_stock_bajo', 'productos_sin_stock',
            'ventas_pendientes', 'ventas_aceptadas', 'ventas_anuladas',
            'ventas_semana', 'estadisticas_mensuales'
        ));
    }
}
