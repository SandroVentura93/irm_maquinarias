<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Venta;
use App\Models\Compra;

class ReportesController extends Controller
{
    // Exportar reporte anual en Excel
    public function exportarAnualExcel(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $months_data = [];
        for ($m = 1; $m <= 12; $m++) {
            $fecha_inicio = "$year-" . str_pad($m,2,'0',STR_PAD_LEFT) . "-01 00:00:00";
            $fecha_fin = date('Y-m-t', strtotime("$year-" . str_pad($m,2,'0',STR_PAD_LEFT) . "-01")) . ' 23:59:59';
            $total_ventas = \App\Models\Venta::whereBetween('fecha', [$fecha_inicio, $fecha_fin])->sum('total');
            $total_compras = \App\Models\Compra::whereBetween('fecha', [$fecha_inicio, $fecha_fin])->sum('total');
            $ganancia = $total_ventas - $total_compras;
            $cantidad_productos_vendidos = \App\Models\DetalleVenta::whereHas('venta', function($q) use ($fecha_inicio, $fecha_fin) {
                $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
            })->sum('cantidad');
            $cantidad_productos_comprados = \App\Models\DetalleCompra::whereHas('compra', function($q) use ($fecha_inicio, $fecha_fin) {
                $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
            })->sum('cantidad');
            $months_data[] = [
                'Mes' => date('F', mktime(0,0,0,$m,1)),
                'Total Ventas' => $total_ventas,
                'Total Compras' => $total_compras,
                'Ganancia' => $ganancia,
                'Productos Vendidos' => $cantidad_productos_vendidos,
                'Productos Comprados' => $cantidad_productos_comprados
            ];
        }
        return \Excel::download(new \App\Exports\ReporteDiarioExport($months_data, 'anual'), 'reporte_anual_' . $year . '.xlsx');
    }
    // Exportar reporte anual en PDF
    public function exportarAnualPdf(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $months_data = [];
        $labels = [];
        $ventas_chart = [];
        $compras_chart = [];
        $ganancias_chart = [];
        for ($m = 1; $m <= 12; $m++) {
            $fecha_inicio = "$year-" . str_pad($m,2,'0',STR_PAD_LEFT) . "-01 00:00:00";
            $fecha_fin = date('Y-m-t', strtotime("$year-" . str_pad($m,2,'0',STR_PAD_LEFT) . "-01")) . ' 23:59:59';
            $total_ventas = \App\Models\Venta::whereBetween('fecha', [$fecha_inicio, $fecha_fin])->sum('total');
            $total_compras = \App\Models\Compra::whereBetween('fecha', [$fecha_inicio, $fecha_fin])->sum('total');
            $ganancia = $total_ventas - $total_compras;
            $cantidad_productos_vendidos = \App\Models\DetalleVenta::whereHas('venta', function($q) use ($fecha_inicio, $fecha_fin) {
                $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
            })->sum('cantidad');
            $cantidad_productos_comprados = \App\Models\DetalleCompra::whereHas('compra', function($q) use ($fecha_inicio, $fecha_fin) {
                $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
            })->sum('cantidad');
            $ventas_por_producto = \App\Models\DetalleVenta::select('id_producto', \DB::raw('SUM(cantidad) as cantidad'), \DB::raw('SUM(total) as total'))
                ->whereHas('venta', function($q) use ($fecha_inicio, $fecha_fin) {
                    $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
                })
                ->groupBy('id_producto')
                ->with('producto')
                ->get();
            $compras_por_producto = \App\Models\DetalleCompra::select('id_producto', \DB::raw('SUM(cantidad) as cantidad'), \DB::raw('SUM(total) as total'))
                ->whereHas('compra', function($q) use ($fecha_inicio, $fecha_fin) {
                    $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
                })
                ->groupBy('id_producto')
                ->with('producto')
                ->get();
            $productos = [];
            foreach ($ventas_por_producto as $venta) {
                $prod_id = $venta->id_producto;
                $productos[$prod_id]['nombre'] = $venta->producto ? $venta->producto->descripcion : 'N/A';
                $productos[$prod_id]['cantidad_vendida'] = $venta->cantidad;
                $productos[$prod_id]['total_venta'] = $venta->total;
            }
            foreach ($compras_por_producto as $compra) {
                $prod_id = $compra->id_producto;
                if (!isset($productos[$prod_id])) {
                    $productos[$prod_id]['nombre'] = $compra->producto ? $compra->producto->descripcion : 'N/A';
                    $productos[$prod_id]['cantidad_vendida'] = 0;
                    $productos[$prod_id]['total_venta'] = 0;
                }
                $productos[$prod_id]['cantidad_comprada'] = $compra->cantidad;
                $productos[$prod_id]['total_compra'] = $compra->total;
            }
            foreach ($productos as &$prod) {
                if (!isset($prod['cantidad_comprada'])) $prod['cantidad_comprada'] = 0;
                if (!isset($prod['total_compra'])) $prod['total_compra'] = 0;
            }
            $months_data[] = [
                'name' => date('F', mktime(0,0,0,$m,1)),
                'total_ventas' => $total_ventas,
                'total_compras' => $total_compras,
                'ganancia' => $ganancia,
                'cantidad_productos_vendidos' => $cantidad_productos_vendidos,
                'cantidad_productos_comprados' => $cantidad_productos_comprados,
                'productos' => array_values($productos)
            ];
            $labels[] = date('M', mktime(0,0,0,$m,1));
            $ventas_chart[] = $total_ventas;
            $compras_chart[] = $total_compras;
            $ganancias_chart[] = $ganancia;
        }
        $grafico_path = '';
        // En PDF no se muestra el gráfico, pero puedes pasarlo si lo necesitas
        return \PDF::loadView('reportes.anual_pdf', compact('year', 'months_data', 'grafico_path'))->download('reporte_anual_' . $year . '.pdf');
    }
    // Reporte Anual (web)
    public function anual(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $months_data = [];
        $labels = [];
        $ventas_chart = [];
        $compras_chart = [];
        $ganancias_chart = [];
        for ($m = 1; $m <= 12; $m++) {
            $fecha_inicio = "$year-" . str_pad($m,2,'0',STR_PAD_LEFT) . "-01 00:00:00";
            $fecha_fin = date('Y-m-t', strtotime("$year-" . str_pad($m,2,'0',STR_PAD_LEFT) . "-01")) . ' 23:59:59';
            $total_ventas = \App\Models\Venta::whereBetween('fecha', [$fecha_inicio, $fecha_fin])->sum('total');
            $total_compras = \App\Models\Compra::whereBetween('fecha', [$fecha_inicio, $fecha_fin])->sum('total');
            $ganancia = $total_ventas - $total_compras;
            $cantidad_productos_vendidos = \App\Models\DetalleVenta::whereHas('venta', function($q) use ($fecha_inicio, $fecha_fin) {
                $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
            })->sum('cantidad');
            $cantidad_productos_comprados = \App\Models\DetalleCompra::whereHas('compra', function($q) use ($fecha_inicio, $fecha_fin) {
                $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
            })->sum('cantidad');
            $ventas_por_producto = \App\Models\DetalleVenta::select('id_producto', \DB::raw('SUM(cantidad) as cantidad'), \DB::raw('SUM(total) as total'))
                ->whereHas('venta', function($q) use ($fecha_inicio, $fecha_fin) {
                    $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
                })
                ->groupBy('id_producto')
                ->with('producto')
                ->get();
            $compras_por_producto = \App\Models\DetalleCompra::select('id_producto', \DB::raw('SUM(cantidad) as cantidad'), \DB::raw('SUM(total) as total'))
                ->whereHas('compra', function($q) use ($fecha_inicio, $fecha_fin) {
                    $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
                })
                ->groupBy('id_producto')
                ->with('producto')
                ->get();
            $productos = [];
            foreach ($ventas_por_producto as $venta) {
                $prod_id = $venta->id_producto;
                $productos[$prod_id]['nombre'] = $venta->producto ? $venta->producto->descripcion : 'N/A';
                $productos[$prod_id]['cantidad_vendida'] = $venta->cantidad;
                $productos[$prod_id]['total_venta'] = $venta->total;
            }
            foreach ($compras_por_producto as $compra) {
                $prod_id = $compra->id_producto;
                if (!isset($productos[$prod_id])) {
                    $productos[$prod_id]['nombre'] = $compra->producto ? $compra->producto->descripcion : 'N/A';
                    $productos[$prod_id]['cantidad_vendida'] = 0;
                    $productos[$prod_id]['total_venta'] = 0;
                }
                $productos[$prod_id]['cantidad_comprada'] = $compra->cantidad;
                $productos[$prod_id]['total_compra'] = $compra->total;
            }
            foreach ($productos as &$prod) {
                if (!isset($prod['cantidad_comprada'])) $prod['cantidad_comprada'] = 0;
                if (!isset($prod['total_compra'])) $prod['total_compra'] = 0;
            }
            $months_data[] = [
                'name' => date('F', mktime(0,0,0,$m,1)),
                'total_ventas' => $total_ventas,
                'total_compras' => $total_compras,
                'ganancia' => $ganancia,
                'cantidad_productos_vendidos' => $cantidad_productos_vendidos,
                'cantidad_productos_comprados' => $cantidad_productos_comprados,
                'productos' => array_values($productos)
            ];
            $labels[] = date('M', mktime(0,0,0,$m,1));
            $ventas_chart[] = $total_ventas;
            $compras_chart[] = $total_compras;
            $ganancias_chart[] = $ganancia;
        }
        $grafico_path = '';
        if (array_sum($ventas_chart) > 0 || array_sum($compras_chart) > 0 || array_sum($ganancias_chart) > 0) {
            $grafico_local = \App\Helpers\GraficoHelper::generarGraficoTrimestral($labels, $ventas_chart, $compras_chart, $ganancias_chart, 'Resumen Anual');
            $grafico_path = asset('storage/' . basename($grafico_local));
        }
        return view('reportes.anual', compact('year', 'months_data', 'grafico_path'));
    }
// ...existing code...
    // Reporte Semestral (web)
    public function semestral(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $semester = $request->input('semester', 1);
        $semesters = [
            1 => [1,2,3,4,5,6],
            2 => [7,8,9,10,11,12]
        ];
        $months_in_semester = $semesters[$semester];
        $months_data = [];
        $labels = [];
        $ventas_chart = [];
        $compras_chart = [];
        $ganancias_chart = [];
        foreach ($months_in_semester as $m) {
            $fecha_inicio = "$year-" . str_pad($m,2,'0',STR_PAD_LEFT) . "-01 00:00:00";
            $fecha_fin = date('Y-m-t', strtotime("$year-" . str_pad($m,2,'0',STR_PAD_LEFT) . "-01")) . ' 23:59:59';
            $total_ventas = \App\Models\Venta::whereBetween('fecha', [$fecha_inicio, $fecha_fin])->sum('total');
            $total_compras = \App\Models\Compra::whereBetween('fecha', [$fecha_inicio, $fecha_fin])->sum('total');
            $ganancia = $total_ventas - $total_compras;
            $cantidad_productos_vendidos = \App\Models\DetalleVenta::whereHas('venta', function($q) use ($fecha_inicio, $fecha_fin) {
                $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
            })->sum('cantidad');
            $cantidad_productos_comprados = \App\Models\DetalleCompra::whereHas('compra', function($q) use ($fecha_inicio, $fecha_fin) {
                $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
            })->sum('cantidad');
            $ventas_por_producto = \App\Models\DetalleVenta::select('id_producto', \DB::raw('SUM(cantidad) as cantidad'), \DB::raw('SUM(total) as total'))
                ->whereHas('venta', function($q) use ($fecha_inicio, $fecha_fin) {
                    $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
                })
                ->groupBy('id_producto')
                ->with('producto')
                ->get();
            $compras_por_producto = \App\Models\DetalleCompra::select('id_producto', \DB::raw('SUM(cantidad) as cantidad'), \DB::raw('SUM(total) as total'))
                ->whereHas('compra', function($q) use ($fecha_inicio, $fecha_fin) {
                    $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
                })
                ->groupBy('id_producto')
                ->with('producto')
                ->get();
            $productos = [];
            foreach ($ventas_por_producto as $venta) {
                $prod_id = $venta->id_producto;
                $productos[$prod_id]['nombre'] = $venta->producto ? $venta->producto->descripcion : 'N/A';
                $productos[$prod_id]['cantidad_vendida'] = $venta->cantidad;
                $productos[$prod_id]['total_venta'] = $venta->total;
            }
            foreach ($compras_por_producto as $compra) {
                $prod_id = $compra->id_producto;
                if (!isset($productos[$prod_id])) {
                    $productos[$prod_id]['nombre'] = $compra->producto ? $compra->producto->descripcion : 'N/A';
                    $productos[$prod_id]['cantidad_vendida'] = 0;
                    $productos[$prod_id]['total_venta'] = 0;
                }
                $productos[$prod_id]['cantidad_comprada'] = $compra->cantidad;
                $productos[$prod_id]['total_compra'] = $compra->total;
            }
            foreach ($productos as &$prod) {
                if (!isset($prod['cantidad_comprada'])) $prod['cantidad_comprada'] = 0;
                if (!isset($prod['total_compra'])) $prod['total_compra'] = 0;
            }
            $months_data[] = [
                'name' => date('F', mktime(0,0,0,$m,1)),
                'total_ventas' => $total_ventas,
                'total_compras' => $total_compras,
                'ganancia' => $ganancia,
                'cantidad_productos_vendidos' => $cantidad_productos_vendidos,
                'cantidad_productos_comprados' => $cantidad_productos_comprados,
                'productos' => array_values($productos)
            ];
            $labels[] = date('M', mktime(0,0,0,$m,1));
            $ventas_chart[] = $total_ventas;
            $compras_chart[] = $total_compras;
            $ganancias_chart[] = $ganancia;
        }
        // Solo gráfico en web
        $grafico_path = '';
        if (array_sum($ventas_chart) > 0 || array_sum($compras_chart) > 0 || array_sum($ganancias_chart) > 0) {
            $grafico_local = \App\Helpers\GraficoHelper::generarGraficoTrimestral($labels, $ventas_chart, $compras_chart, $ganancias_chart, 'Resumen Semestral');
            $grafico_path = asset('storage/' . basename($grafico_local));
        }
        return view('reportes.semestral', compact('year', 'semester', 'months_data', 'grafico_path'));
    }

    // Exportar reporte semestral en PDF
    public function exportarSemestralPdf(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $semester = $request->input('semester', 1);
        $semesters = [
            1 => [1,2,3,4,5,6],
            2 => [7,8,9,10,11,12]
        ];
        $months_in_semester = $semesters[$semester];
        $months_data = [];
        $labels = [];
        $ventas_chart = [];
        $compras_chart = [];
        $ganancias_chart = [];
        foreach ($months_in_semester as $m) {
            $fecha_inicio = "$year-" . str_pad($m,2,'0',STR_PAD_LEFT) . "-01 00:00:00";
            $fecha_fin = date('Y-m-t', strtotime("$year-" . str_pad($m,2,'0',STR_PAD_LEFT) . "-01")) . ' 23:59:59';
            $total_ventas = \App\Models\Venta::whereBetween('fecha', [$fecha_inicio, $fecha_fin])->sum('total');
            $total_compras = \App\Models\Compra::whereBetween('fecha', [$fecha_inicio, $fecha_fin])->sum('total');
            $ganancia = $total_ventas - $total_compras;
            $cantidad_productos_vendidos = \App\Models\DetalleVenta::whereHas('venta', function($q) use ($fecha_inicio, $fecha_fin) {
                $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
            })->sum('cantidad');
            $cantidad_productos_comprados = \App\Models\DetalleCompra::whereHas('compra', function($q) use ($fecha_inicio, $fecha_fin) {
                $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
            })->sum('cantidad');
            $ventas_por_producto = \App\Models\DetalleVenta::select('id_producto', \DB::raw('SUM(cantidad) as cantidad'), \DB::raw('SUM(total) as total'))
                ->whereHas('venta', function($q) use ($fecha_inicio, $fecha_fin) {
                    $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
                })
                ->groupBy('id_producto')
                ->with('producto')
                ->get();
            $compras_por_producto = \App\Models\DetalleCompra::select('id_producto', \DB::raw('SUM(cantidad) as cantidad'), \DB::raw('SUM(total) as total'))
                ->whereHas('compra', function($q) use ($fecha_inicio, $fecha_fin) {
                    $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
                })
                ->groupBy('id_producto')
                ->with('producto')
                ->get();
            $productos = [];
            foreach ($ventas_por_producto as $venta) {
                $prod_id = $venta->id_producto;
                $productos[$prod_id]['nombre'] = $venta->producto ? $venta->producto->descripcion : 'N/A';
                $productos[$prod_id]['cantidad_vendida'] = $venta->cantidad;
                $productos[$prod_id]['total_venta'] = $venta->total;
            }
            foreach ($compras_por_producto as $compra) {
                $prod_id = $compra->id_producto;
                if (!isset($productos[$prod_id])) {
                    $productos[$prod_id]['nombre'] = $compra->producto ? $compra->producto->descripcion : 'N/A';
                    $productos[$prod_id]['cantidad_vendida'] = 0;
                    $productos[$prod_id]['total_venta'] = 0;
                }
                $productos[$prod_id]['cantidad_comprada'] = $compra->cantidad;
                $productos[$prod_id]['total_compra'] = $compra->total;
            }
            foreach ($productos as &$prod) {
                if (!isset($prod['cantidad_comprada'])) $prod['cantidad_comprada'] = 0;
                if (!isset($prod['total_compra'])) $prod['total_compra'] = 0;
            }
            $months_data[] = [
                'name' => date('F', mktime(0,0,0,$m,1)),
                'total_ventas' => $total_ventas,
                'total_compras' => $total_compras,
                'ganancia' => $ganancia,
                'cantidad_productos_vendidos' => $cantidad_productos_vendidos,
                'cantidad_productos_comprados' => $cantidad_productos_comprados,
                'productos' => array_values($productos)
            ];
            $labels[] = date('M', mktime(0,0,0,$m,1));
            $ventas_chart[] = $total_ventas;
            $compras_chart[] = $total_compras;
            $ganancias_chart[] = $ganancia;
        }
        // Sin gráfico en PDF
        $grafico_path = '';
        return \PDF::loadView('reportes.semestral_pdf', compact('year', 'semester', 'months_data', 'grafico_path'))->download('reporte_semestral_' . $year . '_S' . $semester . '.pdf');
    }

    // Exportar reporte semestral en Excel
    public function exportarSemestralExcel(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $semester = $request->input('semester', 1);
        $semesters = [
            1 => [1,2,3,4,5,6],
            2 => [7,8,9,10,11,12]
        ];
        $months_in_semester = $semesters[$semester];
        $data = [
            ['Año', 'Semestre', 'Mes', 'Total Ventas', 'Total Compras', 'Ganancia', 'Productos Vendidos', 'Productos Comprados'],
        ];
        foreach ($months_in_semester as $m) {
            $fecha_inicio = "$year-" . str_pad($m,2,'0',STR_PAD_LEFT) . "-01 00:00:00";
            $fecha_fin = date('Y-m-t', strtotime("$year-" . str_pad($m,2,'0',STR_PAD_LEFT) . "-01")) . ' 23:59:59';
            $total_ventas = \App\Models\Venta::whereBetween('fecha', [$fecha_inicio, $fecha_fin])->sum('total');
            $total_compras = \App\Models\Compra::whereBetween('fecha', [$fecha_inicio, $fecha_fin])->sum('total');
            $ganancia = $total_ventas - $total_compras;
            $cantidad_productos_vendidos = \App\Models\DetalleVenta::whereHas('venta', function($q) use ($fecha_inicio, $fecha_fin) {
                $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
            })->sum('cantidad');
            $cantidad_productos_comprados = \App\Models\DetalleCompra::whereHas('compra', function($q) use ($fecha_inicio, $fecha_fin) {
                $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
            })->sum('cantidad');
            $data[] = [
                $year,
                $semester,
                date('F', mktime(0,0,0,$m,1)),
                $total_ventas,
                $total_compras,
                $ganancia,
                $cantidad_productos_vendidos,
                $cantidad_productos_comprados
            ];

            // Detalle de productos vendidos
            $ventas_por_producto = \App\Models\DetalleVenta::select('id_producto', \DB::raw('SUM(cantidad) as cantidad'), \DB::raw('SUM(total) as total'))
                ->whereHas('venta', function($q) use ($fecha_inicio, $fecha_fin) {
                    $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
                })
                ->groupBy('id_producto')
                ->with('producto')
                ->get();
            $data[] = ['Ventas por Producto en ' . date('F', mktime(0,0,0,$m,1))];
            $data[] = ['Producto', 'Cantidad Vendida', 'Total Venta'];
            foreach ($ventas_por_producto as $venta) {
                $data[] = [
                    $venta->producto ? $venta->producto->descripcion : 'N/A',
                    $venta->cantidad,
                    $venta->total
                ];
            }

            // Detalle de productos comprados
            $compras_por_producto = \App\Models\DetalleCompra::select('id_producto', \DB::raw('SUM(cantidad) as cantidad'), \DB::raw('SUM(total) as total'))
                ->whereHas('compra', function($q) use ($fecha_inicio, $fecha_fin) {
                    $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
                })
                ->groupBy('id_producto')
                ->with('producto')
                ->get();
            $data[] = ['Compras por Producto en ' . date('F', mktime(0,0,0,$m,1))];
            $data[] = ['Producto', 'Cantidad Comprada', 'Total Compra'];
            foreach ($compras_por_producto as $compra) {
                $data[] = [
                    $compra->producto ? $compra->producto->descripcion : 'N/A',
                    $compra->cantidad,
                    $compra->total
                ];
            }
            $data[] = [];
        }
        return \Excel::download(new \App\Exports\ReporteDiarioExport($data), 'reporte_semestral_' . $year . '_S' . $semester . '.xlsx');
    }
    // Exportar reporte mensual en Excel
    public function exportarMensualExcel(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));
        $fecha_inicio = "$year-$month-01 00:00:00";
        $fecha_fin = date('Y-m-t', strtotime("$year-$month-01")) . ' 23:59:59';

        $total_ventas = Venta::whereBetween('fecha', [$fecha_inicio, $fecha_fin])->sum('total');
        $total_compras = Compra::whereBetween('fecha', [$fecha_inicio, $fecha_fin])->sum('total');
        $ganancia = $total_ventas - $total_compras;
        $cantidad_productos_vendidos = \App\Models\DetalleVenta::whereHas('venta', function($q) use ($fecha_inicio, $fecha_fin) {
            $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
        })->sum('cantidad');
        $cantidad_productos_comprados = \App\Models\DetalleCompra::whereHas('compra', function($q) use ($fecha_inicio, $fecha_fin) {
            $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
        })->sum('cantidad');
        $ventas_por_producto = \App\Models\DetalleVenta::select('id_producto', \DB::raw('SUM(cantidad) as cantidad'), \DB::raw('SUM(total) as total'))
            ->whereHas('venta', function($q) use ($fecha_inicio, $fecha_fin) {
                $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
            })
            ->groupBy('id_producto')
            ->with('producto')
            ->get();
        $compras_por_producto = \App\Models\DetalleCompra::select('id_producto', \DB::raw('SUM(cantidad) as cantidad'), \DB::raw('SUM(total) as total'))
            ->whereHas('compra', function($q) use ($fecha_inicio, $fecha_fin) {
                $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
            })
            ->groupBy('id_producto')
            ->with('producto')
            ->get();

        $data = [
            ['Año', 'Mes', 'Fecha Inicio', 'Fecha Fin', 'Total Ventas', 'Total Compras', 'Ganancia', 'Productos Vendidos', 'Productos Comprados'],
            [$year, $month, $fecha_inicio, $fecha_fin, $total_ventas, $total_compras, $ganancia, $cantidad_productos_vendidos, $cantidad_productos_comprados],
            [],
            ['Ventas por Producto'],
            ['Producto', 'Cantidad', 'Total'],
        ];
        foreach ($ventas_por_producto as $venta) {
            $data[] = [
                $venta->producto ? $venta->producto->descripcion : 'N/A',
                $venta->cantidad,
                $venta->total
            ];
        }
        $data[] = [];
        $data[] = ['Compras por Producto'];
        $data[] = ['Producto', 'Cantidad', 'Total'];
        foreach ($compras_por_producto as $compra) {
            $data[] = [
                $compra->producto ? $compra->producto->descripcion : 'N/A',
                $compra->cantidad,
                $compra->total
            ];
        }

        return \Excel::download(new \App\Exports\ReporteDiarioExport($data), 'reporte_mensual_' . $year . '_' . $month . '.xlsx');
    }
    // Exportar reporte mensual en PDF
    public function exportarMensualPdf(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');
        if (empty($year) || empty($month) || !is_numeric($year) || !is_numeric($month) || $month < 1 || $month > 12) {
            return back()->withErrors(['month' => 'Debe seleccionar un año y mes válido para el reporte mensual.']);
        }
        $fecha_inicio = "$year-" . str_pad($month,2,'0',STR_PAD_LEFT) . "-01 00:00:00";
        $fecha_fin = date('Y-m-t', strtotime("$year-" . str_pad($month,2,'0',STR_PAD_LEFT) . "-01")) . ' 23:59:59';

        $total_ventas = Venta::whereBetween('fecha', [$fecha_inicio, $fecha_fin])->sum('total');
        $total_compras = Compra::whereBetween('fecha', [$fecha_inicio, $fecha_fin])->sum('total');
        $ganancia = $total_ventas - $total_compras;
        $cantidad_productos_vendidos = \App\Models\DetalleVenta::whereHas('venta', function($q) use ($fecha_inicio, $fecha_fin) {
            $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
        })->sum('cantidad');
        $cantidad_productos_comprados = \App\Models\DetalleCompra::whereHas('compra', function($q) use ($fecha_inicio, $fecha_fin) {
            $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
        })->sum('cantidad');

        $ventas_por_producto = \App\Models\DetalleVenta::select('id_producto', \DB::raw('SUM(cantidad) as cantidad'), \DB::raw('SUM(total) as total'))
            ->whereHas('venta', function($q) use ($fecha_inicio, $fecha_fin) {
                $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
            })
            ->groupBy('id_producto')
            ->with('producto')
            ->get();
        $compras_por_producto = \App\Models\DetalleCompra::select('id_producto', \DB::raw('SUM(cantidad) as cantidad'), \DB::raw('SUM(total) as total'))
            ->whereHas('compra', function($q) use ($fecha_inicio, $fecha_fin) {
                $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
            })
            ->groupBy('id_producto')
            ->with('producto')
            ->get();

        // Generar gráfico como imagen PNG
        $labels = ['Ventas', 'Compras', 'Ganancia'];
        $dataGrafico = [$total_ventas, $total_compras, $ganancia];
        $grafico_path = \App\Helpers\GraficoHelper::generarGraficoDiario($labels, $dataGrafico, 'Resumen Mensual');

        $data = compact(
            'total_ventas',
            'total_compras',
            'ganancia',
            'cantidad_productos_vendidos',
            'cantidad_productos_comprados',
            'ventas_por_producto',
            'compras_por_producto',
            'year',
            'month',
            'grafico_path'
        );
        $pdf = \PDF::loadView('reportes.mensual_pdf', $data);
        return $pdf->download('reporte_mensual_' . $year . '_' . $month . '.pdf');
    }
    // ...existing code...
    // Reporte mensual
    public function mensual(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));
        $fecha_inicio = "$year-$month-01 00:00:00";
        $fecha_fin = date('Y-m-t', strtotime("$year-$month-01")) . ' 23:59:59';

        $total_ventas = Venta::whereBetween('fecha', [$fecha_inicio, $fecha_fin])->sum('total');
        $total_compras = Compra::whereBetween('fecha', [$fecha_inicio, $fecha_fin])->sum('total');
        $ganancia = $total_ventas - $total_compras;
        $cantidad_productos_vendidos = \App\Models\DetalleVenta::whereHas('venta', function($q) use ($fecha_inicio, $fecha_fin) {
            $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
        })->sum('cantidad');
        $cantidad_productos_comprados = \App\Models\DetalleCompra::whereHas('compra', function($q) use ($fecha_inicio, $fecha_fin) {
            $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
        })->sum('cantidad');

        return view('reportes.mensual', compact(
            'total_ventas',
            'total_compras',
            'ganancia',
            'cantidad_productos_vendidos',
            'cantidad_productos_comprados',
            'year',
            'month'
        ));
    }

    // ...existing code...

    // Exportar reporte semanal en Excel
    public function exportarSemanalExcel(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $week = $request->input('week', date('W'));
        $dto = new \DateTime();
        $dto->setISODate($year, $week);
        $fecha_inicio = $dto->format('Y-m-d');
        $dto->modify('+6 days');
        $fecha_fin = $dto->format('Y-m-d');
        $desde = $fecha_inicio . ' 00:00:00';
        $hasta = $fecha_fin . ' 23:59:59';

        $total_ventas = Venta::whereBetween('fecha', [$desde, $hasta])->sum('total');
        $total_compras = Compra::whereBetween('fecha', [$desde, $hasta])->sum('total');
        $ganancia = $total_ventas - $total_compras;
        $cantidad_productos_vendidos = \App\Models\DetalleVenta::whereHas('venta', function($q) use ($desde, $hasta) {
            $q->whereBetween('fecha', [$desde, $hasta]);
        })->sum('cantidad');
        $cantidad_productos_comprados = \App\Models\DetalleCompra::whereHas('compra', function($q) use ($desde, $hasta) {
            $q->whereBetween('fecha', [$desde, $hasta]);
        })->sum('cantidad');
        $ventas_por_producto = \App\Models\DetalleVenta::select('id_producto', \DB::raw('SUM(cantidad) as cantidad'), \DB::raw('SUM(total) as total'))
            ->whereHas('venta', function($q) use ($desde, $hasta) {
                $q->whereBetween('fecha', [$desde, $hasta]);
            })
            ->groupBy('id_producto')
            ->with('producto')
            ->get();
        $compras_por_producto = \App\Models\DetalleCompra::select('id_producto', \DB::raw('SUM(cantidad) as cantidad'), \DB::raw('SUM(total) as total'))
            ->whereHas('compra', function($q) use ($desde, $hasta) {
                $q->whereBetween('fecha', [$desde, $hasta]);
            })
            ->groupBy('id_producto')
            ->with('producto')
            ->get();

        $data = [
            ['Año', 'Semana', 'Fecha Inicio', 'Fecha Fin', 'Total Ventas', 'Total Compras', 'Ganancia', 'Productos Vendidos', 'Productos Comprados'],
            [$year, $week, $fecha_inicio, $fecha_fin, $total_ventas, $total_compras, $ganancia, $cantidad_productos_vendidos, $cantidad_productos_comprados],
            [],
            ['Ventas por Producto'],
            ['Producto', 'Cantidad', 'Total'],
        ];
        foreach ($ventas_por_producto as $venta) {
            $data[] = [
                $venta->producto ? $venta->producto->descripcion : 'N/A',
                $venta->cantidad,
                $venta->total
            ];
        }
        $data[] = [];
        $data[] = ['Compras por Producto'];
        $data[] = ['Producto', 'Cantidad', 'Total'];
        foreach ($compras_por_producto as $compra) {
            $data[] = [
                $compra->producto ? $compra->producto->descripcion : 'N/A',
                $compra->cantidad,
                $compra->total
            ];
        }

        return \Excel::download(new \App\Exports\ReporteDiarioExport($data), 'reporte_semanal_' . $year . '_semana_' . $week . '.xlsx');
    }
    // Reporte semanal
    public function semanal(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));
        $week = $request->input('week');

        // Generar lista de semanas con inicio y fin
        $weeks_list = [];
        $first_day = new \DateTime("$year-$month-01");
        $last_day = clone $first_day;
        $last_day->modify('last day of this month');
        $current_day = clone $first_day;
        $weeks_seen = [];
        while ($current_day <= $last_day) {
            $week_number = (int)$current_day->format('W');
            $week_year = (int)$current_day->format('o');
            $key = $week_year . '-' . $week_number;
            if (!in_array($key, $weeks_seen)) {
                $dto_start = new \DateTime();
                $dto_start->setISODate($week_year, $week_number);
                $start_date = $dto_start->format('Y-m-d');
                $dto_end = clone $dto_start;
                $dto_end->modify('+6 days');
                $end_date = $dto_end->format('Y-m-d');
                // Ajustar fechas para que estén dentro del mes
                $start_date = max($start_date, $first_day->format('Y-m-d'));
                $end_date = min($end_date, $last_day->format('Y-m-d'));
                $weeks_list[] = [
                    'number' => $week_number,
                    'start' => $start_date,
                    'end' => $end_date
                ];
                $weeks_seen[] = $key;
            }
            $current_day->modify('+1 day');
        }

        // Si el usuario ya seleccionó una semana, calcular el rango de fechas de esa semana
        $fecha_inicio = null;
        $fecha_fin = null;
        $desde = null;
        $hasta = null;
        $total_ventas = null;
        $total_compras = null;
        $ganancia = null;
        $cantidad_productos_vendidos = null;
        $cantidad_productos_comprados = null;
        if ($week) {
            $dto = new \DateTime();
            $dto->setISODate($year, $week);
            $fecha_inicio = $dto->format('Y-m-d');
            $dto->modify('+6 days');
            $fecha_fin = $dto->format('Y-m-d');
            $desde = $fecha_inicio . ' 00:00:00';
            $hasta = $fecha_fin . ' 23:59:59';

            $total_ventas = Venta::whereBetween('fecha', [$desde, $hasta])->sum('total');
            $total_compras = Compra::whereBetween('fecha', [$desde, $hasta])->sum('total');
            $ganancia = $total_ventas - $total_compras;

            $cantidad_productos_vendidos = \App\Models\DetalleVenta::whereHas('venta', function($q) use ($desde, $hasta) {
                $q->whereBetween('fecha', [$desde, $hasta]);
            })->sum('cantidad');

            $cantidad_productos_comprados = \App\Models\DetalleCompra::whereHas('compra', function($q) use ($desde, $hasta) {
                $q->whereBetween('fecha', [$desde, $hasta]);
            })->sum('cantidad');
        }

        return view('reportes.semanal', compact(
            'total_ventas',
            'total_compras',
            'ganancia',
            'cantidad_productos_vendidos',
            'cantidad_productos_comprados',
            'year',
            'month',
            'week',
            'fecha_inicio',
            'fecha_fin',
            'weeks_list'
        ));
    }
    public function diario(Request $request)
    {
        $fecha = $request->input('fecha', date('Y-m-d'));
        $hora_inicio = $request->input('hora_inicio', '00:00');
        $hora_fin = $request->input('hora_fin', '23:59');

        $desde = $fecha . ' ' . $hora_inicio;
        $hasta = $fecha . ' ' . $hora_fin;

        $total_ventas = Venta::whereBetween('fecha', [$desde, $hasta])->sum('total');
        $total_compras = Compra::whereBetween('fecha', [$desde, $hasta])->sum('total');
        $ganancia = $total_ventas - $total_compras;

        $cantidad_productos_vendidos = \App\Models\DetalleVenta::whereHas('venta', function($q) use ($desde, $hasta) {
            $q->whereBetween('fecha', [$desde, $hasta]);
        })->sum('cantidad');

        $cantidad_productos_comprados = \App\Models\DetalleCompra::whereHas('compra', function($q) use ($desde, $hasta) {
            $q->whereBetween('fecha', [$desde, $hasta]);
        })->sum('cantidad');

        return view('reportes.diario', compact(
            'total_ventas',
            'total_compras',
            'ganancia',
            'cantidad_productos_vendidos',
            'cantidad_productos_comprados',
            'fecha',
            'hora_inicio',
            'hora_fin'
        ));
    }
    // Exportar reporte diario en PDF
    public function exportarPdf(Request $request)
    {
        $fecha = $request->input('fecha', date('Y-m-d'));
        $hora_inicio = $request->input('hora_inicio', '00:00');
        $hora_fin = $request->input('hora_fin', '23:59');
        $desde = $fecha . ' ' . $hora_inicio;
        $hasta = $fecha . ' ' . $hora_fin;

        $total_ventas = Venta::whereBetween('fecha', [$desde, $hasta])->sum('total');
        $total_compras = Compra::whereBetween('fecha', [$desde, $hasta])->sum('total');
        $ganancia = $total_ventas - $total_compras;
        $cantidad_productos_vendidos = \App\Models\DetalleVenta::whereHas('venta', function($q) use ($desde, $hasta) {
            $q->whereBetween('fecha', [$desde, $hasta]);
        })->sum('cantidad');
        $cantidad_productos_comprados = \App\Models\DetalleCompra::whereHas('compra', function($q) use ($desde, $hasta) {
            $q->whereBetween('fecha', [$desde, $hasta]);
        })->sum('cantidad');
        $ventas_por_producto = \App\Models\DetalleVenta::select('id_producto', \DB::raw('SUM(cantidad) as cantidad'), \DB::raw('SUM(total) as total'))
            ->whereHas('venta', function($q) use ($desde, $hasta) {
                $q->whereBetween('fecha', [$desde, $hasta]);
            })
            ->groupBy('id_producto')
            ->with('producto')
            ->get();
        $compras_por_producto = \App\Models\DetalleCompra::select('id_producto', \DB::raw('SUM(cantidad) as cantidad'), \DB::raw('SUM(total) as total'))
            ->whereHas('compra', function($q) use ($desde, $hasta) {
                $q->whereBetween('fecha', [$desde, $hasta]);
            })
            ->groupBy('id_producto')
            ->with('producto')
            ->get();

        // Generar gráfico como imagen PNG
        $labels = ['Ventas', 'Compras', 'Ganancia'];
        $dataGrafico = [$total_ventas, $total_compras, $ganancia];
        $grafico_path = \App\Helpers\GraficoHelper::generarGraficoDiario($labels, $dataGrafico, 'Resumen Diario');

        $data = compact(
            'total_ventas',
            'total_compras',
            'ganancia',
            'cantidad_productos_vendidos',
            'cantidad_productos_comprados',
            'ventas_por_producto',
            'compras_por_producto',
            'fecha',
            'hora_inicio',
            'hora_fin',
            'grafico_path'
        );
        // PDF export logic here (if missing, add return statement)
    }
    public function exportarExcel(Request $request)
    {
        $fecha = $request->input('fecha', date('Y-m-d'));
        $hora_inicio = $request->input('hora_inicio', '00:00');
        $hora_fin = $request->input('hora_fin', '23:59');
        $desde = $fecha . ' ' . $hora_inicio;
        $hasta = $fecha . ' ' . $hora_fin;

        $total_ventas = Venta::whereBetween('fecha', [$desde, $hasta])->sum('total');
        $total_compras = Compra::whereBetween('fecha', [$desde, $hasta])->sum('total');
        $ganancia = $total_ventas - $total_compras;
        $cantidad_productos_vendidos = \App\Models\DetalleVenta::whereHas('venta', function($q) use ($desde, $hasta) {
            $q->whereBetween('fecha', [$desde, $hasta]);
        })->sum('cantidad');
        $cantidad_productos_comprados = \App\Models\DetalleCompra::whereHas('compra', function($q) use ($desde, $hasta) {
            $q->whereBetween('fecha', [$desde, $hasta]);
        })->sum('cantidad');
        $ventas_por_producto = \App\Models\DetalleVenta::select('id_producto', \DB::raw('SUM(cantidad) as cantidad'), \DB::raw('SUM(total) as total'))
            ->whereHas('venta', function($q) use ($desde, $hasta) {
                $q->whereBetween('fecha', [$desde, $hasta]);
            })
            ->groupBy('id_producto')
            ->with('producto')
            ->get();
        $compras_por_producto = \App\Models\DetalleCompra::select('id_producto', \DB::raw('SUM(cantidad) as cantidad'), \DB::raw('SUM(total) as total'))
            ->whereHas('compra', function($q) use ($desde, $hasta) {
                $q->whereBetween('fecha', [$desde, $hasta]);
            })
            ->groupBy('id_producto')
            ->with('producto')
            ->get();

        $data = [
            ['Fecha', 'Hora Inicio', 'Hora Fin', 'Total Ventas', 'Total Compras', 'Ganancia', 'Productos Vendidos', 'Productos Comprados'],
            [$fecha, $hora_inicio, $hora_fin, $total_ventas, $total_compras, $ganancia, $cantidad_productos_vendidos, $cantidad_productos_comprados],
            [],
            ['Ventas por Producto'],
            ['Producto', 'Cantidad', 'Total'],
        ];
        foreach ($ventas_por_producto as $venta) {
            $data[] = [
                $venta->producto ? $venta->producto->descripcion : 'N/A',
                $venta->cantidad,
                $venta->total
            ];
        }
        $data[] = [];
        $data[] = ['Compras por Producto'];
        $data[] = ['Producto', 'Cantidad', 'Total'];
        foreach ($compras_por_producto as $compra) {
            $data[] = [
                $compra->producto ? $compra->producto->descripcion : 'N/A',
                $compra->cantidad,
                $compra->total
            ];
        }

        return \Excel::download(new \App\Exports\ReporteDiarioExport($data), 'reporte_diario_' . $fecha . '.xlsx');
    }


// ...existing code...
    // Reporte Trimestral (web)
    public function trimestral(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $quarter = $request->input('quarter', 1);
        $months = [
            1 => [1,2,3],
            2 => [4,5,6],
            3 => [7,8,9],
            4 => [10,11,12]
        ];
        $months_in_quarter = $months[$quarter];
        $months_data = [];
        $labels = [];
        $ventas_chart = [];
        $compras_chart = [];
        $ganancias_chart = [];
        foreach ($months_in_quarter as $m) {
            $fecha_inicio = "$year-" . str_pad($m,2,'0',STR_PAD_LEFT) . "-01 00:00:00";
            $fecha_fin = date('Y-m-t', strtotime("$year-" . str_pad($m,2,'0',STR_PAD_LEFT) . "-01")) . ' 23:59:59';
            $total_ventas = \App\Models\Venta::whereBetween('fecha', [$fecha_inicio, $fecha_fin])->sum('total');
            $total_compras = \App\Models\Compra::whereBetween('fecha', [$fecha_inicio, $fecha_fin])->sum('total');
            $ganancia = $total_ventas - $total_compras;
            $cantidad_productos_vendidos = \App\Models\DetalleVenta::whereHas('venta', function($q) use ($fecha_inicio, $fecha_fin) {
                $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
            })->sum('cantidad');
            $cantidad_productos_comprados = \App\Models\DetalleCompra::whereHas('compra', function($q) use ($fecha_inicio, $fecha_fin) {
                $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
            })->sum('cantidad');
            $ventas_por_producto = \App\Models\DetalleVenta::select('id_producto', \DB::raw('SUM(cantidad) as cantidad'), \DB::raw('SUM(total) as total'))
                ->whereHas('venta', function($q) use ($fecha_inicio, $fecha_fin) {
                    $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
                })
                ->groupBy('id_producto')
                ->with('producto')
                ->get();
            $compras_por_producto = \App\Models\DetalleCompra::select('id_producto', \DB::raw('SUM(cantidad) as cantidad'), \DB::raw('SUM(total) as total'))
                ->whereHas('compra', function($q) use ($fecha_inicio, $fecha_fin) {
                    $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
                })
                ->groupBy('id_producto')
                ->with('producto')
                ->get();
            $productos = [];
            foreach ($ventas_por_producto as $venta) {
                $prod_id = $venta->id_producto;
                $productos[$prod_id]['nombre'] = $venta->producto ? $venta->producto->descripcion : 'N/A';
                $productos[$prod_id]['cantidad_vendida'] = $venta->cantidad;
                $productos[$prod_id]['total_venta'] = $venta->total;
            }
            foreach ($compras_por_producto as $compra) {
                $prod_id = $compra->id_producto;
                if (!isset($productos[$prod_id])) {
                    $productos[$prod_id]['nombre'] = $compra->producto ? $compra->producto->descripcion : 'N/A';
                    $productos[$prod_id]['cantidad_vendida'] = 0;
                    $productos[$prod_id]['total_venta'] = 0;
                }
                $productos[$prod_id]['cantidad_comprada'] = $compra->cantidad;
                $productos[$prod_id]['total_compra'] = $compra->total;
            }
            foreach ($productos as &$prod) {
                if (!isset($prod['cantidad_comprada'])) $prod['cantidad_comprada'] = 0;
                if (!isset($prod['total_compra'])) $prod['total_compra'] = 0;
            }
            $months_data[] = [
                'name' => date('F', mktime(0,0,0,$m,1)),
                'total_ventas' => $total_ventas,
                'total_compras' => $total_compras,
                'ganancia' => $ganancia,
                'cantidad_productos_vendidos' => $cantidad_productos_vendidos,
                'cantidad_productos_comprados' => $cantidad_productos_comprados,
                'productos' => array_values($productos)
            ];
            $labels[] = date('M', mktime(0,0,0,$m,1));
            $ventas_chart[] = $total_ventas;
            $compras_chart[] = $total_compras;
            $ganancias_chart[] = $ganancia;
        }
        // Generar gráfico resumen trimestral
        $grafico_path = '';
        $hayDatos = array_sum($ventas_chart) > 0 || array_sum($compras_chart) > 0 || array_sum($ganancias_chart) > 0;
        if ($hayDatos) {
            $grafico_local = \App\Helpers\GraficoHelper::generarGraficoTrimestral($labels, $ventas_chart, $compras_chart, $ganancias_chart, 'Resumen Trimestral');
            $grafico_path = asset('storage/' . basename($grafico_local));
        } else {
            $grafico_path = asset('images/grafico_vacio.png'); // Debes tener una imagen vacía en public/images/
        }
        return view('reportes.trimestral', compact('year', 'quarter', 'months_data', 'grafico_path'));
    }

    // Exportar reporte trimestral en PDF
    public function exportarTrimestralPdf(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $quarter = $request->input('quarter', 1);
        // Reutilizar la lógica del método trimestral
        $months = [
            1 => [1,2,3],
            2 => [4,5,6],
            3 => [7,8,9],
            4 => [10,11,12]
        ];
        $months_in_quarter = $months[$quarter];
        $months_data = [];
        $labels = [];
        $ventas_chart = [];
        $compras_chart = [];
        $ganancias_chart = [];
        foreach ($months_in_quarter as $m) {
            $fecha_inicio = "$year-" . str_pad($m,2,'0',STR_PAD_LEFT) . "-01 00:00:00";
            $fecha_fin = date('Y-m-t', strtotime("$year-" . str_pad($m,2,'0',STR_PAD_LEFT) . "-01")) . ' 23:59:59';
            $total_ventas = \App\Models\Venta::whereBetween('fecha', [$fecha_inicio, $fecha_fin])->sum('total');
            $total_compras = \App\Models\Compra::whereBetween('fecha', [$fecha_inicio, $fecha_fin])->sum('total');
            $ganancia = $total_ventas - $total_compras;
            $cantidad_productos_vendidos = \App\Models\DetalleVenta::whereHas('venta', function($q) use ($fecha_inicio, $fecha_fin) {
                $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
            })->sum('cantidad');
            $cantidad_productos_comprados = \App\Models\DetalleCompra::whereHas('compra', function($q) use ($fecha_inicio, $fecha_fin) {
                $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
            })->sum('cantidad');
            $ventas_por_producto = \App\Models\DetalleVenta::select('id_producto', \DB::raw('SUM(cantidad) as cantidad'), \DB::raw('SUM(total) as total'))
                ->whereHas('venta', function($q) use ($fecha_inicio, $fecha_fin) {
                    $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
                })
                ->groupBy('id_producto')
                ->with('producto')
                ->get();
            $compras_por_producto = \App\Models\DetalleCompra::select('id_producto', \DB::raw('SUM(cantidad) as cantidad'), \DB::raw('SUM(total) as total'))
                ->whereHas('compra', function($q) use ($fecha_inicio, $fecha_fin) {
                    $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
                })
                ->groupBy('id_producto')
                ->with('producto')
                ->get();
            $productos = [];
            foreach ($ventas_por_producto as $venta) {
                $prod_id = $venta->id_producto;
                $productos[$prod_id]['nombre'] = $venta->producto ? $venta->producto->descripcion : 'N/A';
                $productos[$prod_id]['cantidad_vendida'] = $venta->cantidad;
                $productos[$prod_id]['total_venta'] = $venta->total;
            }
            foreach ($compras_por_producto as $compra) {
                $prod_id = $compra->id_producto;
                if (!isset($productos[$prod_id])) {
                    $productos[$prod_id]['nombre'] = $compra->producto ? $compra->producto->descripcion : 'N/A';
                    $productos[$prod_id]['cantidad_vendida'] = 0;
                    $productos[$prod_id]['total_venta'] = 0;
                }
                $productos[$prod_id]['cantidad_comprada'] = $compra->cantidad;
                $productos[$prod_id]['total_compra'] = $compra->total;
            }
            foreach ($productos as &$prod) {
                if (!isset($prod['cantidad_comprada'])) $prod['cantidad_comprada'] = 0;
                if (!isset($prod['total_compra'])) $prod['total_compra'] = 0;
            }
            $months_data[] = [
                'name' => date('F', mktime(0,0,0,$m,1)),
                'total_ventas' => $total_ventas,
                'total_compras' => $total_compras,
                'ganancia' => $ganancia,
                'cantidad_productos_vendidos' => $cantidad_productos_vendidos,
                'cantidad_productos_comprados' => $cantidad_productos_comprados,
                'productos' => array_values($productos)
            ];
            $labels[] = date('M', mktime(0,0,0,$m,1));
            $ventas_chart[] = $total_ventas;
            $compras_chart[] = $total_compras;
            $ganancias_chart[] = $ganancia;
        }
    $grafico_local = \App\Helpers\GraficoHelper::generarGraficoTrimestral($labels, $ventas_chart, $compras_chart, $ganancias_chart, 'Resumen Trimestral');
    $grafico_path = asset('storage/' . basename($grafico_local));
    $data = compact('year', 'quarter', 'months_data', 'grafico_path');
    $pdf = \PDF::loadView('reportes.trimestral_pdf', $data);
    return $pdf->download('reporte_trimestral_' . $year . '_Q' . $quarter . '.pdf');
    }

    // Exportar reporte trimestral en Excel
    public function exportarTrimestralExcel(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $quarter = $request->input('quarter', 1);
        $months = [
            1 => [1,2,3],
            2 => [4,5,6],
            3 => [7,8,9],
            4 => [10,11,12]
        ];
        $months_in_quarter = $months[$quarter];
        $data = [
            ['Año', 'Trimestre', 'Mes', 'Total Ventas', 'Total Compras', 'Ganancia', 'Productos Vendidos', 'Productos Comprados'],
        ];
        foreach ($months_in_quarter as $m) {
            $fecha_inicio = "$year-" . str_pad($m,2,'0',STR_PAD_LEFT) . "-01 00:00:00";
            $fecha_fin = date('Y-m-t', strtotime("$year-" . str_pad($m,2,'0',STR_PAD_LEFT) . "-01")) . ' 23:59:59';
            $total_ventas = \App\Models\Venta::whereBetween('fecha', [$fecha_inicio, $fecha_fin])->sum('total');
            $total_compras = \App\Models\Compra::whereBetween('fecha', [$fecha_inicio, $fecha_fin])->sum('total');
            $ganancia = $total_ventas - $total_compras;
            $cantidad_productos_vendidos = \App\Models\DetalleVenta::whereHas('venta', function($q) use ($fecha_inicio, $fecha_fin) {
                $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
            })->sum('cantidad');
            $cantidad_productos_comprados = \App\Models\DetalleCompra::whereHas('compra', function($q) use ($fecha_inicio, $fecha_fin) {
                $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
            })->sum('cantidad');
            $data[] = [
                $year,
                $quarter,
                date('F', mktime(0,0,0,$m,1)),
                $total_ventas,
                $total_compras,
                $ganancia,
                $cantidad_productos_vendidos,
                $cantidad_productos_comprados
            ];

            // Detalle de productos vendidos
            $ventas_por_producto = \App\Models\DetalleVenta::select('id_producto', \DB::raw('SUM(cantidad) as cantidad'), \DB::raw('SUM(total) as total'))
                ->whereHas('venta', function($q) use ($fecha_inicio, $fecha_fin) {
                    $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
                })
                ->groupBy('id_producto')
                ->with('producto')
                ->get();
            $data[] = ['Ventas por Producto en ' . date('F', mktime(0,0,0,$m,1))];
            $data[] = ['Producto', 'Cantidad Vendida', 'Total Venta'];
            foreach ($ventas_por_producto as $venta) {
                $data[] = [
                    $venta->producto ? $venta->producto->descripcion : 'N/A',
                    $venta->cantidad,
                    $venta->total
                ];
            }

            // Detalle de productos comprados
            $compras_por_producto = \App\Models\DetalleCompra::select('id_producto', \DB::raw('SUM(cantidad) as cantidad'), \DB::raw('SUM(total) as total'))
                ->whereHas('compra', function($q) use ($fecha_inicio, $fecha_fin) {
                    $q->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
                })
                ->groupBy('id_producto')
                ->with('producto')
                ->get();
            $data[] = ['Compras por Producto en ' . date('F', mktime(0,0,0,$m,1))];
            $data[] = ['Producto', 'Cantidad Comprada', 'Total Compra'];
            foreach ($compras_por_producto as $compra) {
                $data[] = [
                    $compra->producto ? $compra->producto->descripcion : 'N/A',
                    $compra->cantidad,
                    $compra->total
                ];
            }
            $data[] = [];
        }
        return \Excel::download(new \App\Exports\ReporteDiarioExport($data), 'reporte_trimestral_' . $year . '_Q' . $quarter . '.xlsx');
    }
}
