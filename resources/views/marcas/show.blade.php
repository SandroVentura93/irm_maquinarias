@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header Moderno -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <a href="{{ route('marcas.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="header-info">
                    <h1 class="header-title">{{ $marca->nombre }}</h1>
                    <p class="header-subtitle">Detalles completos de la marca</p>
                </div>
            </div>
            <div class="header-actions">
                @if($marca->activo)
                    <span class="status-badge active">
                        <i class="fas fa-check-circle"></i> Activa
                    </span>
                @else
                    <span class="status-badge inactive">
                        <i class="fas fa-times-circle"></i> Inactiva
                    </span>
                @endif
                <a href="{{ route('marcas.edit', $marca->id_marca) }}" class="btn-action-header">
                    <i class="fas fa-edit"></i> Editar
                </a>
            </div>
        </div>
    </div>

    <!-- Contenido Principal -->
    <div class="show-container">
        <!-- Tarjeta de Información Principal -->
        <div class="main-content">
            <div class="info-card-large">
                <div class="info-card-header">
                    <div class="header-icon-large">
                        <i class="fas fa-tag"></i>
                    </div>
                    <h2 class="card-title">Información General</h2>
                </div>
                
                <div class="info-card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-hashtag"></i> ID de la Marca
                            </div>
                            <div class="info-value">{{ $marca->id_marca }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-tag"></i> Nombre
                            </div>
                            <div class="info-value">{{ $marca->nombre }}</div>
                        </div>

                        <div class="info-item full-width">
                            <div class="info-label">
                                <i class="fas fa-align-left"></i> Descripción
                            </div>
                            <div class="info-value">
                                {{ $marca->descripcion ?? 'Sin descripción' }}
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-toggle-on"></i> Estado
                            </div>
                            <div class="info-value">
                                @if($marca->activo)
                                    <span class="badge-active">
                                        <i class="fas fa-check-circle"></i> Activa
                                    </span>
                                @else
                                    <span class="badge-inactive">
                                        <i class="fas fa-times-circle"></i> Inactiva
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta de Auditoría -->
            <div class="info-card-large">
                <div class="info-card-header">
                    <div class="header-icon-large">
                        <i class="fas fa-history"></i>
                    </div>
                    <h2 class="card-title">Información de Auditoría</h2>
                </div>
                
                <div class="info-card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-icon created">
                                <i class="fas fa-calendar-plus"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">Fecha de Creación</div>
                                <div class="timeline-date">
                                    {{ $marca->created_at ? $marca->created_at->format('d/m/Y H:i:s') : 'N/A' }}
                                </div>
                                <div class="timeline-relative">
                                    {{ $marca->created_at ? $marca->created_at->diffForHumans() : 'N/A' }}
                                </div>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-icon updated">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">Última Actualización</div>
                                <div class="timeline-date">
                                    {{ $marca->updated_at ? $marca->updated_at->format('d/m/Y H:i:s') : 'N/A' }}
                                </div>
                                <div class="timeline-relative">
                                    {{ $marca->updated_at ? $marca->updated_at->diffForHumans() : 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta de Productos -->
            <div class="info-card-large">
                <div class="info-card-header">
                    <div class="header-icon-large">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <h2 class="card-title">Productos Asociados</h2>
                </div>
                
                <div class="info-card-body">
                    @php
                        $productos = \App\Models\Producto::where('id_marca', $marca->id_marca)->get();
                    @endphp
                    
                    @if($productos->count() > 0)
                        <div class="productos-list">
                            @foreach($productos->take(5) as $producto)
                            <div class="producto-item">
                                <div class="producto-icon">
                                    <i class="fas fa-cube"></i>
                                </div>
                                <div class="producto-info">
                                    <div class="producto-nombre">{{ $producto->descripcion }}</div>
                                    <div class="producto-codigo">SKU: {{ $producto->codigo ?? 'N/A' }}</div>
                                </div>
                                <div class="producto-precio">
                                    S/ {{ number_format($producto->precio_venta ?? 0, 2) }}
                                </div>
                            </div>
                            @endforeach
                            
                            @if($productos->count() > 5)
                            <div class="more-products">
                                <i class="fas fa-plus-circle"></i>
                                Y {{ $productos->count() - 5 }} producto(s) más
                            </div>
                            @endif
                        </div>
                    @else
                        <div class="empty-products">
                            <i class="fas fa-inbox"></i>
                            <p>No hay productos asociados a esta marca</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Panel Lateral -->
        <div class="sidebar-content">
            <!-- Estadísticas -->
            <div class="stats-card">
                <h3 class="stats-title">
                    <i class="fas fa-chart-pie"></i> Estadísticas
                </h3>
                <div class="stats-grid">
                    <div class="stat-box">
                        <div class="stat-icon products">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value">{{ $productos->count() }}</div>
                            <div class="stat-label">Productos</div>
                        </div>
                    </div>

                    <div class="stat-box">
                        <div class="stat-icon stock">
                            <i class="fas fa-warehouse"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value">
                                {{ $productos->sum('stock') ?? 0 }}
                            </div>
                            <div class="stat-label">Stock Total</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="quick-actions">
                <h3 class="actions-title">
                    <i class="fas fa-bolt"></i> Acciones Rápidas
                </h3>
                <div class="actions-list">
                    <a href="{{ route('marcas.edit', $marca->id_marca) }}" class="action-btn edit">
                        <i class="fas fa-edit"></i>
                        <span>Editar Marca</span>
                    </a>
                    <a href="{{ route('marcas.index') }}" class="action-btn back">
                        <i class="fas fa-arrow-left"></i>
                        <span>Volver al Listado</span>
                    </a>
                </div>
            </div>

            <!-- Zona de Peligro -->
            <div class="danger-card">
                <h3 class="danger-title">
                    <i class="fas fa-exclamation-triangle"></i> Zona de Peligro
                </h3>
                <p class="danger-description">
                    Eliminar esta marca es una acción permanente
                </p>
                <form action="{{ route('marcas.destroy', $marca->id_marca) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta marca? Esta acción no se puede deshacer.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger-full">
                        <i class="fas fa-trash-alt"></i>
                        Eliminar Marca
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Header de Página */
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 20px;
        color: white;
    }

    .btn-back {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        text-decoration: none;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateX(-5px);
        color: white;
    }

    .header-info {
        color: white;
    }

    .header-title {
        font-size: 32px;
        font-weight: 700;
        margin: 0;
        color: white;
    }

    .header-subtitle {
        margin: 5px 0 0 0;
        font-size: 14px;
        opacity: 0.9;
    }

    .header-actions {
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .status-badge {
        padding: 12px 24px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        backdrop-filter: blur(10px);
    }

    .status-badge.active {
        background: rgba(72, 187, 120, 0.9);
        color: white;
    }

    .status-badge.inactive {
        background: rgba(252, 129, 129, 0.9);
        color: white;
    }

    .btn-action-header {
        padding: 12px 24px;
        background: white;
        color: #667eea;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-action-header:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        color: #667eea;
    }

    /* Contenedor Principal */
    .show-container {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 30px;
    }

    /* Tarjetas de Información */
    .info-card-large {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
        overflow: hidden;
    }

    .info-card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 25px 30px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .header-icon-large {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
        backdrop-filter: blur(10px);
    }

    .card-title {
        color: white;
        font-size: 20px;
        font-weight: 600;
        margin: 0;
    }

    .info-card-body {
        padding: 30px;
    }

    /* Grid de Información */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .info-item.full-width {
        grid-column: 1 / -1;
    }

    .info-label {
        font-size: 13px;
        font-weight: 600;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-label i {
        color: #667eea;
    }

    .info-value {
        font-size: 16px;
        color: #2d3748;
        font-weight: 600;
        padding: 15px;
        background: #f7fafc;
        border-radius: 10px;
        border-left: 4px solid #667eea;
    }

    .badge-active {
        background: rgba(72, 187, 120, 0.1);
        color: #38a169;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .badge-inactive {
        background: rgba(252, 129, 129, 0.1);
        color: #f56565;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* Timeline */
    .timeline {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }

    .timeline-item {
        display: flex;
        gap: 20px;
        align-items: flex-start;
    }

    .timeline-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: white;
        flex-shrink: 0;
    }

    .timeline-icon.created {
        background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    }

    .timeline-icon.updated {
        background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
    }

    .timeline-content {
        flex: 1;
    }

    .timeline-title {
        font-size: 14px;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 5px;
    }

    .timeline-date {
        font-size: 16px;
        color: #4a5568;
        font-weight: 600;
        margin-bottom: 3px;
    }

    .timeline-relative {
        font-size: 13px;
        color: #718096;
    }

    /* Lista de Productos */
    .productos-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .producto-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        background: #f7fafc;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .producto-item:hover {
        background: #edf2f7;
        transform: translateX(5px);
    }

    .producto-icon {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
        flex-shrink: 0;
    }

    .producto-info {
        flex: 1;
    }

    .producto-nombre {
        font-size: 14px;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 3px;
    }

    .producto-codigo {
        font-size: 12px;
        color: #718096;
    }

    .producto-precio {
        font-size: 16px;
        font-weight: 700;
        color: #48bb78;
    }

    .more-products {
        text-align: center;
        padding: 15px;
        color: #667eea;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .empty-products {
        text-align: center;
        padding: 40px;
        color: #a0aec0;
    }

    .empty-products i {
        font-size: 48px;
        margin-bottom: 15px;
    }

    /* Estadísticas */
    .stats-card {
        background: white;
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 20px;
    }

    .stats-title {
        font-size: 16px;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .stats-title i {
        color: #667eea;
    }

    .stats-grid {
        display: grid;
        gap: 15px;
    }

    .stat-box {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 20px;
        background: #f7fafc;
        border-radius: 12px;
    }

    .stat-icon {
        width: 55px;
        height: 55px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
    }

    .stat-icon.products {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .stat-icon.stock {
        background: linear-gradient(135deg, #f6ad55 0%, #ed8936 100%);
    }

    .stat-info {
        flex: 1;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #2d3748;
        line-height: 1;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 13px;
        color: #718096;
        font-weight: 600;
    }

    /* Acciones Rápidas */
    .quick-actions {
        background: white;
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 20px;
    }

    .actions-title {
        font-size: 16px;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .actions-title i {
        color: #fbbf24;
    }

    .actions-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .action-btn {
        padding: 15px;
        border-radius: 12px;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .action-btn.edit {
        background: #e6f0ff;
        color: #4299e1;
    }

    .action-btn.edit:hover {
        background: #4299e1;
        color: white;
        transform: translateX(5px);
    }

    .action-btn.back {
        background: #f7fafc;
        color: #718096;
    }

    .action-btn.back:hover {
        background: #e2e8f0;
        color: #2d3748;
        transform: translateX(-5px);
    }

    /* Zona de Peligro */
    .danger-card {
        background: white;
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 2px solid #fed7d7;
    }

    .danger-title {
        font-size: 16px;
        font-weight: 700;
        color: #c53030;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .danger-description {
        font-size: 13px;
        color: #718096;
        margin-bottom: 15px;
    }

    .btn-danger-full {
        width: 100%;
        padding: 14px;
        background: #fc8181;
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-danger-full:hover {
        background: #f56565;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(245, 101, 101, 0.4);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .show-container {
            grid-template-columns: 1fr;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 20px;
        }

        .header-title {
            font-size: 24px;
        }

        .header-actions {
            width: 100%;
            justify-content: space-between;
        }
    }
</style>
@endsection