<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $clientes = \App\Models\Cliente::count();
            $productos = \App\Models\Producto::count();
            $monedas = \App\Models\Moneda::count();
            $alertas = \App\Models\Producto::where('stock_actual', '<', 10)->count();
            
            // Estadísticas adicionales
            $ventas_hoy = \App\Models\Venta::whereDate('fecha', today())->count();
            $ventas_mes = \App\Models\Venta::whereMonth('fecha', now()->month)
                                        ->whereYear('fecha', now()->year)
                                        ->count();
        } catch (\Exception $e) {
            // Si hay error, usar valores por defecto
            $clientes = 0;
            $productos = 0;
            $monedas = 0;
            $alertas = 0;
            $ventas_hoy = 0;
            $ventas_mes = 0;
        }

        return view('dashboard.index', compact('clientes', 'productos', 'monedas', 'alertas', 'ventas_hoy', 'ventas_mes'));
    }
}
