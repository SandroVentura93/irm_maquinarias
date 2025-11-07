<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $clientes = \App\Models\Cliente::count();
        $productos = \App\Models\Producto::count();
        $monedas = \App\Models\Moneda::count();
        $alertas = \App\Models\Producto::where('stock_actual', '<', 10)->count();

        return view('dashboard.index', compact('clientes', 'productos', 'monedas', 'alertas'));
    }
}
