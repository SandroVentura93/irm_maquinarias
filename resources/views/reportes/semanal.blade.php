@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header con Gradiente -->
    <div class="page-header-modern">
        <div class="header-content">
            <div class="header-info">
                <h1 class="header-title">
                    <i class="fas fa-calendar-week me-3"></i>
                    Reporte Semanal
                </h1>
                <p class="header-subtitle">Análisis de ventas y compras por semana</p>
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
            <form method="GET" id="form-semanal">
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="form-group-modern">
                            <label for="year" class="form-label-modern">
                                <i class="fas fa-calendar-alt me-2"></i>
                                Año
                            </label>
                            <div class="input-icon-wrapper">
                                <i class="fas fa-calendar input-icon"></i>
                                <input type="number" 
                                       name="year" 
                                       id="year" 
                                       class="form-control-modern" 
                                       value="{{ request('year', date('Y')) }}" 
                                       min="2020" 
                                       max="{{ date('Y') }}" 
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group-modern">
                            <label for="month" class="form-label-modern">
                                <i class="fas fa-calendar-day me-2"></i>
                                Mes
                            </label>
                            <div class="input-icon-wrapper">
                                <i class="fas fa-calendar-check input-icon"></i>
                                <select name="month" id="month" class="form-control-modern" required>
                                    @for($m=1; $m<=12; $m++)
                                        <option value="{{ sprintf('%02d', $m) }}" {{ request('month', date('m')) == sprintf('%02d', $m) ? 'selected' : '' }}>
                                            {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group-modern">
                            <label for="week" class="form-label-modern">
                                <i class="fas fa-calendar-week me-2"></i>
                                Semana
                            </label>
                            <div class="input-icon-wrapper">
                                <i class="fas fa-list-ol input-icon"></i>
                                <select name="week" id="week" class="form-control-modern" required>
                                    <option value="">Seleccione una semana</option>
                                    @if(isset($weeks_list) && is_array($weeks_list))
                                        @foreach($weeks_list as $w)
                                            <option value="{{ $w['number'] }}" {{ request('week') == $w['number'] ? 'selected' : '' }}>
                                                Semana {{ $w['number'] }} ({{ $w['start'] }} - {{ $w['end'] }})
                                            </option>
                                        @endforeach
                                    @endif
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

    <!-- Botones de Exportación -->
    <div class="export-buttons mb-4">
        <a href="/semanal/pdf?year={{ request('year') }}&month={{ request('month') }}&week={{ request('week') }}" 
           class="btn-export pdf" 
           target="_blank">
            <i class="fas fa-file-pdf"></i>
            <span>Exportar PDF</span>
        </a>
        <!-- <a href="/semanal/excel?year={{ request('year') }}&month={{ request('month') }}&week={{ request('week') }}" 
           class="btn-export excel" 
           target="_blank">
            <i class="fas fa-file-excel"></i>
            <span>Exportar Excel</span>
        </a> -->
    </div>

    <!-- Dashboard de Estadísticas -->
    <div class="row g-4 mb-4">
        <!-- Total Vendido -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card ventas">
                <div class="stat-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Total Vendido</span>
                    <span class="stat-value">S/. {{ number_format($total_ventas, 2) }}</span>
                    <span class="stat-meta">Esta semana</span>
                </div>
                <div class="stat-badge success">
                    <i class="fas fa-arrow-up"></i>
                </div>
            </div>
        </div>

        <!-- Total Comprado -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card compras">
                <div class="stat-icon">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Total Comprado</span>
                    <span class="stat-value">S/. {{ number_format($total_compras, 2) }}</span>
                    <span class="stat-meta">Esta semana</span>
                </div>
                <div class="stat-badge danger">
                    <i class="fas fa-arrow-down"></i>
                </div>
            </div>
        </div>

        <!-- Productos Vendidos -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card productos-vendidos">
                <div class="stat-icon">
                    <i class="fas fa-cubes"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Productos Vendidos</span>
                    <span class="stat-value">{{ $cantidad_productos_vendidos }}</span>
                    <span class="stat-meta">Unidades</span>
                </div>
                <div class="stat-badge info">
                    <i class="fas fa-chart-bar"></i>
                </div>
            </div>
        </div>

        <!-- Productos Comprados -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card productos-comprados">
                <div class="stat-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Productos Comprados</span>
                    <span class="stat-value">{{ $cantidad_productos_comprados }}</span>
                    <span class="stat-meta">Unidades</span>
                </div>
                <div class="stat-badge warning">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfica y Resumen -->
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card-modern">
                <div class="card-header-gradient">
                    <i class="fas fa-chart-bar"></i>
                    <span>Análisis Semanal de Ventas vs Compras</span>
                </div>
                <div class="card-body-modern">
                    <canvas id="graficoSemanal" height="320"></canvas>
                </div>
            </div>
        </div>

        <!-- Resumen Financiero -->
        <div class="col-lg-4">
            <div class="card-modern">
                <div class="card-header-gradient">
                    <i class="fas fa-wallet"></i>
                    <span>Resumen Financiero</span>
                </div>
                <div class="card-body-modern">
                    <div class="financial-summary">
                        <div class="financial-item">
                            <div class="financial-icon ventas">
                                <i class="fas fa-arrow-up"></i>
                            </div>
                            <div class="financial-info">
                                <span class="financial-label">Ingresos Semanales</span>
                                <span class="financial-value success">S/. {{ number_format($total_ventas, 2) }}</span>
                            </div>
                        </div>

                        <div class="financial-item">
                            <div class="financial-icon compras">
                                <i class="fas fa-arrow-down"></i>
                            </div>
                            <div class="financial-info">
                                <span class="financial-label">Egresos Semanales</span>
                                <span class="financial-value danger">S/. {{ number_format($total_compras, 2) }}</span>
                            </div>
                        </div>

                        <div class="financial-divider"></div>

                        <div class="financial-item total">
                            <div class="financial-icon ganancia">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="financial-info">
                                <span class="financial-label">Ganancia Semanal</span>
                                <span class="financial-value primary">S/. {{ number_format($ganancia, 2) }}</span>
                            </div>
                        </div>

                        <div class="profit-indicator {{ $ganancia >= 0 ? 'positive' : 'negative' }}">
                            <i class="fas fa-{{ $ganancia >= 0 ? 'check-circle' : 'exclamation-triangle' }}"></i>
                            <span>{{ $ganancia >= 0 ? 'Balance Positivo' : 'Balance Negativo' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Indicadores de Rendimiento -->
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
                                Margen Semanal
                            </div>
                            <div class="indicator-value">
                                {{ $total_ventas > 0 ? number_format(($ganancia / $total_ventas) * 100, 1) : 0 }}%
                            </div>
                        </div>

                        <div class="indicator-item">
                            <div class="indicator-label">
                                <i class="fas fa-calendar-day text-info"></i>
                                Promedio Diario
                            </div>
                            <div class="indicator-value">
                                S/. {{ number_format($total_ventas / 7, 2) }}
                            </div>
                        </div>

                        <div class="indicator-item">
                            <div class="indicator-label">
                                <i class="fas fa-exchange-alt text-warning"></i>
                                Rotación
                            </div>
                            <div class="indicator-value">
                                {{ $cantidad_productos_vendidos }} / {{ $cantidad_productos_comprados }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Información del Período -->
    @if(request('week'))
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
                                <strong>Año:</strong> {{ request('year') }} &nbsp;|&nbsp;
                                <strong>Mes:</strong> {{ DateTime::createFromFormat('!m', request('month'))->format('F') }} &nbsp;|&nbsp;
                                <strong>Semana:</strong> {{ request('week') }}
                                @if(isset($weeks_list) && is_array($weeks_list))
                                    @foreach($weeks_list as $w)
                                        @if($w['number'] == request('week'))
                                            ({{ $w['start'] }} - {{ $w['end'] }})
                                        @endif
                                    @endforeach
                                @endif
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
    /* Tema de Reportes - Tono Azul/Verde */
    :root {
        --reportes-gradient: linear-gradient(135deg, #14b8a6 0%, #0891b2 100%);
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
        box-shadow: 0 8px 32px rgba(20, 184, 166, 0.3);
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
        box-shadow: 0 4px 20px rgba(20, 184, 166, 0.08);
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
        border: 2px solid #ccfbf1;
        background: linear-gradient(135deg, #f0fdfa 0%, #ecfeff 100%);
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
        color: #0f766e;
        margin: 0 0 8px 0;
    }

    .period-text {
        font-size: 15px;
        color: #0d9488;
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
        color: #14b8a6;
        font-size: 16px;
        z-index: 1;
    }

    .form-control-modern {
        width: 100%;
        padding: 14px 16px 14px 48px;
        border: 2px solid #ccfbf1;
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s ease;
        background: #f8fafc;
    }

    .form-control-modern:focus {
        outline: none;
        border-color: #14b8a6;
        box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.1);
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
        box-shadow: 0 4px 16px rgba(20, 184, 166, 0.3);
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-primary-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(20, 184, 166, 0.4);
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

    /* Cards de Estadísticas */
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        display: flex;
        align-items: center;
        gap: 20px;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 32px rgba(0,0,0,0.12);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
    }

    .stat-card.ventas::before {
        background: var(--ventas-gradient);
    }

    .stat-card.compras::before {
        background: var(--compras-gradient);
    }

    .stat-card.productos-vendidos::before {
        background: var(--productos-gradient);
    }

    .stat-card.productos-comprados::before {
        background: var(--ganancia-gradient);
    }

    .stat-icon {
        width: 64px;
        height: 64px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
        flex-shrink: 0;
    }

    .stat-card.ventas .stat-icon {
        background: var(--ventas-gradient);
    }

    .stat-card.compras .stat-icon {
        background: var(--compras-gradient);
    }

    .stat-card.productos-vendidos .stat-icon {
        background: var(--productos-gradient);
    }

    .stat-card.productos-comprados .stat-icon {
        background: var(--ganancia-gradient);
    }

    .stat-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .stat-label {
        font-size: 14px;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 800;
        color: #1e293b;
    }

    .stat-meta {
        font-size: 12px;
        color: #94a3b8;
        font-weight: 500;
    }

    .stat-badge {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .stat-badge.success {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .stat-badge.danger {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .stat-badge.info {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .stat-badge.warning {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
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
        background: linear-gradient(135deg, #ecfeff 0%, #cffafe 100%);
        border: 2px solid #a5f3fc;
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
        color: #0891b2;
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
        border-left: 4px solid #14b8a6;
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

    /* Responsive */
    @media (max-width: 767px) {
        .stat-card {
            flex-direction: column;
            text-align: center;
        }

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
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Auto-submit al cambiar año o mes
    document.getElementById('month').addEventListener('change', function() {
        document.getElementById('form-semanal').submit();
    });
    
    document.getElementById('year').addEventListener('change', function() {
        document.getElementById('form-semanal').submit();
    });

    // Gráfico
    const ctx = document.getElementById('graficoSemanal').getContext('2d');
    
    const gradient1 = ctx.createLinearGradient(0, 0, 0, 400);
    gradient1.addColorStop(0, 'rgba(16, 185, 129, 0.8)');
    gradient1.addColorStop(1, 'rgba(16, 185, 129, 0.2)');
    
    const gradient2 = ctx.createLinearGradient(0, 0, 0, 400);
    gradient2.addColorStop(0, 'rgba(239, 68, 68, 0.8)');
    gradient2.addColorStop(1, 'rgba(239, 68, 68, 0.2)');
    
    const gradient3 = ctx.createLinearGradient(0, 0, 0, 400);
    gradient3.addColorStop(0, 'rgba(20, 184, 166, 0.8)');
    gradient3.addColorStop(1, 'rgba(20, 184, 166, 0.2)');

    const graficoSemanal = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Ventas', 'Compras', 'Ganancia'],
            datasets: [{
                label: 'Monto (S/.)',
                data: [{{ $total_ventas }}, {{ $total_compras }}, {{ $ganancia }}],
                backgroundColor: [gradient1, gradient2, gradient3],
                borderColor: ['#10b981', '#ef4444', '#14b8a6'],
                borderWidth: 3,
                borderRadius: 12,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
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
                    borderColor: '#14b8a6',
                    borderWidth: 2,
                    cornerRadius: 10,
                    callbacks: {
                        label: function(context) {
                            return 'S/. ' + context.parsed.y.toLocaleString('es-PE', {minimumFractionDigits: 2, maximumFractionDigits: 2});
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
</script>

@endsection
