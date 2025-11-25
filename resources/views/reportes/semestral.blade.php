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
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
        box-shadow: 0 8px 16px rgba(16, 185, 129, 0.15);
    }

    .month-card-header {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 1rem;
        border-radius: 12px 12px 0 0;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .month-card-body {
        padding: 1.25rem;
    }

    .metric-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .metric-row:last-child {
        border-bottom: none;
    }

    .metric-label {
        font-weight: 500;
        color: #6b7280;
        font-size: 0.9rem;
    }

    .metric-value {
        font-weight: 600;
        color: #111827;
        font-size: 1rem;
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
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        padding: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .summary-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        background: white;
        border-radius: 12px;
        margin-bottom: 1rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .summary-item:last-child {
        margin-bottom: 0;
    }

    .summary-label {
        font-weight: 600;
        color: #059669;
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
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
        background-color: #f0fdf4;
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
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    .btn-filter {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.625rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        color: white;
    }
</style>

<div class="container-fluid px-4 py-4">
    <div class="page-header">
        <i class="fas fa-calendar-alt fa-2x" style="color: #10b981;"></i>
        <h1 class="page-title">Reporte Semestral</h1>
    </div>

    <!-- Filtros -->
    <div class="filter-card">
        <form method="GET" action="{{ route('reportes.semestral') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="year" class="form-label">
                    <i class="fas fa-calendar me-2"></i>Año
                </label>
                <select name="year" id="year" class="form-select">
                    @for ($y = date('Y')-5; $y <= date('Y'); $y++)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4">
                <label for="semester" class="form-label">
                    <i class="fas fa-calendar-check me-2"></i>Semestre
                </label>
                <select name="semester" id="semester" class="form-select">
                    <option value="1" {{ $semester == 1 ? 'selected' : '' }}>1er Semestre (Enero - Junio)</option>
                    <option value="2" {{ $semester == 2 ? 'selected' : '' }}>2do Semestre (Julio - Diciembre)</option>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-filter w-100">
                    <i class="fas fa-filter me-2"></i>Aplicar Filtros
                </button>
            </div>
        </form>
    </div>

    @if(isset($months_data))
        <!-- Tarjetas de Meses -->
        <div class="row g-3 mb-4">
            @foreach($months_data as $month)
            <div class="col-md-6 col-lg-4 col-xl-2">
                <div class="month-card">
                    <div class="month-card-header">
                        <i class="fas fa-calendar-day me-2"></i>{{ $month['name'] }}
                    </div>
                    <div class="month-card-body">
                        <div class="metric-row">
                            <span class="metric-label">
                                <i class="fas fa-shopping-cart me-1"></i>Ventas
                            </span>
                            <span class="metric-value success">S/ {{ number_format($month['total_ventas'], 2) }}</span>
                        </div>
                        <div class="metric-row">
                            <span class="metric-label">
                                <i class="fas fa-box me-1"></i>Compras
                            </span>
                            <span class="metric-value danger">S/ {{ number_format($month['total_compras'], 2) }}</span>
                        </div>
                        <div class="metric-row">
                            <span class="metric-label">
                                <i class="fas fa-chart-line me-1"></i>Ganancia
                            </span>
                            <span class="metric-value" style="color: #10b981;">S/ {{ number_format($month['ganancia'], 2) }}</span>
                        </div>
                        <div class="metric-row">
                            <span class="metric-label">
                                <i class="fas fa-cubes me-1"></i>Vendidos
                            </span>
                            <span class="metric-value">{{ $month['cantidad_productos_vendidos'] }}</span>
                        </div>
                        <div class="metric-row">
                            <span class="metric-label">
                                <i class="fas fa-dolly me-1"></i>Comprados
                            </span>
                            <span class="metric-value">{{ $month['cantidad_productos_comprados'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Totales del Semestre -->
        @php
            $totalVentas = collect($months_data)->sum('total_ventas');
            $totalCompras = collect($months_data)->sum('total_compras');
            $totalGanancia = collect($months_data)->sum('ganancia');
            $totalProductosVendidos = collect($months_data)->sum('cantidad_productos_vendidos');
            $totalProductosComprados = collect($months_data)->sum('cantidad_productos_comprados');
        @endphp
        
        <div class="summary-card mb-4">
            <h4 class="text-center mb-4" style="color: #059669; font-weight: 700;">
                <i class="fas fa-chart-pie me-2"></i>Resumen del Semestre {{ $semester }} - {{ $year }}
            </h4>
            <div class="row">
                <div class="col-md-4">
                    <div class="summary-item">
                        <span class="summary-label">
                            <i class="fas fa-dollar-sign"></i>Total Ventas
                        </span>
                        <span class="summary-value" style="color: #059669;">S/ {{ number_format($totalVentas, 2) }}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="summary-item">
                        <span class="summary-label">
                            <i class="fas fa-shopping-bag"></i>Total Compras
                        </span>
                        <span class="summary-value" style="color: #dc2626;">S/ {{ number_format($totalCompras, 2) }}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="summary-item">
                        <span class="summary-label">
                            <i class="fas fa-trophy"></i>Ganancia Total
                        </span>
                        <span class="summary-value" style="color: #10b981;">S/ {{ number_format($totalGanancia, 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="summary-item">
                        <span class="summary-label">
                            <i class="fas fa-chart-bar"></i>Productos Vendidos
                        </span>
                        <span class="summary-value">{{ number_format($totalProductosVendidos) }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="summary-item">
                        <span class="summary-label">
                            <i class="fas fa-warehouse"></i>Productos Comprados
                        </span>
                        <span class="summary-value">{{ number_format($totalProductosComprados) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico -->
        <div class="chart-container">
            <h4 class="chart-title">
                <i class="fas fa-chart-area me-2"></i>Análisis Comparativo del Semestre
            </h4>
            <canvas id="graficoSemestral" height="80"></canvas>
        </div>

        <!-- Botones de Exportación -->
        <div class="export-buttons">
            <a href="{{ route('reportes.semestral.pdf', ['year' => $year, 'semester' => $semester]) }}" class="btn btn-export btn-pdf">
                <i class="fas fa-file-pdf"></i>
                Exportar PDF
            </a>
            <!-- <a href="{{ route('reportes.semestral.excel', ['year' => $year, 'semester' => $semester]) }}" class="btn btn-export btn-excel">
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
            Seleccione un año y semestre para visualizar el reporte.
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    @if(isset($months_data))
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('graficoSemestral').getContext('2d');
        
        // Crear gradientes
        const gradientVentas = ctx.createLinearGradient(0, 0, 0, 400);
        gradientVentas.addColorStop(0, 'rgba(16, 185, 129, 0.8)');
        gradientVentas.addColorStop(1, 'rgba(16, 185, 129, 0.2)');
        
        const gradientCompras = ctx.createLinearGradient(0, 0, 0, 400);
        gradientCompras.addColorStop(0, 'rgba(239, 68, 68, 0.8)');
        gradientCompras.addColorStop(1, 'rgba(239, 68, 68, 0.2)');
        
        const gradientGanancias = ctx.createLinearGradient(0, 0, 0, 400);
        gradientGanancias.addColorStop(0, 'rgba(59, 130, 246, 0.8)');
        gradientGanancias.addColorStop(1, 'rgba(59, 130, 246, 0.2)');

        const labels = [
            @foreach($months_data as $month)
                '{{ $month['name'] }}',
            @endforeach
        ];
        
        const ventas = [
            @foreach($months_data as $month)
                {{ $month['total_ventas'] }},
            @endforeach
        ];
        
        const compras = [
            @foreach($months_data as $month)
                {{ $month['total_compras'] }},
            @endforeach
        ];
        
        const ganancias = [
            @foreach($months_data as $month)
                {{ $month['ganancia'] }},
            @endforeach
        ];

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Ventas',
                        data: ventas,
                        backgroundColor: gradientVentas,
                        borderColor: '#10b981',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    },
                    {
                        label: 'Compras',
                        data: compras,
                        backgroundColor: gradientCompras,
                        borderColor: '#ef4444',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: '#ef4444',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    },
                    {
                        label: 'Ganancia',
                        data: ganancias,
                        backgroundColor: gradientGanancias,
                        borderColor: '#3b82f6',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: '#3b82f6',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
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
                                return context.dataset.label + ': S/ ' + context.parsed.y.toLocaleString('es-PE', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'S/ ' + value.toLocaleString('es-PE');
                            },
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 12,
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
