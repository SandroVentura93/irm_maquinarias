@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header Ultra Moderno -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="dashboard-header-ultra">
                <div class="header-content-wrapper">
                    <div class="header-left-section">
                        <div class="dashboard-logo-container">
                            <div class="logo-circle">
                                <i class="fas fa-industry"></i>
                            </div>
                            <div class="logo-animation"></div>
                        </div>
                        <div class="header-text-content">
                            <h1 class="dashboard-title-ultra">IRM Maquinarias</h1>
                            <h2 class="dashboard-subtitle-ultra">Dashboard Ejecutivo</h2>
                            <p class="dashboard-description">Panel de Control y Análisis de Rendimiento</p>
                        </div>
                    </div>
                    <div class="header-right-section">
                        <div class="time-weather-widget">
                            <div class="current-time">
                                <div class="time-display">
                                    <span id="currentTime">{{ now()->format('H:i:s') }}</span>
                                </div>
                                <div class="date-display">
                                    <span id="currentDate">{{ now()->format('d M Y') }}</span>
                                </div>
                            </div>
                            <div class="weather-icon">
                                <i class="fas fa-cloud-sun"></i>
                            </div>
                        </div>
                        <div class="user-profile-mini">
                            <div class="profile-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="profile-info">
                                <span class="profile-name">{{ Auth::user()->nombre ?? 'Usuario' }}</span>
                                <span class="profile-role">
                                    @php
                                        $roles = [
                                            1 => 'Administrador',
                                            2 => 'Gerente',
                                            3 => 'Vendedor',
                                            4 => 'Almacenero',
                                            5 => 'Contador'
                                        ];
                                    @endphp
                                    {{ $roles[Auth::user()->id_rol] ?? 'Usuario' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="header-stats-bar">
                    <div class="quick-stat">
                        <span class="stat-icon"><i class="fas fa-chart-line"></i></span>
                        <span class="stat-text">Sistema Online</span>
                        <span class="stat-status active"></span>
                    </div>
                    <div class="quick-stat">
                        <span class="stat-icon"><i class="fas fa-database"></i></span>
                        <span class="stat-text">BD Conectada</span>
                        <span class="stat-status active"></span>
                    </div>
                    <div class="quick-stat">
                        <span class="stat-icon"><i class="fas fa-shield-alt"></i></span>
                        <span class="stat-text">Secure SSL</span>
                        <span class="stat-status active"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas principales con diseño moderno -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card stats-card-primary">
                <div class="stats-card-body">
                    <div class="stats-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stats-content">
                        <div class="stats-number">{{ number_format($ventas_total) }}</div>
                        <div class="stats-label">Total Ventas</div>
                        <div class="stats-growth">
                            <span class="stats-today">Hoy: {{ $ventas_hoy }}</span>
                            <span class="stats-month">Mes: {{ $ventas_mes }}</span>
                        </div>
                    </div>
                </div>
                <div class="stats-footer">
                    <a href="{{ route('ventas.index') }}">Ver todas <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
        <!-- NUEVO: Totales por Moneda -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card stats-card-secondary">
                <div class="stats-card-body">
                    <div class="stats-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="stats-content">
                        <div class="stats-number">PEN: S/ {{ number_format($ingresos_total_pen, 2) }}</div>
                        <div class="stats-label">Ingresos en Soles</div>
                        <div class="stats-growth">
                            <span class="stats-today">Hoy: S/ {{ number_format($ingresos_hoy_pen, 2) }}</span>
                            <span class="stats-month">Mes: S/ {{ number_format($ingresos_mes_pen, 2) }}</span>
                        </div>
                    </div>
                </div>
                <div class="stats-footer">
                    <a href="{{ route('ventas.index') }}?moneda=PEN">Ver ventas PEN <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card stats-card-secondary">
                <div class="stats-card-body">
                    <div class="stats-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stats-content">
                        <div class="stats-number">USD: $ {{ number_format($ingresos_total_usd, 2) }}</div>
                        <div class="stats-label">Ingresos en Dólares</div>
                        <div class="stats-growth">
                            <span class="stats-today">Hoy: $ {{ number_format($ingresos_hoy_usd, 2) }}</span>
                            <span class="stats-month">Mes: $ {{ number_format($ingresos_mes_usd, 2) }}</span>
                        </div>
                    </div>
                </div>
                <div class="stats-footer">
                    <a href="{{ route('ventas.index') }}?moneda=USD">Ver ventas USD <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
        <!-- Panel de Ingresos Totales eliminado por solicitud del usuario -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card stats-card-info">
                <div class="stats-card-body">
                    <div class="stats-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stats-content">
                        <div class="stats-number">{{ number_format($clientes) }}</div>
                        <div class="stats-label">Clientes Registrados</div>
                        <div class="stats-growth">
                            <span class="stats-today">Productos: {{ number_format($productos) }}</span>
                            <span class="stats-month">Marcas: {{ number_format($monedas) }}</span>
                        </div>
                    </div>
                </div>
                <div class="stats-footer">
                    <a href="{{ route('clientes.index') }}">Gestionar <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card stats-card-warning">
                <div class="stats-card-body">
                    <div class="stats-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="stats-content">
                        <div class="stats-number">{{ number_format($alertas) }}</div>
                        <div class="stats-label">Alertas de Stock</div>
                        <div class="stats-growth">
                            <span class="stats-today">Pendientes: {{ $ventas_pendientes }}</span>
                            <span class="stats-month">Anuladas: {{ $ventas_anuladas }}</span>
                        </div>
                    </div>
                </div>
                <div class="stats-footer">
                    <a href="{{ route('productos.index') }}">Ver alertas <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas de Stock Bajo (calculadas en Blade) -->
    @php
        // Asegurar que $productos sea una colección
        if (!isset($productos) || !is_iterable($productos)) {
            $productos = collect();
        } else {
            $productos = collect($productos);
        }
        $productos_stock_bajo = $productos->filter(function($producto) {
            return isset($producto->stock_minimo) && isset($producto->stock_actual) && $producto->stock_actual < $producto->stock_minimo && $producto->stock_actual > 0;
        });
        $productos_sin_stock = $productos->filter(function($producto) {
            return isset($producto->stock_actual) && $producto->stock_actual <= 0;
        });
    @endphp
    @if($productos_stock_bajo->count() > 0 || $productos_sin_stock->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert-banner">
                <div class="alert-header">
                    <div class="alert-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="alert-content">
                        <h5 class="alert-title">⚠️ Alertas de Inventario</h5>
                        <p class="alert-description">
                            Tienes {{ $productos_stock_bajo->count() }} productos con stock bajo
                            @if($productos_sin_stock->count() > 0)
                                y {{ $productos_sin_stock->count() }} productos sin stock
                            @endif
                        </p>
                    </div>
                    <div class="alert-actions">
                        <a href="{{ route('productos.index') }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-warehouse me-1"></i>
                            Ver Inventario
                        </a>
                    </div>
                </div>
                @if($productos_stock_bajo->count() > 0)
                <div class="alert-products">
                    <div class="alert-products-header">
                        <h6><i class="fas fa-boxes me-2"></i>Productos con Stock Bajo (menor al mínimo)</h6>
                    </div>
                    <div class="alert-products-grid">
                        @foreach($productos_stock_bajo as $producto)
                        <div class="alert-product-item">
                            <div class="alert-product-info">
                                <div class="alert-product-name">{{ $producto->descripcion }}</div>
                                <div class="alert-product-code">{{ $producto->codigo }}</div>
                            </div>
                            <div class="alert-product-stock">
                                <span class="stock-badge stock-{{ $producto->stock_actual <= 3 ? 'critical' : 'low' }}">
                                    {{ $producto->stock_actual }} {{ $producto->unidad ?? 'unidades' }}
                                </span>
                            </div>
                            <div class="alert-product-actions">
                                <a href="{{ route('productos.index') }}?search={{ $producto->codigo }}" class="btn btn-sm btn-outline-primary" title="Ver producto">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Mejores clientes y productos más vendidos -->
    <div class="row mb-4">
        <!-- Top Clientes -->
        <div class="col-xl-6 mb-4">
            <div class="top-clients-card">
                <div class="top-clients-header">
                    <h5><i class="fas fa-crown me-2"></i>Top 5 Mejores Clientes</h5>
                    <span class="badge bg-success">{{ $top_clientes->count() }} clientes VIP</span>
                </div>
                <div class="top-clients-body">
                    @forelse($top_clientes as $index => $cliente_data)
                    <div class="client-item">
                        <div class="client-rank">
                            <span class="rank-crown">
                                @if($index == 0)
                                    <i class="fas fa-crown text-warning"></i>
                                @elseif($index == 1)
                                    <i class="fas fa-medal text-secondary"></i>
                                @elseif($index == 2)
                                    <i class="fas fa-award text-warning"></i>
                                @else
                                    <span class="rank-number">#{{ $index + 1 }}</span>
                                @endif
                            </span>
                        </div>
                        <div class="client-info">
                            <div class="client-name">
                                {{ $cliente_data->cliente->nombre ?? 'Cliente no encontrado' }}
                            </div>
                            <div class="client-document">
                                {{ $cliente_data->cliente->numero_documento ?? 'Sin documento' }}
                            </div>
                        </div>
                        <div class="client-stats">
                            <div class="client-purchases">{{ number_format($cliente_data->total_compras) }} compras</div>
                            <div class="client-amount">S/ {{ number_format($cliente_data->total_gastado, 2) }}</div>
                        </div>
                        <div class="client-progress">
                            @php
                                $max_gastado = $top_clientes->first()->total_gastado ?? 1;
                                $max_gastado = $max_gastado > 0 ? $max_gastado : 1;
                                $porcentaje = ($cliente_data->total_gastado / $max_gastado) * 100;
                            @endphp
                            <div class="progress">
                                <div class="progress-bar bg-success" style="width: {{ $porcentaje }}%"></div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="fas fa-users text-muted fa-3x mb-3"></i>
                        <p class="text-muted">No hay datos de clientes</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Top productos mejorado -->
        <div class="col-xl-6 mb-4">
            <div class="top-products-card enhanced">
                <div class="top-products-header">
                    <h5><i class="fas fa-trophy me-2"></i>Top 5 Productos Más Vendidos</h5>
                    <span class="badge bg-primary">{{ $top_productos->count() }} productos top</span>
                </div>
                <div class="top-products-body">
                    @forelse($top_productos as $index => $producto)
                    <div class="product-item enhanced">
                        <div class="product-rank">
                            @if($index == 0)
                                <span class="rank-trophy"><i class="fas fa-trophy text-warning"></i></span>
                            @elseif($index == 1)
                                <span class="rank-trophy"><i class="fas fa-medal text-secondary"></i></span>
                            @elseif($index == 2)
                                <span class="rank-trophy"><i class="fas fa-award text-warning"></i></span>
                            @else
                                <span class="rank-number">#{{ $index + 1 }}</span>
                            @endif
                        </div>
                        <div class="product-info">
                            <div class="product-name">{{ $producto->producto->descripcion ?? 'Producto no encontrado' }}</div>
                            <div class="product-code">{{ $producto->producto->codigo ?? 'N/A' }}</div>
                        </div>
                        <div class="product-stats enhanced">
                            <div class="product-quantity">{{ number_format($producto->total_vendido) }} unidades</div>
                            <div class="product-revenue">S/ {{ number_format($producto->ingresos_generados, 2) }}</div>
                        </div>
                        <div class="product-progress">
                            @php
                                $max_vendido = $top_productos->first()->total_vendido ?? 1;
                                $porcentaje = ($producto->total_vendido / $max_vendido) * 100;
                            @endphp
                            <div class="progress">
                                <div class="progress-bar bg-primary" style="width: {{ $porcentaje }}%"></div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="fas fa-box-open text-muted fa-3x mb-3"></i>
                        <p class="text-muted">No hay datos de productos vendidos</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard de Gráficos Mejorado -->
    <div class="dashboard-charts-grid">
        <!-- Gráfico Principal de Ventas -->
        <div class="main-chart-container">
            <div class="chart-card enhanced">
                <div class="chart-header">
                    <div class="chart-title-section">
                        <h5 class="chart-title">
                            <i class="fas fa-chart-area me-2"></i>
                            Tendencia de Ventas - Últimos 7 días
                        </h5>
                        <p class="chart-subtitle">Evolución diaria de ventas e ingresos</p>
                    </div>
                    <div class="chart-controls">
                        <div class="chart-metrics">
                                <div class="metric">
                                    <span class="metric-value">{{ number_format($ventas_semana ? array_sum(array_column($ventas_semana, 'ventas')) : 0) }}</span>
                                    <span class="metric-label">Ventas 7 días</span>
                                </div>
                                <div class="metric">
                                    <span class="metric-value">S/ {{ number_format($ventas_semana ? array_sum(array_column($ventas_semana, 'ingresos_pen')) : 0, 2) }}</span>
                                    <span class="metric-subvalue">$ {{ number_format($ventas_semana ? array_sum(array_column($ventas_semana, 'ingresos_usd')) : 0, 2) }}</span>
                                    <span class="metric-label">Ingresos 7 días (PEN / USD)</span>
                                </div>
                        </div>
                        <button class="btn btn-sm btn-outline-primary chart-refresh-btn" onclick="refreshChart('ventas')" title="Actualizar datos">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <!-- Totales por moneda (últimos 7 días) -->
                        <div class="chart-totals mt-2">
                            <span class="total-pen">S/ {{ number_format($ventas_semana ? array_sum(array_column($ventas_semana, 'ingresos_pen')) : 0, 2) }}</span>
                            <span class="total-usd">$ {{ number_format($ventas_semana ? array_sum(array_column($ventas_semana, 'ingresos_usd')) : 0, 2) }}</span>
                        </div>
                    </div>
                </div>
                <div class="chart-body">
                    <div class="chart-wrapper">
                        <canvas id="ventasChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Panel de Estados y Métricas -->
        <div class="side-charts-container">
            <!-- Estados de Ventas Mejorado -->
            <div class="chart-card compact">
                <div class="chart-header compact">
                    <h6 class="chart-title">
                        <i class="fas fa-chart-pie me-2"></i>
                        Estados de Ventas
                    </h6>
                </div>
                <div class="chart-body compact">
                    <div class="donut-chart-container">
                        <canvas id="estadosChart"></canvas>
                        <div class="donut-center">
                            <span class="donut-total">{{ $ventas_total }}</span>
                            <span class="donut-label">Total</span>
                        </div>
                    </div>
                    <div class="compact-legend">
                        <div class="legend-item">
                            <span class="legend-color success"></span>
                            <span class="legend-text">Aceptadas</span>
                            <span class="legend-value">{{ $ventas_aceptadas }}</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color warning"></span>
                            <span class="legend-text">Pendientes</span>
                            <span class="legend-value">{{ $ventas_pendientes }}</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color danger"></span>
                            <span class="legend-text">Anuladas</span>
                            <span class="legend-value">{{ $ventas_anuladas }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Métricas de Comprobantes -->
            <div class="metrics-card">
                <div class="metrics-header">
                    <h6><i class="fas fa-file-alt me-2"></i>Comprobantes</h6>
                </div>
                <div class="metrics-body">
                    <div class="metric-item">
                        <div class="metric-icon facturas">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <div class="metric-content">
                            <span class="metric-number">{{ number_format($facturas) }}</span>
                            <span class="metric-name">Facturas</span>
                        </div>
                    </div>
                    <div class="metric-item">
                        <div class="metric-icon boletas">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <div class="metric-content">
                            <span class="metric-number">{{ number_format($boletas) }}</span>
                            <span class="metric-name">Boletas</span>
                        </div>
                    </div>
                    <div class="metric-item">
                        <div class="metric-icon tickets">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <div class="metric-content">
                            <span class="metric-number">{{ number_format($tickets) }}</span>
                            <span class="metric-name">Tickets</span>
                        </div>
                    </div>
                    <div class="metric-item">
                        <div class="metric-icon cotizaciones">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <div class="metric-content">
                            <span class="metric-number">{{ number_format($cotizaciones) }}</span>
                            <span class="metric-name">Cotizaciones</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico de Evolución Mensual -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="chart-card enhanced">
                <div class="chart-header">
                    <div class="chart-title-section">
                        <h5 class="chart-title">
                            <i class="fas fa-chart-bar me-2"></i>
                            Evolución Mensual - Últimos 6 meses
                        </h5>
                        <p class="chart-subtitle">Comparativa de ventas e ingresos por mes</p>
                    </div>
                    <div class="chart-controls">
                        <div class="chart-metrics">
                                <div class="metric">
                                    <span class="metric-value">{{ number_format($estadisticas_mensuales ? array_sum(array_column($estadisticas_mensuales, 'ventas')) : 0) }}</span>
                                    <span class="metric-label">Ventas 6 meses</span>
                                </div>
                                <div class="metric">
                                    <span class="metric-value">S/ {{ number_format($estadisticas_mensuales ? array_sum(array_column($estadisticas_mensuales, 'ingresos_pen')) : 0, 2) }}</span>
                                    <span class="metric-subvalue">$ {{ number_format($estadisticas_mensuales ? array_sum(array_column($estadisticas_mensuales, 'ingresos_usd')) : 0, 2) }}</span>
                                    <span class="metric-label">Ingresos 6 meses (PEN / USD)</span>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="chart-body">
                    <div class="chart-wrapper">
                        <canvas id="mensualChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones rápidas modernizadas -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="quick-actions-card">
                <div class="quick-actions-header">
                    <h5><i class="fas fa-rocket me-2"></i>Acciones Rápidas</h5>
                    <p class="text-muted">Accede rápidamente a las funciones más utilizadas</p>
                </div>
                <div class="quick-actions-grid">
                    <a href="{{ route('ventas.create') }}" class="quick-action-item">
                        <div class="quick-action-icon nueva-venta">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="quick-action-content">
                            <div class="quick-action-title">Nueva Venta</div>
                            <div class="quick-action-desc">Crear comprobante</div>
                        </div>
                    </a>
                    
                    <a href="{{ route('clientes.create') }}" class="quick-action-item">
                        <div class="quick-action-icon nuevo-cliente">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="quick-action-content">
                            <div class="quick-action-title">Nuevo Cliente</div>
                            <div class="quick-action-desc">Registrar cliente</div>
                        </div>
                    </a>
                    
                    <a href="{{ route('productos.create') }}" class="quick-action-item">
                        <div class="quick-action-icon nuevo-producto">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="quick-action-content">
                            <div class="quick-action-title">Nuevo Producto</div>
                            <div class="quick-action-desc">Agregar inventario</div>
                        </div>
                    </a>
                    
                    <a href="{{ route('ventas.index') }}" class="quick-action-item">
                        <div class="quick-action-icon ver-ventas">
                            <i class="fas fa-list"></i>
                        </div>
                        <div class="quick-action-content">
                            <div class="quick-action-title">Ver Ventas</div>
                            <div class="quick-action-desc">Gestionar ventas</div>
                        </div>
                    </a>
                    
                    <a href="{{ route('productos.index') }}" class="quick-action-item">
                        <div class="quick-action-icon inventario">
                            <i class="fas fa-warehouse"></i>
                        </div>
                        <div class="quick-action-content">
                            <div class="quick-action-title">Inventario</div>
                            <div class="quick-action-desc">Gestionar stock</div>
                        </div>
                    </a>
                    
                    <a href="{{ route('clientes.index') }}" class="quick-action-item">
                        <div class="quick-action-icon clientes">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="quick-action-content">
                            <div class="quick-action-title">Clientes</div>
                            <div class="quick-action-desc">Base de datos</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>


</div>

<!-- Estilos CSS modernos -->
<style>
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    --warning-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    --danger-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    --hover-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    --border-radius: 15px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.chart-totals {
    display: flex;
    gap: 1rem;
    align-items: center;
    margin-left: 0.5rem;
}
.chart-totals .total-pen {
    font-weight: 700;
    color: #1f9d55;
}
.chart-totals .total-usd {
    font-weight: 700;
    color: #ff6f3b;
}
.metric-subvalue {
    display: block;
    color: #6b7280;
    font-weight: 600;
    margin-top: 4px;
}

/* Header Ultra Moderno del Dashboard */
.dashboard-header-ultra {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 25%, #667eea 50%, #764ba2 75%, #f093fb 100%);
    color: white;
    border-radius: 25px;
    box-shadow: 
        0 20px 40px rgba(102, 126, 234, 0.3),
        0 0 0 1px rgba(255, 255, 255, 0.1),
        inset 0 1px 0 rgba(255, 255, 255, 0.2);
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
    backdrop-filter: blur(20px);
}

.dashboard-header-ultra::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%);
    pointer-events: none;
}

.header-content-wrapper {
    padding: 2rem 2.5rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    z-index: 2;
}

.header-left-section {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.dashboard-logo-container {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.logo-circle {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    backdrop-filter: blur(15px);
    border: 2px solid rgba(255, 255, 255, 0.2);
    position: relative;
    z-index: 3;
    animation: logoFloat 3s ease-in-out infinite;
}

.logo-animation {
    position: absolute;
    width: 90px;
    height: 90px;
    border-radius: 50%;
    border: 2px solid rgba(255, 255, 255, 0.3);
    animation: logoPulse 2s ease-in-out infinite;
}

@keyframes logoFloat {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-5px); }
}

@keyframes logoPulse {
    0%, 100% { 
        transform: scale(1);
        opacity: 0.7;
    }
    50% { 
        transform: scale(1.1);
        opacity: 0.3;
    }
}

.header-text-content {
    display: flex;
    flex-direction: column;
}

.dashboard-title-ultra {
    font-size: 2.5rem;
    font-weight: 900;
    margin: 0 0 0.25rem 0;
    text-shadow: 
        0 2px 4px rgba(0, 0, 0, 0.3),
        0 4px 8px rgba(0, 0, 0, 0.2);
    background: linear-gradient(45deg, #ffffff 30%, #f0f0ff 70%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    letter-spacing: 1px;
}

.dashboard-subtitle-ultra {
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0 0 0.5rem 0;
    opacity: 0.95;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
}

.dashboard-description {
    margin: 0;
    font-size: 0.9rem;
    opacity: 0.8;
    font-weight: 400;
    letter-spacing: 0.5px;
}

.header-right-section {
    display: flex;
    align-items: center;
    gap: 2rem;
}

.time-weather-widget {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: rgba(255, 255, 255, 0.1);
    padding: 1rem 1.5rem;
    border-radius: 15px;
    backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.current-time {
    text-align: right;
}

.time-display {
    font-size: 1.5rem;
    font-weight: 700;
    font-family: 'Courier New', monospace;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
}

.date-display {
    font-size: 0.9rem;
    opacity: 0.9;
    font-weight: 500;
}

.weather-icon {
    font-size: 1.5rem;
    opacity: 0.9;
    animation: weatherFloat 4s ease-in-out infinite;
}

@keyframes weatherFloat {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-3px) rotate(5deg); }
}

.user-profile-mini {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: rgba(255, 255, 255, 0.1);
    padding: 0.75rem 1.25rem;
    border-radius: 50px;
    backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}

.user-profile-mini:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
}

.profile-avatar {
    width: 35px;
    height: 35px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
}

.profile-info {
    display: flex;
    flex-direction: column;
    text-align: right;
}

.profile-name {
    font-size: 0.85rem;
    font-weight: 600;
    line-height: 1;
}

.profile-role {
    font-size: 0.7rem;
    opacity: 0.8;
    font-weight: 400;
}

.header-stats-bar {
    padding: 1rem 2.5rem 2rem;
    display: flex;
    gap: 2rem;
    position: relative;
    z-index: 2;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.quick-stat {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 25px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    transition: all 0.3s ease;
}

.quick-stat:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-1px);
}

.stat-icon {
    font-size: 0.8rem;
    opacity: 0.9;
}

.stat-text {
    font-size: 0.75rem;
    font-weight: 500;
    opacity: 0.95;
}

.stat-status {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #00ff88;
    box-shadow: 0 0 10px rgba(0, 255, 136, 0.5);
    animation: statusBlink 2s ease-in-out infinite;
}

.stat-status.active {
    background: #00ff88;
    box-shadow: 0 0 10px rgba(0, 255, 136, 0.6);
}

@keyframes statusBlink {
    0%, 100% { 
        opacity: 1;
        transform: scale(1);
    }
    50% { 
        opacity: 0.7;
        transform: scale(1.1);
    }
}

/* Animaciones de partículas */
@keyframes particleFloat {
    0% {
        transform: translateY(0) translateX(0) rotate(0deg);
        opacity: 0;
    }
    10% {
        opacity: 1;
    }
    90% {
        opacity: 1;
    }
    100% {
        transform: translateY(-200px) translateX(20px) rotate(360deg);
        opacity: 0;
    }
}

.particle {
    animation: particleFloat 4s linear infinite;
}

/* Mejoras adicionales para responsividad */
@media (max-width: 1200px) {
    .dashboard-header-ultra {
        border-radius: 20px;
    }
    
    .header-content-wrapper {
        padding: 1.5rem 2rem 1rem;
        flex-direction: column;
        gap: 1.5rem;
        text-align: center;
    }
    
    .header-left-section {
        flex-direction: column;
        gap: 1rem;
    }
    
    .header-right-section {
        flex-direction: row;
        gap: 1rem;
        width: 100%;
        justify-content: center;
    }
    
    .header-stats-bar {
        padding: 1rem 2rem 1.5rem;
        flex-wrap: wrap;
        justify-content: center;
        gap: 1rem;
    }
    
    .dashboard-title-ultra {
        font-size: 2rem;
    }
    
    .logo-circle {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
    
    .logo-animation {
        width: 70px;
        height: 70px;
    }
}

@media (max-width: 768px) {
    .header-content-wrapper {
        padding: 1rem 1.5rem 0.5rem;
    }
    
    .header-right-section {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .time-weather-widget,
    .user-profile-mini {
        padding: 0.75rem 1rem;
    }
    
    .header-stats-bar {
        padding: 0.75rem 1.5rem 1.25rem;
        gap: 0.5rem;
    }
    
    .quick-stat {
        padding: 0.375rem 0.75rem;
        flex: 1;
        min-width: auto;
        justify-content: center;
    }
    
    .stat-text {
        font-size: 0.7rem;
    }
    
    .dashboard-title-ultra {
        font-size: 1.75rem;
    }
    
    .dashboard-subtitle-ultra {
        font-size: 1rem;
    }
    
    .dashboard-description {
        font-size: 0.8rem;
    }
}

/* Stats cards modernos */
.stats-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    transition: var(--transition);
    overflow: hidden;
    border: none;
    height: 100%;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--hover-shadow);
}

.stats-card-body {
    padding: 1.5rem;
    display: flex;
    align-items: center;
    position: relative;
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    margin-right: 1rem;
    flex-shrink: 0;
}

.stats-card-primary .stats-icon {
    background: var(--primary-gradient);
}

.stats-card-success .stats-icon {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.stats-card-info .stats-icon {
    background: var(--info-gradient);
}

.stats-card-warning .stats-icon {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
}

.stats-content {
    flex: 1;
}

.stats-number {
    font-size: 2rem;
    font-weight: 700;
    color: #2d3748;
    line-height: 1;
    margin-bottom: 0.25rem;
}

.stats-label {
    color: #718096;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
}

.stats-growth {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.stats-today,
.stats-month {
    font-size: 0.75rem;
    color: #a0aec0;
    font-weight: 500;
}

.stats-footer {
    padding: 1rem 1.5rem;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
}

.stats-footer a {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.875rem;
    transition: var(--transition);
}

.stats-footer a:hover {
    color: #764ba2;
    text-decoration: none;
}

/* Dashboard Charts Grid */
.dashboard-charts-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.main-chart-container {
    min-height: 400px;
}

.side-charts-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

/* Chart cards mejoradas */
.chart-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
    transition: var(--transition);
}

.chart-card.enhanced {
    border: 2px solid transparent;
    background: linear-gradient(white, white), linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    background-clip: padding-box, border-box;
}

.chart-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--hover-shadow);
}

.chart-card.compact {
    min-height: auto;
}

.chart-header {
    padding: 1.5rem;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    flex-shrink: 0;
}

.chart-header.compact {
    padding: 1rem 1.5rem;
}

.chart-title-section {
    flex: 1;
}

.chart-title {
    margin: 0 0 0.25rem 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: #2d3748;
}

.chart-subtitle {
    margin: 0;
    font-size: 0.875rem;
    color: #718096;
    font-weight: 400;
}

.chart-controls {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.chart-metrics {
    display: flex;
    gap: 1.5rem;
}

.metric {
    text-align: right;
}

.metric-value {
    display: block;
    font-size: 1.1rem;
    font-weight: 700;
    color: #2d3748;
    line-height: 1;
}

.metric-label {
    display: block;
    font-size: 0.75rem;
    color: #718096;
    margin-top: 0.25rem;
}

.chart-refresh-btn {
    border-radius: 8px;
    transition: var(--transition);
}

.chart-refresh-btn:hover {
    transform: rotate(180deg);
}

.chart-body {
    padding: 1.5rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.chart-body.compact {
    padding: 1rem 1.5rem;
}

.chart-wrapper {
    position: relative;
    height: 100%;
    min-height: 300px;
    flex: 1;
}

/* Donut Chart Específico */
.donut-chart-container {
    position: relative;
    max-width: 200px;
    margin: 0 auto 1rem;
}

.donut-center {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    pointer-events: none;
}

.donut-total {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    color: #2d3748;
    line-height: 1;
}

.donut-label {
    display: block;
    font-size: 0.75rem;
    color: #718096;
    margin-top: 0.25rem;
}

/* Leyendas Compactas */
.compact-legend {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.legend-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f1f3f4;
}

.legend-item:last-child {
    border-bottom: none;
}

.legend-color {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-right: 0.5rem;
}

.legend-color.success {
    background: #28a745;
}

.legend-color.warning {
    background: #ffc107;
}

.legend-color.danger {
    background: #dc3545;
}

.legend-text {
    flex: 1;
    font-size: 0.875rem;
    color: #2d3748;
}

.legend-value {
    font-weight: 600;
    color: #2d3748;
    font-size: 0.875rem;
}

/* Métricas Card */
.metrics-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    overflow: hidden;
    transition: var(--transition);
}

.metrics-header {
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #e9ecef;
}

.metrics-header h6 {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
    color: #2d3748;
}

.metrics-body {
    padding: 1rem;
}

.metric-item {
    display: flex;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f1f3f4;
    transition: var(--transition);
}

.metric-item:hover {
    background: #f8f9fa;
    margin: 0 -1rem;
    padding-left: 1rem;
    padding-right: 1rem;
    border-radius: 8px;
}

.metric-item:last-child {
    border-bottom: none;
}

.metric-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
    margin-right: 1rem;
}

.metric-icon.facturas {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.metric-icon.boletas {
    background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
}

.metric-icon.tickets {
    background: linear-gradient(135deg, #e83e8c 0%, #fd7e14 100%);
}

.metric-icon.cotizaciones {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
}

.metric-content {
    display: flex;
    flex-direction: column;
}

.metric-number {
    font-size: 1.25rem;
    font-weight: 700;
    color: #2d3748;
    line-height: 1;
}

.metric-name {
    font-size: 0.75rem;
    color: #718096;
    margin-top: 0.25rem;
}

/* Stats summary card */
.stats-summary-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    overflow: hidden;
}

.stats-summary-header {
    padding: 1.5rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #e9ecef;
}

.stats-summary-header h5 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: #2d3748;
}

.stats-summary-body {
    padding: 1rem;
}

.summary-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 1rem;
    transition: var(--transition);
    cursor: pointer;
}

.summary-item:hover {
    background: #f8f9fa;
    transform: translateX(5px);
}

.summary-item:last-child {
    margin-bottom: 0;
}

.summary-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    margin-right: 1rem;
}

.summary-icon.facturas {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.summary-icon.boletas {
    background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
}

.summary-icon.cotizaciones {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
}

.summary-content {
    flex: 1;
}

.summary-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2d3748;
    line-height: 1;
}

.summary-label {
    color: #718096;
    font-size: 0.875rem;
    font-weight: 500;
}

.summary-arrow {
    color: #a0aec0;
    transition: var(--transition);
}

.summary-item:hover .summary-arrow {
    color: #667eea;
    transform: translateX(5px);
}

/* Top products card */
.top-products-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    overflow: hidden;
}

.top-products-header {
    padding: 1.5rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.top-products-header h5 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: #2d3748;
}

.top-products-body {
    padding: 1rem;
}

.product-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 1rem;
    background: #f8f9fa;
    transition: var(--transition);
}

.product-item:hover {
    background: #e9ecef;
    transform: translateY(-2px);
}

.product-item:last-child {
    margin-bottom: 0;
}

.product-rank {
    margin-right: 1rem;
}

.rank-number {
    width: 30px;
    height: 30px;
    background: var(--primary-gradient);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.875rem;
}

.product-info {
    flex: 1;
    margin-right: 1rem;
}

.product-name {
    font-weight: 600;
    color: #2d3748;
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}

.product-code {
    color: #718096;
    font-size: 0.75rem;
}

.product-stats {
    text-align: right;
    margin-right: 1rem;
}

.product-quantity {
    font-size: 1.25rem;
    font-weight: 700;
    color: #2d3748;
    line-height: 1;
}

.product-label {
    color: #718096;
    font-size: 0.75rem;
}

.product-progress {
    width: 100px;
}

.progress {
    height: 8px;
    background: #e9ecef;
    border-radius: 4px;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    background: var(--primary-gradient);
    transition: var(--transition);
}

/* Quick actions */
.quick-actions-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    overflow: hidden;
}

.quick-actions-header {
    padding: 1.5rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #e9ecef;
    text-align: center;
}

.quick-actions-header h5 {
    margin: 0 0 0.5rem 0;
    font-size: 1.25rem;
    font-weight: 600;
    color: #2d3748;
}

.quick-actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    padding: 1.5rem;
}

.quick-action-item {
    display: flex;
    align-items: center;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 10px;
    text-decoration: none;
    transition: var(--transition);
    border: 2px solid transparent;
}

.quick-action-item:hover {
    background: white;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    transform: translateY(-3px);
    border-color: #667eea;
    text-decoration: none;
}

.quick-action-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    margin-right: 1rem;
}

.quick-action-icon.nueva-venta {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.quick-action-icon.nuevo-cliente {
    background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
}

.quick-action-icon.nuevo-producto {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
}

.quick-action-icon.ver-ventas {
    background: var(--primary-gradient);
}

.quick-action-icon.inventario {
    background: linear-gradient(135deg, #6610f2 0%, #e83e8c 100%);
}

.quick-action-icon.clientes {
    background: linear-gradient(135deg, #fd7e14 0%, #ff6b6b 100%);
}

.quick-action-content {
    flex: 1;
}

.quick-action-title {
    font-weight: 600;
    color: #2d3748;
    font-size: 1rem;
    margin-bottom: 0.25rem;
}

.quick-action-desc {
    color: #718096;
    font-size: 0.875rem;
}

/* Alertas de Stock */
.alert-banner {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    border: 2px solid #ffc107;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    overflow: hidden;
    animation: slideInUp 0.6s ease forwards;
}

.alert-header {
    display: flex;
    align-items: center;
    padding: 1.5rem;
    background: rgba(255, 193, 7, 0.1);
    border-bottom: 1px solid rgba(255, 193, 7, 0.3);
}

.alert-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    margin-right: 1rem;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.alert-content {
    flex: 1;
}

.alert-title {
    margin: 0 0 0.5rem 0;
    font-size: 1.25rem;
    font-weight: 700;
    color: #8a6d3b;
}

.alert-description {
    margin: 0;
    color: #8a6d3b;
    opacity: 0.9;
}

.alert-actions {
    margin-left: auto;
}

.alert-products {
    padding: 1.5rem;
    background: white;
}

.alert-products-header h6 {
    margin: 0 0 1rem 0;
    color: #8a6d3b;
    font-weight: 600;
}

.alert-products-grid {
    display: grid;
    gap: 1rem;
}

.alert-product-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #ffc107;
    transition: var(--transition);
}

.alert-product-item:hover {
    background: white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transform: translateX(5px);
}

.alert-product-info {
    flex: 1;
    margin-right: 1rem;
}

.alert-product-name {
    font-weight: 600;
    color: #2d3748;
    font-size: 0.9rem;
}

.alert-product-code {
    color: #718096;
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

.alert-product-stock {
    margin-right: 1rem;
}

.stock-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stock-badge.stock-critical {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
    animation: blink 1s infinite alternate;
}

.stock-badge.stock-low {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
    color: #8a6d3b;
}

@keyframes blink {
    from { opacity: 1; }
    to { opacity: 0.6; }
}

/* Top Clientes */
.top-clients-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    overflow: hidden;
}

.top-clients-header {
    padding: 1.5rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.top-clients-header h5 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: #2d3748;
}

.top-clients-body {
    padding: 1rem;
}

.client-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 1rem;
    background: #f8f9fa;
    transition: var(--transition);
    cursor: pointer;
}

.client-item:hover {
    background: #e9ecef;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.client-item:last-child {
    margin-bottom: 0;
}

.client-rank {
    margin-right: 1rem;
    min-width: 40px;
    text-align: center;
}

.rank-crown {
    font-size: 1.5rem;
}

.client-info {
    flex: 1;
    margin-right: 1rem;
}

.client-name {
    font-weight: 600;
    color: #2d3748;
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}

.client-document {
    color: #718096;
    font-size: 0.75rem;
}

.client-stats {
    text-align: right;
    margin-right: 1rem;
    min-width: 120px;
}

.client-purchases {
    font-size: 0.75rem;
    color: #718096;
    margin-bottom: 0.25rem;
}

.client-amount {
    font-size: 1rem;
    font-weight: 700;
    color: #28a745;
}

.client-progress {
    width: 80px;
}

/* Productos mejorados */
.top-products-card.enhanced {
    border: 2px solid transparent;
    background: linear-gradient(white, white), linear-gradient(135deg, #667eea, #764ba2);
    background-clip: padding-box, border-box;
}

.product-item.enhanced {
    position: relative;
    overflow: hidden;
}

.product-item.enhanced::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 4px;
    background: var(--primary-gradient);
}

.rank-trophy {
    font-size: 1.25rem;
}

.product-stats.enhanced {
    text-align: right;
    margin-right: 1rem;
    min-width: 140px;
}

.product-quantity {
    font-size: 0.875rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.25rem;
}

.product-revenue {
    font-size: 0.75rem;
    color: #28a745;
    font-weight: 600;
}

/* Badges mejorados */
.badge {
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    font-size: 0.7rem;
}

.bg-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
}

.bg-primary {
    background: var(--primary-gradient) !important;
}

.bg-warning {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%) !important;
    color: #8a6d3b !important;
}

/* Responsive Design Mejorado */
@media (max-width: 1200px) {
    .dashboard-charts-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .side-charts-container {
        flex-direction: row;
        gap: 1rem;
    }
    
    .chart-controls {
        flex-direction: column;
        align-items: flex-end;
        gap: 0.5rem;
    }
    
    .chart-metrics {
        flex-direction: column;
        gap: 0.5rem;
    }
}

@media (max-width: 768px) {
    .dashboard-header {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
        padding: 1.5rem;
    }
    
    .dashboard-title {
        font-size: 1.5rem;
    }
    
    .stats-number {
        font-size: 1.5rem;
    }
    
    .side-charts-container {
        flex-direction: column;
    }
    
    .chart-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
        padding: 1rem;
    }
    
    .chart-controls {
        align-items: center;
    }
    
    .chart-wrapper {
        min-height: 250px;
    }
    
    .donut-chart-container {
        max-width: 150px;
    }
    
    .metric-item {
        padding: 1rem 0;
    }
    
    .quick-actions-grid {
        grid-template-columns: 1fr;
    }
    
    .alert-header {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .alert-products-grid {
        grid-template-columns: 1fr;
    }
    
    .client-item,
    .product-item {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .client-progress,
    .product-progress {
        width: 100%;
        max-width: 200px;
    }
}

@media (max-width: 480px) {
    .dashboard-charts-grid {
        gap: 0.75rem;
    }
    
    .chart-body {
        padding: 1rem;
    }
    
    .chart-wrapper {
        min-height: 200px;
    }
    
    .donut-chart-container {
        max-width: 120px;
    }
    
    .metric-icon {
        width: 35px;
        height: 35px;
        font-size: 0.875rem;
    }
    
    .metric-number {
        font-size: 1.1rem;
    }
}

/* Animaciones Mejoradas */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(40px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-40px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(40px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

@keyframes shimmer {
    0% {
        background-position: -200px 0;
    }
    100% {
        background-position: calc(200px + 100%) 0;
    }
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

@keyframes bounceIn {
    0% {
        opacity: 0;
        transform: scale(0.3);
    }
    50% {
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.9);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

/* Aplicación de animaciones */
.stats-card {
    animation: slideInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    opacity: 0;
}

.chart-card {
    animation: fadeIn 0.8s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    opacity: 0;
}

.main-chart-container .chart-card {
    animation: slideInLeft 0.8s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}

.side-charts-container .chart-card {
    animation: slideInRight 0.8s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}

.quick-actions-card,
.top-products-card,
.top-clients-card {
    animation: slideInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    opacity: 0;
}

.alert-banner {
    animation: bounceIn 1s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}

/* Delays escalonados */
.stats-card:nth-child(1) { animation-delay: 0.1s; }
.stats-card:nth-child(2) { animation-delay: 0.2s; }
.stats-card:nth-child(3) { animation-delay: 0.3s; }
.stats-card:nth-child(4) { animation-delay: 0.4s; }

.main-chart-container { animation-delay: 0.5s; }
.side-charts-container { animation-delay: 0.7s; }

.quick-actions-card { animation-delay: 0.9s; }
.top-products-card { animation-delay: 1.0s; }
.top-clients-card { animation-delay: 1.1s; }

/* Efectos de hover mejorados */
.stats-card:hover .stats-icon {
    animation: pulse 0.6s ease-in-out;
}

.metric-item:hover .metric-icon {
    transform: scale(1.1) rotate(5deg);
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.quick-action-item:hover .quick-action-icon {
    animation: pulse 0.6s ease-in-out;
}

/* Loading shimmer effect */
.chart-wrapper::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        90deg,
        transparent,
        rgba(255, 255, 255, 0.4),
        transparent
    );
    background-size: 200px 100%;
    animation: shimmer 2s infinite;
    pointer-events: none;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.chart-wrapper.loading::before {
    opacity: 1;
}

/* Smooth transitions para elementos interactivos */
.legend-item,
.client-item,
.product-item,
.alert-product-item {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.legend-item:hover {
    transform: translateX(8px);
    background: rgba(102, 126, 234, 0.05);
    border-radius: 8px;
    padding: 0.5rem 1rem;
    margin: 0 -1rem;
}

/* Micro-animaciones para badges */
.badge {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

/* Progress bars animadas */
.progress-bar {
    transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.progress-bar::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    background-image: linear-gradient(
        -45deg,
        rgba(255, 255, 255, .2) 25%,
        transparent 25%,
        transparent 50%,
        rgba(255, 255, 255, .2) 50%,
        rgba(255, 255, 255, .2) 75%,
        transparent 75%,
        transparent
    );
    background-size: 50px 50px;
    animation: shimmer 2s linear infinite;
}

/* Scroll suave */
html {
    scroll-behavior: smooth;
}

/* Transiciones de estado */
.chart-refresh-btn {
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.chart-refresh-btn:active {
    transform: scale(0.9);
}
</style>

<!-- Scripts para gráficos optimizados -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuración global de Chart.js para mejor rendimiento
    Chart.defaults.font.family = "'Inter', 'system-ui', sans-serif";
    Chart.defaults.color = '#718096';
    Chart.defaults.borderColor = '#e2e8f0';
    Chart.defaults.backgroundColor = 'rgba(102, 126, 234, 0.1)';
    
    // Datos para gráficos
    const ventasData = @json($ventas_semana || []);
    // Preparar arrays por moneda y balances acumulados
    const ingresosPenArr = ventasData.map(item => Number(item.ingresos_pen || 0));
    const ingresosUsdArr = ventasData.map(item => Number(item.ingresos_usd || 0));
    function cumulative(arr) {
        const out = [];
        let s = 0;
        for (let v of arr) {
            s += Number(v) || 0;
            out.push(s);
        }
        return out;
    }
    const balancePen = cumulative(ingresosPenArr);
    const balanceUsd = cumulative(ingresosUsdArr);
    const estadisticasMensuales = @json($estadisticas_mensuales);
    
    // Configuración responsive común
    const commonResponsiveConfig = {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            intersect: false,
            mode: 'index'
        },
        elements: {
            point: {
                radius: 4,
                hoverRadius: 6
            }
        }
    };
    
    // Gráfico de ventas semanales mejorado
    const ventasCtx = document.getElementById('ventasChart').getContext('2d');
    const ventasChart = new Chart(ventasCtx, {
        type: 'line',
        data: {
            labels: ventasData.map(item => item.fecha),
            datasets: [{
                label: 'Ventas',
                data: ventasData.map(item => item.ventas),
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#667eea',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2
            }, {
                label: 'Ingresos PEN (S/)',
                data: ventasData.map(item => item.ingresos_pen || 0),
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.08)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#28a745',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                yAxisID: 'y1'
            }, {
                label: 'Ingresos USD ($)',
                data: ventasData.map(item => item.ingresos_usd || 0),
                borderColor: '#ff7f50',
                backgroundColor: 'rgba(255, 127, 80, 0.08)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#ff7f50',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                yAxisID: 'y2'
            }]

            // Añadimos líneas de balance acumulado por moneda
            .concat([{
                label: 'Balance Acumulado PEN (S/)',
                data: balancePen,
                borderColor: '#1f9d55',
                backgroundColor: 'transparent',
                borderWidth: 2,
                tension: 0.3,
                fill: false,
                borderDash: [6,4],
                pointRadius: 0,
                yAxisID: 'y1'
            }, {
                label: 'Balance Acumulado USD ($)',
                data: balanceUsd,
                borderColor: '#ff6f3b',
                backgroundColor: 'transparent',
                borderWidth: 2,
                tension: 0.3,
                fill: false,
                borderDash: [6,4],
                pointRadius: 0,
                yAxisID: 'y2'
            }])
        },
        options: {
            ...commonResponsiveConfig,
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 12
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    position: 'left',
                    grid: {
                        color: '#f1f5f9'
                    },
                    ticks: {
                        font: {
                            size: 12
                        }
                    }
                },
                // Left axis used for PEN (S/)
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    beginAtZero: true,
                    grid: {
                        color: '#f1f5f9'
                    },
                    ticks: {
                        font: {
                            size: 12
                        },
                        callback: function(value) {
                            return 'S/ ' + value.toLocaleString();
                        }
                    }
                },
                // Right axis used for USD ($)
                y2: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    beginAtZero: true,
                    grid: {
                        drawOnChartArea: false,
                    },
                    ticks: {
                        font: {
                            size: 12
                        },
                        callback: function(value) {
                            return '$ ' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'circle',
                        padding: 20,
                        font: {
                            size: 12,
                            weight: '600'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: '#667eea',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        title: function(context) {
                            return 'Fecha: ' + context[0].label;
                        },
                        label: function(context) {
                            // Normalizar label y valor
                            const label = context.dataset && context.dataset.label ? context.dataset.label : '';
                            const rawValue = (context.parsed && context.parsed.y !== undefined) ? context.parsed.y : context.parsed;
                            const value = (rawValue === null || rawValue === undefined) ? 0 : rawValue;

                            if (label.includes('Ventas')) {
                                return 'Ventas: ' + value + ' unidades';
                            }

                            if (label.includes('PEN')) {
                                return 'Ingresos: S/ ' + Number(value).toLocaleString();
                            }

                            if (label.includes('USD')) {
                                return 'Ingresos: $ ' + Number(value).toLocaleString();
                            }

                            // Fallback
                            return (label || context.label) + ': ' + value;
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
    
    // Gráfico de estados mejorado (donut)
    const estadosCtx = document.getElementById('estadosChart').getContext('2d');
    const estadosChart = new Chart(estadosCtx, {
        type: 'doughnut',
        data: {
            labels: ['Aceptadas', 'Pendientes', 'Anuladas'],
            datasets: [{
                data: [{{ $ventas_aceptadas }}, {{ $ventas_pendientes }}, {{ $ventas_anuladas }}],
                backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
                borderWidth: 4,
                borderColor: '#ffffff',
                hoverBorderWidth: 6,
                hoverBorderColor: '#ffffff'
            }]
        },
        options: {
            ...commonResponsiveConfig,
            cutout: '70%',
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: '#667eea',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                        }
                    }
                }
            },
            animation: {
                animateRotate: true,
                duration: 2000
            }
        }
    });
    
    // Gráfico mensual mejorado
    const mensualCtx = document.getElementById('mensualChart').getContext('2d');
    const mensualChart = new Chart(mensualCtx, {
        type: 'bar',
        data: {
            labels: estadisticasMensuales.map(item => item.mes),
            datasets: [{
                label: 'Ventas',
                data: estadisticasMensuales.map(item => item.ventas),
                backgroundColor: 'rgba(102, 126, 234, 0.8)',
                borderColor: '#667eea',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false
            }, {
                label: 'Ingresos PEN (S/)',
                data: estadisticasMensuales.map(item => item.ingresos_pen || 0),
                backgroundColor: 'rgba(40, 167, 69, 0.8)',
                borderColor: '#28a745',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
                yAxisID: 'y'
            }, {
                label: 'Ingresos USD ($)',
                data: estadisticasMensuales.map(item => item.ingresos_usd || 0),
                backgroundColor: 'rgba(255, 127, 80, 0.8)',
                borderColor: '#ff7f50',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
                yAxisID: 'y1'
            }]
        },
        options: {
            ...commonResponsiveConfig,
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 12
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    position: 'left',
                    grid: {
                        color: '#f1f5f9'
                    },
                    ticks: {
                        font: {
                            size: 12
                        }
                    }
                },
                // Usar y para PEN (S/), y1 para USD ($) en el mensual
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    beginAtZero: true,
                    grid: {
                        drawOnChartArea: false,
                    },
                    ticks: {
                        font: {
                            size: 12
                        },
                        callback: function(value) {
                            return '$ ' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'rect',
                        padding: 20,
                        font: {
                            size: 12,
                            weight: '600'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: '#667eea',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        title: function(context) {
                            return 'Mes: ' + context[0].label;
                        },
                        label: function(context) {
                            const value = (context.parsed && context.parsed.y !== undefined) ? context.parsed.y : context.parsed;
                            if (context.datasetIndex === 0) {
                                return 'Ventas: ' + value + ' unidades';
                            }

                            const label = context.dataset && context.dataset.label ? context.dataset.label : '';
                            if (label.includes('USD')) {
                                return 'Ingresos: $ ' + Number(value).toLocaleString();
                            }

                            // Por defecto asumimos PEN
                            return 'Ingresos: S/ ' + Number(value).toLocaleString();
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
    
    // Función para refrescar gráficos
    window.refreshChart = function(type) {
        const button = document.querySelector('.chart-refresh-btn');
        button.style.transform = 'rotate(360deg)';
        
        setTimeout(() => {
            button.style.transform = 'rotate(0deg)';
            // Aquí podrías hacer una llamada AJAX para actualizar los datos
            console.log('Actualizando gráfico:', type);
        }, 600);
    };
    
    // Responsividad mejorada
    function handleResize() {
        setTimeout(() => {
            ventasChart.resize();
            estadosChart.resize();
            mensualChart.resize();
        }, 100);
    }
    
    window.addEventListener('resize', handleResize);
    
    // Reloj en tiempo real mejorado
    function updateClock() {
        const now = new Date();
        const timeElement = document.getElementById('currentTime');
        const dateElement = document.getElementById('currentDate');
        
        if (timeElement) {
            const timeString = now.toLocaleTimeString('es-PE', { 
                hour12: false,
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            timeElement.textContent = timeString;
        }
        
        if (dateElement) {
            const dateString = now.toLocaleDateString('es-PE', { 
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
            dateElement.textContent = dateString;
        }
    }
    
    // Actualizar reloj cada segundo
    updateClock();
    setInterval(updateClock, 1000);
    
    // Efectos de partículas en el header (opcional)
    function createParticle() {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.cssText = `
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 50%;
            pointer-events: none;
            animation: particleFloat 4s linear infinite;
            left: ${Math.random() * 100}%;
            top: 100%;
            z-index: 1;
        `;
        
        document.querySelector('.dashboard-header-ultra').appendChild(particle);
        
        setTimeout(() => {
            particle.remove();
        }, 4000);
    }
    
    // Crear partículas ocasionalmente
    setInterval(createParticle, 3000);
    
    // Animaciones de entrada para stats cards
    const statsCards = document.querySelectorAll('.stats-card');
    statsCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
        
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 500 + (index * 100));
    });
    
    // Observador para elementos que entran en viewport (animaciones)
    const chartElements = document.querySelectorAll('.chart-card');
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    chartElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
    
    // Contador animado para números - DESACTIVADO para mejor rendimiento
    function animateValue(element, start, end, duration) {
        // Mostrar valor final inmediatamente sin animación
        const isCurrencySoles = element.textContent.includes('S/');
        const isCurrencyDollar = element.textContent.includes('$');
        if (isCurrencySoles) {
            element.textContent = `S/ ${Number(end).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
        } else if (isCurrencyDollar) {
            element.textContent = `$ ${Number(end).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
        } else {
            // si es un conteo (sin decimales) mostrar entero formateado
            if (Number.isInteger(Number(end))) {
                element.textContent = Number(end).toLocaleString();
            } else {
                element.textContent = Number(end).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
            }
        }
    }
    
    // Animar números cuando las cards entran en viewport
    const numberElements = document.querySelectorAll('.stats-number');
    const numberObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const element = entry.target;
                // Limpiar manteniendo punto decimal y signo negativo si existiera
                const raw = element.textContent || '';
                // Permitir dígitos, punto, coma y signo negativo; luego normalizar comas (miles)
                let cleaned = raw.replace(/[^\d.,-]/g, '').replace(/,/g, '');
                let targetValue = parseFloat(cleaned);
                if (isNaN(targetValue)) targetValue = 0;

                if (targetValue !== 0) {
                    // Mostrar valor inmediatamente sin animación
                    animateValue(element, 0, targetValue, 0);
                }
                
                numberObserver.unobserve(element);
            }
        });
    });
    
    numberElements.forEach(el => {
        numberObserver.observe(el);
    });
    
    // Efectos de hover mejorados para quick actions
    const quickActions = document.querySelectorAll('.quick-action-item');
    quickActions.forEach(action => {
        action.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px) scale(1.02)';
        });
        
        action.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    console.log('🚀 Dashboard Ultra Moderno cargado exitosamente');
});
</script>

@endsection