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
            $alertas = \App\Models\Producto::whereColumn('stock_actual', '<', 'stock_minimo')
                                             ->where('stock_actual', '>', 0)
                                             ->count();
            
            // Estadísticas de ventas (solo aceptadas y pendientes)
            $ventas_hoy = \App\Models\Venta::whereDate('fecha', today())
                                        ->whereIn('xml_estado', ['ACEPTADO', 'PENDIENTE'])
                                        ->count();
            $ventas_mes = \App\Models\Venta::whereMonth('fecha', now()->month)
                                        ->whereYear('fecha', now()->year)
                                        ->whereIn('xml_estado', ['ACEPTADO', 'PENDIENTE'])
                                        ->count();
            $ventas_total = \App\Models\Venta::whereIn('xml_estado', ['ACEPTADO', 'PENDIENTE'])->count();
            
            // Estadísticas financieras
            // Para ACEPTADAS: sumar el total completo
            // Para PENDIENTES: sumar solo lo cancelado (total - saldo)
            $ingresos_hoy_aceptadas = \App\Models\Venta::whereDate('fecha', today())
                                           ->where('xml_estado', 'ACEPTADO')
                                           ->sum('total') ?? 0;
            $ingresos_hoy_pendientes = \DB::table('ventas')
                                           ->whereDate('fecha', today())
                                           ->where('xml_estado', 'PENDIENTE')
                                           ->sum(\DB::raw('total - saldo')) ?? 0;
            $ingresos_hoy = $ingresos_hoy_aceptadas + $ingresos_hoy_pendientes;
            
            $ingresos_mes_aceptadas = \App\Models\Venta::whereMonth('fecha', now()->month)
                                           ->whereYear('fecha', now()->year)
                                           ->where('xml_estado', 'ACEPTADO')
                                           ->sum('total') ?? 0;
            $ingresos_mes_pendientes = \DB::table('ventas')
                                           ->whereMonth('fecha', now()->month)
                                           ->whereYear('fecha', now()->year)
                                           ->where('xml_estado', 'PENDIENTE')
                                           ->sum(\DB::raw('total - saldo')) ?? 0;
            $ingresos_mes = $ingresos_mes_aceptadas + $ingresos_mes_pendientes;
            
            $ingresos_total_aceptadas = \App\Models\Venta::where('xml_estado', 'ACEPTADO')
                                             ->sum('total') ?? 0;
            $ingresos_total_pendientes = \DB::table('ventas')
                                             ->where('xml_estado', 'PENDIENTE')
                                             ->sum(\DB::raw('total - saldo')) ?? 0;
            $ingresos_total = $ingresos_total_aceptadas + $ingresos_total_pendientes;
            
            // Estadísticas por tipo de comprobante (solo aceptadas y pendientes)
            $facturas = \App\Models\Venta::where('id_tipo_comprobante', 1)
                                        ->whereIn('xml_estado', ['ACEPTADO', 'PENDIENTE'])
                                        ->count();
            $boletas = \App\Models\Venta::where('id_tipo_comprobante', 2)
                                       ->whereIn('xml_estado', ['ACEPTADO', 'PENDIENTE'])
                                       ->count();
            $tickets = \App\Models\Venta::where('id_tipo_comprobante', 3)
                                       ->whereIn('xml_estado', ['ACEPTADO', 'PENDIENTE'])
                                       ->count();
            $cotizaciones = \App\Models\Venta::where('id_tipo_comprobante', 8)
                                            ->whereIn('xml_estado', ['ACEPTADO', 'PENDIENTE'])
                                            ->count();
            
            // Top productos más vendidos
            $top_productos = \App\Models\DetalleVenta::select('id_producto', \DB::raw('SUM(cantidad) as total_vendido'), \DB::raw('SUM(cantidad * precio_unitario) as ingresos_generados'))
                                                    ->with('producto')
                                                    ->groupBy('id_producto')
                                                    ->orderBy('total_vendido', 'desc')
                                                    ->limit(5)
                                                    ->get();
            
            // Top clientes por compras
            // Para ACEPTADAS: total completo, para PENDIENTES: solo lo cancelado
            // Optimizado con SQL directo
            $top_clientes = \DB::table('ventas')
                               ->select(
                                   'ventas.id_cliente',
                                   \DB::raw('COUNT(*) as total_compras'),
                                   \DB::raw('SUM(CASE 
                                       WHEN ventas.xml_estado = "ACEPTADO" THEN ventas.total 
                                       WHEN ventas.xml_estado = "PENDIENTE" THEN ventas.total - ventas.saldo 
                                       ELSE 0 
                                   END) as total_gastado')
                               )
                               ->whereIn('ventas.xml_estado', ['ACEPTADO', 'PENDIENTE'])
                               ->groupBy('ventas.id_cliente')
                               ->orderByDesc('total_gastado')
                               ->limit(5)
                               ->get()
                               ->map(function($row) {
                                   $cliente = \App\Models\Cliente::find($row->id_cliente);
                                   return (object)[
                                       'cliente' => $cliente,
                                       'total_compras' => $row->total_compras,
                                       'total_gastado' => $row->total_gastado
                                   ];
                               });
            
            // Productos con stock bajo (menor al mínimo)
            $productos_stock_bajo = \App\Models\Producto::whereColumn('stock_actual', '<', 'stock_minimo')
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
            // Para ACEPTADAS: total completo, para PENDIENTES: solo lo cancelado
            // Optimizado con SQL directo
            $ventas_semana = [];
            for ($i = 6; $i >= 0; $i--) {
                $fecha = now()->subDays($i);
                
                $ingresos_aceptadas = \App\Models\Venta::whereDate('fecha', $fecha)
                                                 ->where('xml_estado', 'ACEPTADO')
                                                 ->sum('total') ?? 0;
                $ingresos_pendientes = \DB::table('ventas')
                                                 ->whereDate('fecha', $fecha)
                                                 ->where('xml_estado', 'PENDIENTE')
                                                 ->sum(\DB::raw('total - saldo')) ?? 0;
                
                $ventas_semana[] = [
                    'fecha' => $fecha->format('d/m'),
                    'ventas' => \App\Models\Venta::whereDate('fecha', $fecha)
                                                ->whereIn('xml_estado', ['ACEPTADO', 'PENDIENTE'])
                                                ->count(),
                    'ingresos' => $ingresos_aceptadas + $ingresos_pendientes
                ];
            }
            
            // Estadísticas mensuales (últimos 6 meses)
            // Para ACEPTADAS: total completo, para PENDIENTES: solo lo cancelado
            // Optimizado con SQL directo
            $estadisticas_mensuales = [];
            for ($i = 5; $i >= 0; $i--) {
                $mes = now()->subMonths($i);
                
                $ingresos_aceptadas = \App\Models\Venta::whereMonth('fecha', $mes->month)
                                                 ->whereYear('fecha', $mes->year)
                                                 ->where('xml_estado', 'ACEPTADO')
                                                 ->sum('total') ?? 0;
                $ingresos_pendientes = \DB::table('ventas')
                                                 ->whereMonth('fecha', $mes->month)
                                                 ->whereYear('fecha', $mes->year)
                                                 ->where('xml_estado', 'PENDIENTE')
                                                 ->sum(\DB::raw('total - saldo')) ?? 0;
                
                $estadisticas_mensuales[] = [
                    'mes' => $mes->format('M Y'),
                    'ventas' => \App\Models\Venta::whereMonth('fecha', $mes->month)
                                               ->whereYear('fecha', $mes->year)
                                               ->whereIn('xml_estado', ['ACEPTADO', 'PENDIENTE'])
                                               ->count(),
                    'ingresos' => $ingresos_aceptadas + $ingresos_pendientes
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
            $tickets = 0;
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
            'facturas', 'boletas', 'tickets', 'cotizaciones',
            'top_productos', 'top_clientes', 'productos_stock_bajo', 'productos_sin_stock',
            'ventas_pendientes', 'ventas_aceptadas', 'ventas_anuladas',
            'ventas_semana', 'estadisticas_mensuales'
        ));
    }
}
