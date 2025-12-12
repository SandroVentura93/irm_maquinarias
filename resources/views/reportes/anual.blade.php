@extends('layouts.dashboard')

@section('content')
<style>
    .card-modern {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .card-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    .card-header-gradient {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        padding: 1.5rem;
        border: none;
    }

    .month-card {
        border: none;
        border-radius: 12px;
        background: white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        height: 100%;
    }

    .month-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(245, 158, 11, 0.2);
    }

    .month-card-header {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        padding: 1rem;
        border-radius: 12px 12px 0 0;
        font-weight: 600;
        font-size: 1rem;
        text-align: center;
    }

    .month-card-body {
        padding: 1rem;
    }

    .metric-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .metric-row:last-child {
        border-bottom: none;
    }

    .metric-label {
        font-weight: 500;
        color: #6b7280;
        font-size: 0.85rem;
    }

    .metric-value {
        font-weight: 600;
        color: #111827;
        font-size: 0.9rem;
    }

    .metric-value.success {
        color: #059669;
    }

    .metric-value.danger {
        color: #dc2626;
    }

    .filter-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
    }

    .summary-card {
        border: none;
        border-radius: 16px;
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        padding: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    .summary-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.25rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .summary-label {
        font-weight: 600;
        color: #d97706;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .summary-value {
        font-weight: 700;
        font-size: 1.5rem;
        color: #111827;
    }

    .chart-container {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }

    .chart-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .table-modern {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .table-modern thead {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    .table-modern thead th {
        font-weight: 600;
        padding: 1rem;
        border: none;
    }

    .table-modern tbody tr {
        transition: background-color 0.2s ease;
    }

    .table-modern tbody tr:hover {
        background-color: #fffbeb;
    }

    .table-modern tbody td {
        padding: 1rem;
        vertical-align: middle;
    }

    .export-buttons {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .btn-export {
        padding: 0.75rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-export:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-pdf {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .btn-excel {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .page-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .form-select {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 0.625rem 1rem;
        transition: all 0.3s ease;
    }

    .form-select:focus {
        border-color: #f59e0b;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
    }

    .btn-filter {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.625rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        color: white;
    }

    .stats-highlight {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-box {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        text-align: center;
        transition: all 0.3s ease;
    }

    .stat-box:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(245, 158, 11, 0.2);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.5rem;
    }

    .stat-icon.ventas {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .stat-icon.compras {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .stat-icon.ganancia {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    .stat-icon.productos {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
    }

    .stat-label {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #111827;
    }
</style>

<div class="container-fluid px-4 py-4">
    <div class="page-header">
        <i class="fas fa-calendar fa-2x" style="color: #f59e0b;"></i>
        <h1 class="page-title">Reporte Anual</h1>
    </div>

    <!-- Filtros -->
    <div class="filter-card">
        <form method="GET" action="{{ route('reportes.anual') }}" class="row g-3 align-items-end">
            <div class="col-md-8">
                <label for="year" class="form-label">
                    <i class="fas fa-calendar-alt me-2"></i>Seleccionar Año
                </label>
                <select name="year" id="year" class="form-select">
                    @for ($y = date('Y')-5; $y <= date('Y'); $y++)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-filter w-100">
                    <i class="fas fa-search me-2"></i>Generar Reporte
                </button>
            </div>
        </form>
    </div>

    @if(isset($months_data))
        <!-- Estadísticas Destacadas -->
        @php
            $totalVentas = collect($months_data)->sum('total_ventas');
            $totalCompras = collect($months_data)->sum('total_compras');
            $totalGanancia = collect($months_data)->sum('ganancia');
            $totalProductosVendidos = collect($months_data)->sum('cantidad_productos_vendidos');
            $totalProductosComprados = collect($months_data)->sum('cantidad_productos_comprados');
            $promedioMensualVentas = $totalVentas / 12;
            $promedioMensualGanancia = $totalGanancia / 12;
            // Totales por moneda provistos por el controlador (fallbacks)
            $totalVentasPen = $sum_ventas_pen ?? collect($months_data)->sum('total_ventas_pen');
            $totalVentasUsd = $sum_ventas_usd ?? collect($months_data)->sum('total_ventas_usd');
            $totalComprasPen = $sum_compras_pen ?? collect($months_data)->sum('total_compras_pen');
            $totalComprasUsd = $sum_compras_usd ?? collect($months_data)->sum('total_compras_usd');
            $totalGananciaPen = $totalVentasPen - $totalComprasPen;
            $totalGananciaUsd = $totalVentasUsd - $totalComprasUsd;
        @endphp

        <div class="stats-highlight">
            <div class="stat-box">
                <div class="stat-icon ventas">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-label">Total Ventas (PEN)</div>
                <div class="stat-value" style="color: #059669;">S/ {{ number_format($totalVentasPen ?? $totalVentas, 2) }}</div>
                <small class="text-muted">Promedio: S/ {{ number_format(($totalVentasPen ?? $totalVentas) / 12, 2) }}/mes</small>
            </div>

            <div class="stat-box">
                <div class="stat-icon ventas">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-label">Total Ventas (USD)</div>
                <div class="stat-value" style="color: #059669;">$ {{ number_format($totalVentasUsd ?? 0, 2) }}</div>
                <small class="text-muted">Promedio: $ {{ number_format(($totalVentasUsd ?? 0) / 12, 2) }}/mes</small>
            </div>

            <div class="stat-box">
                <div class="stat-icon compras">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-label">Total Compras (PEN)</div>
                <div class="stat-value" style="color: #dc2626;">S/ {{ number_format($totalComprasPen ?? $totalCompras, 2) }}</div>
                <small class="text-muted">Inversión del año</small>
            </div>

            <div class="stat-box">
                <div class="stat-icon compras">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-label">Total Compras (USD)</div>
                <div class="stat-value" style="color: #dc2626;">$ {{ number_format($totalComprasUsd ?? 0, 2) }}</div>
                <small class="text-muted">Inversión del año</small>
            </div>

            <div class="stat-box">
                <div class="stat-icon ganancia">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-label">Ganancia Total (PEN)</div>
                <div class="stat-value" style="color: #f59e0b;">S/ {{ number_format($totalGananciaPen ?? ($totalVentas - $totalCompras), 2) }}</div>
                <small class="text-muted">Promedio: S/ {{ number_format(($totalGananciaPen ?? ($totalVentas - $totalCompras)) / 12, 2) }}/mes</small>
            </div>

            <div class="stat-box">
                <div class="stat-icon ganancia">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-label">Ganancia Total (USD)</div>
                <div class="stat-value" style="color: #f59e0b;">$ {{ number_format($totalGananciaUsd ?? 0, 2) }}</div>
                <small class="text-muted">Promedio: $ {{ number_format(($totalGananciaUsd ?? 0) / 12, 2) }}/mes</small>
            </div>

            <div class="stat-box">
                <div class="stat-icon productos">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="stat-label">Total Productos</div>
                <div class="stat-value">{{ number_format($totalProductosVendidos) }}</div>
                <small class="text-muted">Vendidos en {{ $year }}</small>
            </div>
        </div>

        <!-- Tarjetas de Meses -->
        <div class="row g-3 mb-4">
            @foreach($months_data as $month)
            <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                <div class="month-card">
                    <div class="month-card-header">
                        <i class="fas fa-calendar-day me-1"></i>{{ $month['name'] }}
                    </div>
                    <div class="month-card-body">
                                <div class="metric-row">
                                    <span class="metric-label">
                                        <i class="fas fa-arrow-up me-1" style="font-size: 0.7rem;"></i>Ventas (PEN)
                                    </span>
                                    <span class="metric-value success">S/ {{ number_format($month['total_ventas_pen'] ?? ($month['total_ventas'] ?? 0), 2) }}</span>
                                </div>
                                <div class="metric-row">
                                    <span class="metric-label">
                                        <i class="fas fa-arrow-up me-1" style="font-size: 0.7rem;"></i>Ventas (USD)
                                    </span>
                                    <span class="metric-value success">$ {{ number_format($month['total_ventas_usd'] ?? 0, 2) }}</span>
                                </div>
                                <div class="metric-row">
                                    <span class="metric-label">
                                        <i class="fas fa-arrow-down me-1" style="font-size: 0.7rem;"></i>Compras (PEN)
                                    </span>
                                    <span class="metric-value danger">S/ {{ number_format($month['total_compras_pen'] ?? ($month['total_compras'] ?? 0), 2) }}</span>
                                </div>
                                <div class="metric-row">
                                    <span class="metric-label">
                                        <i class="fas fa-arrow-down me-1" style="font-size: 0.7rem;"></i>Compras (USD)
                                    </span>
                                    <span class="metric-value danger">$ {{ number_format($month['total_compras_usd'] ?? 0, 2) }}</span>
                                </div>
                                <div class="metric-row">
                                    <span class="metric-label">
                                        <i class="fas fa-coins me-1" style="font-size: 0.7rem;"></i>Ganancia (PEN)
                                    </span>
                                    <span class="metric-value" style="color: #f59e0b;">S/ {{ number_format($month['ganancia_pen'] ?? ($month['ganancia'] ?? 0), 2) }}</span>
                                </div>
                                <div class="metric-row">
                                    <span class="metric-label">
                                        <i class="fas fa-coins me-1" style="font-size: 0.7rem;"></i>Ganancia (USD)
                                    </span>
                                    <span class="metric-value" style="color: #f59e0b;">$ {{ number_format($month['ganancia_usd'] ?? 0, 2) }}</span>
                                </div>
                        <div class="metric-row">
                            <span class="metric-label">
                                <i class="fas fa-box me-1" style="font-size: 0.7rem;"></i>Vendidos
                            </span>
                            <span class="metric-value">{{ $month['cantidad_productos_vendidos'] }}</span>
                        </div>
                        <div class="metric-row">
                            <span class="metric-label">
                                <i class="fas fa-dolly me-1" style="font-size: 0.7rem;"></i>Comprados
                            </span>
                            <span class="metric-value">{{ $month['cantidad_productos_comprados'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Gráfico -->
        <div class="chart-container">
            <h4 class="chart-title">
                <i class="fas fa-chart-bar me-2"></i>Análisis Anual {{ $year }} - Comparativa Mensual
            </h4>
            <canvas id="graficoAnual" height="60"></canvas>
        </div>

        <!-- Resumen Anual -->
        <div class="summary-card">
            <h4 class="text-center mb-4" style="color: #d97706; font-weight: 700;">
                <i class="fas fa-crown me-2"></i>Resumen Ejecutivo del Año {{ $year }}
            </h4>
            <div class="summary-grid">
                <div class="summary-item">
                    <span class="summary-label">
                        <i class="fas fa-dollar-sign"></i>Total Ventas
                    </span>
                    <span class="summary-value" style="color: #059669;">S/ {{ number_format($totalVentas, 2) }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">
                        <i class="fas fa-shopping-bag"></i>Total Compras
                    </span>
                    <span class="summary-value" style="color: #dc2626;">S/ {{ number_format($totalCompras, 2) }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">
                        <i class="fas fa-trophy"></i>Ganancia Total
                    </span>
                    <span class="summary-value" style="color: #f59e0b;">S/ {{ number_format($totalGanancia, 2) }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">
                        <i class="fas fa-chart-line"></i>Promedio Mensual
                    </span>
                    <span class="summary-value">S/ {{ number_format($promedioMensualVentas, 2) }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">
                        <i class="fas fa-boxes"></i>Productos Vendidos
                    </span>
                    <span class="summary-value">{{ number_format($totalProductosVendidos) }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">
                        <i class="fas fa-warehouse"></i>Productos Comprados
                    </span>
                    <span class="summary-value">{{ number_format($totalProductosComprados) }}</span>
                </div>
            </div>
        </div>

        <!-- Botones de Exportación -->
        <div class="export-buttons">
            <a href="{{ route('reportes.anual.pdf', ['year' => $year]) }}" class="btn btn-export btn-pdf">
                <i class="fas fa-file-pdf"></i>
                Exportar PDF
            </a>
            <!-- <a href="{{ route('reportes.anual.excel', ['year' => $year]) }}" class="btn btn-export btn-excel">
                <i class="fas fa-file-excel"></i>
                Exportar Excel
            </a> -->
        </div>

        <!-- Tabla de Detalle por Productos -->
        <div class="table-modern">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th><i class="fas fa-calendar me-2"></i>Mes</th>
                        <th><i class="fas fa-box me-2"></i>Producto</th>
                        <th class="text-center"><i class="fas fa-arrow-up me-2"></i>Cantidad Vendida</th>
                        <th class="text-end"><i class="fas fa-dollar-sign me-2"></i>Total Ventas</th>
                        <th class="text-center"><i class="fas fa-arrow-down me-2"></i>Cantidad Comprada</th>
                        <th class="text-end"><i class="fas fa-coins me-2"></i>Total Compras</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($months_data as $month)
                        @foreach($month['productos'] as $producto)
                        <tr>
                            <td><strong>{{ $month['name'] }}</strong></td>
                            <td>{{ $producto['nombre'] }}</td>
                            <td class="text-center">
                                <span class="badge bg-success">{{ $producto['cantidad_vendida'] }}</span>
                            </td>
                            <td class="text-end" style="color: #059669; font-weight: 600;">
                                S/ {{ number_format($producto['total_venta'], 2) }}
                            </td>
                            <td class="text-center">
                                <span class="badge bg-danger">{{ $producto['cantidad_comprada'] }}</span>
                            </td>
                            <td class="text-end" style="color: #dc2626; font-weight: 600;">
                                S/ {{ number_format($producto['total_compra'], 2) }}
                            </td>
                        </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            Seleccione un año para visualizar el reporte anual completo.
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    @if(isset($months_data))
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('graficoAnual').getContext('2d');
        
        // Crear gradientes
        const gradientVentas = ctx.createLinearGradient(0, 0, 0, 400);
        gradientVentas.addColorStop(0, 'rgba(16, 185, 129, 0.8)');
        gradientVentas.addColorStop(1, 'rgba(16, 185, 129, 0.2)');
        
        const gradientCompras = ctx.createLinearGradient(0, 0, 0, 400);
        gradientCompras.addColorStop(0, 'rgba(239, 68, 68, 0.8)');
        gradientCompras.addColorStop(1, 'rgba(239, 68, 68, 0.2)');
        
        const gradientGanancias = ctx.createLinearGradient(0, 0, 0, 400);
        gradientGanancias.addColorStop(0, 'rgba(245, 158, 11, 0.8)');
        gradientGanancias.addColorStop(1, 'rgba(245, 158, 11, 0.2)');

        const labels = [
            @foreach($months_data as $month)
                '{{ $month['name'] }}',
            @endforeach
        ];

        const ventasPen = @isset($ventas_chart_pen) {!! json_encode($ventas_chart_pen) !!} @else [
            @foreach($months_data as $month)
                {{ $month['total_ventas_pen'] ?? 0 }},
            @endforeach
        ] @endisset;

        const ventasUsd = @isset($ventas_chart_usd) {!! json_encode($ventas_chart_usd) !!} @else [
            @foreach($months_data as $month)
                {{ $month['total_ventas_usd'] ?? 0 }},
            @endforeach
        ] @endisset;

        const comprasPen = @isset($compras_chart_pen) {!! json_encode($compras_chart_pen) !!} @else [
            @foreach($months_data as $month)
                {{ $month['total_compras_pen'] ?? 0 }},
            @endforeach
        ] @endisset;

        const comprasUsd = @isset($compras_chart_usd) {!! json_encode($compras_chart_usd) !!} @else [
            @foreach($months_data as $month)
                {{ $month['total_compras_usd'] ?? 0 }},
            @endforeach
        ] @endisset;

        const gananciasPen = @isset($ganancias_chart_pen) {!! json_encode($ganancias_chart_pen) !!} @else [
            @foreach($months_data as $month)
                {{ $month['ganancia_pen'] ?? 0 }},
            @endforeach
        ] @endisset;

        const gananciasUsd = @isset($ganancias_chart_usd) {!! json_encode($ganancias_chart_usd) !!} @else [
            @foreach($months_data as $month)
                {{ $month['ganancia_usd'] ?? 0 }},
            @endforeach
        ] @endisset;

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Ventas (S/)',
                        data: ventasPen,
                        backgroundColor: gradientVentas,
                        borderColor: '#10b981',
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false,
                        yAxisID: 'yPen'
                    },
                    {
                        label: 'Ventas ($)',
                        data: ventasUsd,
                        backgroundColor: 'rgba(59,130,246,0.08)',
                        borderColor: '#3b82f6',
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false,
                        yAxisID: 'yUsd'
                    },
                    {
                        label: 'Compras (S/)',
                        data: comprasPen,
                        backgroundColor: gradientCompras,
                        borderColor: '#ef4444',
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false,
                        yAxisID: 'yPen'
                    },
                    {
                        label: 'Compras ($)',
                        data: comprasUsd,
                        backgroundColor: 'rgba(239,68,68,0.08)',
                        borderColor: '#ef4444',
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false,
                        yAxisID: 'yUsd'
                    },
                    {
                        label: 'Ganancia (S/)',
                        data: gananciasPen,
                        backgroundColor: gradientGanancias,
                        borderColor: '#f59e0b',
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false,
                        yAxisID: 'yPen'
                    },
                    {
                        label: 'Ganancia ($)',
                        data: gananciasUsd,
                        backgroundColor: 'rgba(245,158,11,0.06)',
                        borderColor: '#f59e0b',
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false,
                        yAxisID: 'yUsd'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 13,
                                weight: '600'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        callbacks: {
                            label: function(context) {
                                var label = context.dataset.label || '';
                                var value = context.parsed.y || 0;
                                if (context.dataset.yAxisID === 'yUsd') {
                                    return label + ': $ ' + value.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                }
                                return label + ': S/ ' + value.toLocaleString('es-PE', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            }
                        }
                    }
                },
                scales: {
                    yPen: {
                        type: 'linear',
                        position: 'left',
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'S/ ' + value.toLocaleString('es-PE');
                            },
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    yUsd: {
                        type: 'linear',
                        position: 'right',
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$ ' + value.toLocaleString('en-US');
                            },
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            display: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11,
                                weight: '600'
                            }
                        }
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart'
                }
            }
        });
    });
    @endif
</script>

@endsection
