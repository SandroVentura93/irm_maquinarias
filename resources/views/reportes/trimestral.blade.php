@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header con Gradiente -->
    <div class="page-header-modern">
        <div class="header-content">
            <div class="header-info">
                <h1 class="header-title">
                    <i class="fas fa-chart-area me-3"></i>
                    Reporte Trimestral
                </h1>
                <p class="header-subtitle">Análisis de ventas y compras por trimestre</p>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card-modern mb-4">
        <div class="card-header-gradient">
            <i class="fas fa-filter"></i>
            <span>Seleccionar Período</span>
        </div>
        <div class="card-body-modern">
            <form method="GET" action="{{ route('trimestral') }}">
                <div class="row g-4">
                    <div class="col-md-5">
                        <div class="form-group-modern">
                            <label for="year" class="form-label-modern">
                                <i class="fas fa-calendar me-2"></i>
                                Año
                            </label>
                            <div class="input-icon-wrapper">
                                <i class="fas fa-calendar-day input-icon"></i>
                                <select name="year" id="year" class="form-control-modern">
                                    @for ($y = date('Y')-5; $y <= date('Y'); $y++)
                                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="form-group-modern">
                            <label for="quarter" class="form-label-modern">
                                <i class="fas fa-calendar-check me-2"></i>
                                Trimestre
                            </label>
                            <div class="input-icon-wrapper">
                                <i class="fas fa-th-large input-icon"></i>
                                <select name="quarter" id="quarter" class="form-control-modern">
                                    @for ($q = 1; $q <= 4; $q++)
                                        <option value="{{ $q }}" {{ $quarter == $q ? 'selected' : '' }}>
                                            Trimestre {{ $q }} ({{ ['Ene-Mar', 'Abr-Jun', 'Jul-Sep', 'Oct-Dic'][$q-1] }})
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn-primary-modern w-100">
                            <i class="fas fa-search me-2"></i>
                            Consultar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(isset($months_data))
    <!-- Botones de Exportación -->
    <div class="export-buttons mb-4">
        <a href="{{ route('trimestral.pdf', ['year' => $year, 'quarter' => $quarter]) }}" 
           class="btn-export pdf" 
           target="_blank">
            <i class="fas fa-file-pdf"></i>
            <span>Exportar PDF</span>
        </a>
        <!-- <a href="{{ route('trimestral.excel', ['year' => $year, 'quarter' => $quarter]) }}" 
           class="btn-export excel" 
           target="_blank">
            <i class="fas fa-file-excel"></i>
            <span>Exportar Excel</span>
        </a> -->
    </div>

    <!-- Cards de Meses -->
    <div class="row g-4 mb-4">
        @foreach($months_data as $index => $month)
        <div class="col-lg-4 col-md-6">
            <div class="month-card month-{{ $index + 1 }}">
                <div class="month-header">
                    <div class="month-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h5 class="month-title">{{ $month['name'] }}</h5>
                </div>
                <div class="month-body">
                    <div class="month-stat">
                        <span class="stat-icon ventas"><i class="fas fa-arrow-up"></i></span>
                        <div class="stat-details">
                            <span class="stat-label">Ventas</span>
                            <span class="stat-amount success">S/. {{ number_format($month['total_ventas'], 2) }}</span>
                        </div>
                    </div>
                    <div class="month-stat">
                        <span class="stat-icon compras"><i class="fas fa-arrow-down"></i></span>
                        <div class="stat-details">
                            <span class="stat-label">Compras</span>
                            <span class="stat-amount danger">S/. {{ number_format($month['total_compras'], 2) }}</span>
                        </div>
                    </div>
                    <div class="month-stat">
                        <span class="stat-icon ganancia"><i class="fas fa-chart-line"></i></span>
                        <div class="stat-details">
                            <span class="stat-label">Ganancia</span>
                            <span class="stat-amount primary">S/. {{ number_format($month['ganancia'], 2) }}</span>
                        </div>
                    </div>
                    <div class="month-divider"></div>
                    <div class="month-products">
                        <div class="product-info">
                            <i class="fas fa-cubes text-info"></i>
                            <span>{{ $month['cantidad_productos_vendidos'] }} vendidos</span>
                        </div>
                        <div class="product-info">
                            <i class="fas fa-shopping-bag text-warning"></i>
                            <span>{{ $month['cantidad_productos_comprados'] }} comprados</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Gráfica y Resumen -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card-modern">
                <div class="card-header-gradient">
                    <i class="fas fa-chart-bar"></i>
                    <span>Análisis Trimestral por Mes</span>
                </div>
                <div class="card-body-modern">
                    <canvas id="graficoTrimestral" height="320"></canvas>
                </div>
            </div>
        </div>

        <!-- Totales del Trimestre -->
        <div class="col-lg-4">
            @php
                $totalVentas = collect($months_data)->sum('total_ventas');
                $totalCompras = collect($months_data)->sum('total_compras');
                $totalGanancia = collect($months_data)->sum('ganancia');
                $totalProductosVendidos = collect($months_data)->sum('cantidad_productos_vendidos');
                $totalProductosComprados = collect($months_data)->sum('cantidad_productos_comprados');
            @endphp

            <div class="card-modern">
                <div class="card-header-gradient">
                    <i class="fas fa-calculator"></i>
                    <span>Totales del Trimestre</span>
                </div>
                <div class="card-body-modern">
                    <div class="financial-summary">
                        <div class="financial-item">
                            <div class="financial-icon ventas">
                                <i class="fas fa-arrow-up"></i>
                            </div>
                            <div class="financial-info">
                                <span class="financial-label">Total Ventas</span>
                                <span class="financial-value success">S/. {{ number_format($totalVentas, 2) }}</span>
                            </div>
                        </div>

                        <div class="financial-item">
                            <div class="financial-icon compras">
                                <i class="fas fa-arrow-down"></i>
                            </div>
                            <div class="financial-info">
                                <span class="financial-label">Total Compras</span>
                                <span class="financial-value danger">S/. {{ number_format($totalCompras, 2) }}</span>
                            </div>
                        </div>

                        <div class="financial-divider"></div>

                        <div class="financial-item total">
                            <div class="financial-icon ganancia">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="financial-info">
                                <span class="financial-label">Ganancia Total</span>
                                <span class="financial-value primary">S/. {{ number_format($totalGanancia, 2) }}</span>
                            </div>
                        </div>

                        <div class="profit-indicator {{ $totalGanancia >= 0 ? 'positive' : 'negative' }}">
                            <i class="fas fa-{{ $totalGanancia >= 0 ? 'check-circle' : 'exclamation-triangle' }}"></i>
                            <span>{{ $totalGanancia >= 0 ? 'Balance Positivo' : 'Balance Negativo' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Indicadores -->
            <div class="card-modern mt-4">
                <div class="card-header-gradient">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Indicadores</span>
                </div>
                <div class="card-body-modern">
                    <div class="indicator-list">
                        <div class="indicator-item">
                            <div class="indicator-label">
                                <i class="fas fa-percentage text-success"></i>
                                Margen Trimestral
                            </div>
                            <div class="indicator-value">
                                {{ $totalVentas > 0 ? number_format(($totalGanancia / $totalVentas) * 100, 1) : 0 }}%
                            </div>
                        </div>

                        <div class="indicator-item">
                            <div class="indicator-label">
                                <i class="fas fa-calendar-day text-info"></i>
                                Promedio Mensual
                            </div>
                            <div class="indicator-value">
                                S/. {{ number_format($totalVentas / 3, 2) }}
                            </div>
                        </div>

                        <div class="indicator-item">
                            <div class="indicator-label">
                                <i class="fas fa-cubes text-primary"></i>
                                Total Vendidos
                            </div>
                            <div class="indicator-value">
                                {{ $totalProductosVendidos }}
                            </div>
                        </div>

                        <div class="indicator-item">
                            <div class="indicator-label">
                                <i class="fas fa-shopping-bag text-warning"></i>
                                Total Comprados
                            </div>
                            <div class="indicator-value">
                                {{ $totalProductosComprados }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Productos -->
    <div class="card-modern">
        <div class="card-header-gradient">
            <i class="fas fa-table"></i>
            <span>Detalle de Productos por Mes</span>
        </div>
        <div class="card-body-modern">
            <div class="table-responsive">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th><i class="fas fa-calendar-alt me-2"></i>Mes</th>
                            <th><i class="fas fa-box me-2"></i>Producto</th>
                            <th class="text-center"><i class="fas fa-arrow-up me-2"></i>Cant. Vendida</th>
                            <th class="text-end"><i class="fas fa-dollar-sign me-2"></i>Total Ventas</th>
                            <th class="text-center"><i class="fas fa-arrow-down me-2"></i>Cant. Comprada</th>
                            <th class="text-end"><i class="fas fa-dollar-sign me-2"></i>Total Compras</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($months_data as $month)
                            @foreach($month['productos'] as $producto)
                            <tr>
                                <td><span class="month-badge">{{ $month['name'] }}</span></td>
                                <td><strong>{{ $producto['nombre'] }}</strong></td>
                                <td class="text-center"><span class="badge-info">{{ $producto['cantidad_vendida'] }}</span></td>
                                <td class="text-end"><span class="text-success fw-bold">S/. {{ number_format($producto['total_venta'], 2) }}</span></td>
                                <td class="text-center"><span class="badge-warning">{{ $producto['cantidad_comprada'] }}</span></td>
                                <td class="text-end"><span class="text-danger fw-bold">S/. {{ number_format($producto['total_compra'], 2) }}</span></td>
                            </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Información del Período -->
    <div class="row g-4 mt-4">
        <div class="col-12">
            <div class="card-modern card-info">
                <div class="card-body-modern">
                    <div class="period-info">
                        <div class="period-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="period-content">
                            <h6 class="period-title">Período Analizado</h6>
                            <p class="period-text">
                                <strong>Año:</strong> {{ $year }} &nbsp;|&nbsp;
                                <strong>Trimestre:</strong> {{ $quarter }} ({{ ['Enero - Marzo', 'Abril - Junio', 'Julio - Septiembre', 'Octubre - Diciembre'][$quarter-1] }})
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
    /* Tema de Reportes - Tono Morado/Púrpura */
    :root {
        --reportes-gradient: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        --ventas-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
        --compras-gradient: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        --productos-gradient: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        --ganancia-gradient: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }

    /* Header Moderno */
    .page-header-modern {
        background: var(--reportes-gradient);
        padding: 32px;
        border-radius: 20px;
        margin-bottom: 32px;
        box-shadow: 0 8px 32px rgba(139, 92, 246, 0.3);
        position: relative;
        overflow: hidden;
    }

    .page-header-modern::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        border-radius: 50%;
    }

    .header-content {
        position: relative;
        z-index: 1;
    }

    .header-title {
        color: white;
        font-size: 32px;
        font-weight: 800;
        margin: 0;
        display: flex;
        align-items: center;
    }

    .header-subtitle {
        color: rgba(255,255,255,0.9);
        margin: 8px 0 0 0;
        font-size: 16px;
    }

    /* Card Moderno */
    .card-modern {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(139, 92, 246, 0.08);
        margin-bottom: 0;
        overflow: hidden;
        border: none;
    }

    .card-header-gradient {
        background: var(--reportes-gradient);
        padding: 20px 24px;
        color: white;
        font-weight: 700;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .card-body-modern {
        padding: 32px;
    }

    /* Card Info */
    .card-info {
        border: 2px solid #e9d5ff;
        background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%);
    }

    .period-info {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .period-icon {
        width: 56px;
        height: 56px;
        background: var(--reportes-gradient);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        flex-shrink: 0;
    }

    .period-content {
        flex: 1;
    }

    .period-title {
        font-size: 18px;
        font-weight: 700;
        color: #6b21a8;
        margin: 0 0 8px 0;
    }

    .period-text {
        font-size: 15px;
        color: #7c3aed;
        margin: 0;
    }

    /* Form Group Moderno */
    .form-group-modern {
        margin-bottom: 0;
    }

    .form-label-modern {
        display: block;
        font-weight: 600;
        color: #475569;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .input-icon-wrapper {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #8b5cf6;
        font-size: 16px;
        z-index: 1;
    }

    .form-control-modern {
        width: 100%;
        padding: 14px 16px 14px 48px;
        border: 2px solid #e9d5ff;
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s ease;
        background: #f8fafc;
    }

    .form-control-modern:focus {
        outline: none;
        border-color: #8b5cf6;
        box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1);
        background: white;
    }

    /* Botones */
    .btn-primary-modern {
        padding: 14px 32px;
        background: var(--reportes-gradient);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 16px rgba(139, 92, 246, 0.3);
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-primary-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(139, 92, 246, 0.4);
    }

    /* Botones de Exportación */
    .export-buttons {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
    }

    .btn-export {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 14px 28px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 15px;
        text-decoration: none;
        transition: all 0.3s ease;
        color: white;
    }

    .btn-export.pdf {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        box-shadow: 0 4px 16px rgba(239, 68, 68, 0.3);
    }

    .btn-export.pdf:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(239, 68, 68, 0.4);
        color: white;
    }

    .btn-export.excel {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        box-shadow: 0 4px 16px rgba(16, 185, 129, 0.3);
    }

    .btn-export.excel:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(16, 185, 129, 0.4);
        color: white;
    }

    .btn-export i {
        font-size: 18px;
    }

    /* Cards de Meses */
    .month-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border-top: 4px solid;
    }

    .month-card.month-1 {
        border-top-color: #8b5cf6;
    }

    .month-card.month-2 {
        border-top-color: #a78bfa;
    }

    .month-card.month-3 {
        border-top-color: #c4b5fd;
    }

    .month-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 32px rgba(139, 92, 246, 0.2);
    }

    .month-header {
        background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%);
        padding: 20px 24px;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .month-icon {
        width: 48px;
        height: 48px;
        background: var(--reportes-gradient);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
    }

    .month-title {
        font-size: 20px;
        font-weight: 700;
        color: #6b21a8;
        margin: 0;
    }

    .month-body {
        padding: 24px;
    }

    .month-stat {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 12px 0;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: white;
        flex-shrink: 0;
    }

    .stat-icon.ventas {
        background: var(--ventas-gradient);
    }

    .stat-icon.compras {
        background: var(--compras-gradient);
    }

    .stat-icon.ganancia {
        background: var(--reportes-gradient);
    }

    .stat-details {
        flex: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stat-label {
        font-size: 14px;
        color: #64748b;
        font-weight: 600;
    }

    .stat-amount {
        font-size: 16px;
        font-weight: 800;
    }

    .stat-amount.success {
        color: #10b981;
    }

    .stat-amount.danger {
        color: #ef4444;
    }

    .stat-amount.primary {
        color: #8b5cf6;
    }

    .month-divider {
        height: 2px;
        background: linear-gradient(90deg, transparent 0%, #e2e8f0 50%, transparent 100%);
        margin: 16px 0;
    }

    .month-products {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .product-info {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        color: #475569;
        font-weight: 600;
    }

    /* Resumen Financiero */
    .financial-summary {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .financial-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        background: #f8fafc;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .financial-item:hover {
        background: #f1f5f9;
        transform: translateX(5px);
    }

    .financial-item.total {
        background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%);
        border: 2px solid #e9d5ff;
    }

    .financial-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: white;
        flex-shrink: 0;
    }

    .financial-icon.ventas {
        background: var(--ventas-gradient);
    }

    .financial-icon.compras {
        background: var(--compras-gradient);
    }

    .financial-icon.ganancia {
        background: var(--reportes-gradient);
    }

    .financial-info {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .financial-label {
        font-size: 13px;
        color: #64748b;
        font-weight: 600;
    }

    .financial-value {
        font-size: 20px;
        font-weight: 800;
    }

    .financial-value.success {
        color: #10b981;
    }

    .financial-value.danger {
        color: #ef4444;
    }

    .financial-value.primary {
        color: #8b5cf6;
    }

    .financial-divider {
        height: 2px;
        background: linear-gradient(90deg, transparent 0%, #e2e8f0 50%, transparent 100%);
        margin: 8px 0;
    }

    .profit-indicator {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 14px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14px;
    }

    .profit-indicator.positive {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
        border: 2px solid rgba(16, 185, 129, 0.3);
    }

    .profit-indicator.negative {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border: 2px solid rgba(239, 68, 68, 0.3);
    }

    /* Indicadores de Rendimiento */
    .indicator-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .indicator-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px;
        background: #f8fafc;
        border-radius: 10px;
        border-left: 4px solid #8b5cf6;
    }

    .indicator-label {
        font-size: 14px;
        color: #475569;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .indicator-value {
        font-size: 16px;
        font-weight: 800;
        color: #1e293b;
    }

    /* Tabla Moderna */
    .table-modern {
        width: 100%;
        margin: 0;
    }

    .table-modern thead {
        background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%);
    }

    .table-modern thead th {
        padding: 16px;
        font-weight: 700;
        color: #6b21a8;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
    }

    .table-modern tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.2s ease;
    }

    .table-modern tbody tr:hover {
        background: #faf5ff;
    }

    .table-modern tbody td {
        padding: 16px;
        color: #475569;
        font-size: 14px;
    }

    .month-badge {
        display: inline-block;
        background: var(--reportes-gradient);
        color: white;
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
    }

    .badge-info {
        display: inline-block;
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
        padding: 4px 12px;
        border-radius: 6px;
        font-weight: 700;
    }

    .badge-warning {
        display: inline-block;
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
        padding: 4px 12px;
        border-radius: 6px;
        font-weight: 700;
    }

    /* Responsive */
    @media (max-width: 767px) {
        .export-buttons {
            flex-direction: column;
        }

        .btn-export {
            width: 100%;
            justify-content: center;
        }

        .period-info {
            flex-direction: column;
            text-align: center;
        }

        .table-modern {
            font-size: 12px;
        }

        .table-modern thead th,
        .table-modern tbody td {
            padding: 12px 8px;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    window.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('graficoTrimestral').getContext('2d');
        
        var labels = [
            @foreach($months_data as $month)
                '{{ $month['name'] }}',
            @endforeach
        ];
        
        var ventas = [
            @foreach($months_data as $month)
                {{ $month['total_ventas'] }},
            @endforeach
        ];
        
        var compras = [
            @foreach($months_data as $month)
                {{ $month['total_compras'] }},
            @endforeach
        ];
        
        var ganancias = [
            @foreach($months_data as $month)
                {{ $month['ganancia'] }},
            @endforeach
        ];

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Ventas',
                        data: ventas,
                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                        borderColor: '#10b981',
                        borderWidth: 2,
                        borderRadius: 8,
                    },
                    {
                        label: 'Compras',
                        data: compras,
                        backgroundColor: 'rgba(239, 68, 68, 0.8)',
                        borderColor: '#ef4444',
                        borderWidth: 2,
                        borderRadius: 8,
                    },
                    {
                        label: 'Ganancia',
                        data: ganancias,
                        backgroundColor: 'rgba(139, 92, 246, 0.8)',
                        borderColor: '#8b5cf6',
                        borderWidth: 2,
                        borderRadius: 8,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 16,
                        titleFont: {
                            size: 16,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 14
                        },
                        borderColor: '#8b5cf6',
                        borderWidth: 2,
                        cornerRadius: 10,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': S/. ' + context.parsed.y.toLocaleString('es-PE', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            }
                        }
                    }
                },
                animation: {
                    duration: 1500,
                    easing: 'easeInOutQuart'
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            color: '#475569'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f1f5f9',
                            lineWidth: 2
                        },
                        ticks: {
                            font: {
                                size: 13,
                                weight: '600'
                            },
                            color: '#64748b',
                            callback: function(value) {
                                return 'S/. ' + value.toLocaleString('es-PE');
                            }
                        }
                    }
                }
            }
        });
    });
</script>

@endsection
