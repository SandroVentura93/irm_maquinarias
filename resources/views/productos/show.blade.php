@extends('layouts.dashboard')

@section('content')
<div class="container-fluid modern-container">
    <!-- Modern Header -->
    <div class="page-header mb-4">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="page-icon me-3">
                    <i class="fas fa-eye"></i>
                </div>
                <div>
                    <h2 class="page-title mb-0">{{ $producto->descripcion }}</h2>
                    <p class="page-subtitle mb-0">Información detallada del producto</p>
                    <nav class="breadcrumb-modern mt-1">
                        <a href="{{ route('productos.index') }}" class="breadcrumb-link">
                            <i class="fas fa-boxes me-1"></i>Productos
                        </a>
                        <span class="breadcrumb-separator">/</span>
                        <span class="breadcrumb-current">{{ $producto->codigo }}</span>
                    </nav>
                </div>
            </div>
            <div class="header-actions d-flex align-items-center">
                <div class="product-status me-3">
                    <span class="status-badge {{ $producto->activo ? 'status-active' : 'status-inactive' }}">
                        <i class="fas fa-{{ $producto->activo ? 'check-circle' : 'times-circle' }} me-1"></i>
                        {{ $producto->activo ? 'ACTIVO' : 'INACTIVO' }}
                    </span>
                </div>
                <div class="tipo-cambio-badge me-3">
                    <i class="fas fa-exchange-alt me-2"></i>
                    <strong>TC:</strong> S/ {{ number_format($tipoCambio, 4) }}/USD
                </div>
                <a href="{{ route('productos.edit', $producto) }}" class="btn btn-warning btn-modern">
                    <i class="fas fa-edit me-2"></i>Editar Producto
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Información Principal -->
        <div class="col-lg-8">
            <!-- Hero Card del Producto -->
            <div class="card modern-card hero-card mb-4">
                <div class="hero-header">
                    <div class="hero-content">
                        <div class="product-code-badge">
                            <i class="fas fa-barcode me-2"></i>
                            {{ $producto->codigo }}
                        </div>
                        <h3 class="product-title">{{ $producto->descripcion }}</h3>
                        <div class="product-tags">
                            @if($producto->categoria)
                                <span class="tag tag-category">
                                    <i class="fas fa-tag me-1"></i>{{ $producto->categoria->descripcion }}
                                </span>
                            @endif
                            @if($producto->marca)
                                <span class="tag tag-brand">
                                    <i class="fas fa-copyright me-1"></i>{{ $producto->marca->descripcion }}
                                </span>
                            @endif
                            <span class="tag tag-{{ $producto->importado ? 'imported' : 'national' }}">
                                <i class="fas fa-{{ $producto->importado ? 'globe' : 'home' }} me-1"></i>
                                {{ $producto->importado ? 'Importado' : 'Nacional' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información Detallada -->
            <div class="row g-4">
                <!-- Información Básica -->
                <div class="col-md-6">
                    <div class="card modern-card info-card">
                        <div class="card-header modern-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-info-circle me-2 text-primary"></i>
                                Información Básica
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="info-grid">
                                @if($producto->numero_parte)
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-tag text-secondary"></i>
                                        Número de Parte
                                    </div>
                                    <div class="info-value">{{ $producto->numero_parte }}</div>
                                </div>
                                @endif

                                @if($producto->modelo)
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-cog text-secondary"></i>
                                        Modelo
                                    </div>
                                    <div class="info-value">{{ $producto->modelo }}</div>
                                </div>
                                @endif

                                @if($producto->peso)
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-weight text-secondary"></i>
                                        Peso
                                    </div>
                                    <div class="info-value">{{ $producto->peso }} kg</div>
                                </div>
                                @endif

                                @if($producto->ubicacion)
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-map-marker-alt text-secondary"></i>
                                        Ubicación
                                    </div>
                                    <div class="info-value">
                                        <span class="location-badge">
                                            <i class="fas fa-warehouse me-1"></i>
                                            {{ $producto->ubicacion }}
                                        </span>
                                    </div>
                                </div>
                                @endif

                                @if($producto->proveedor)
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-truck text-secondary"></i>
                                        Proveedor
                                    </div>
                                    <div class="info-value">
                                        <span class="supplier-badge">
                                            {{ $producto->proveedor->nombre ?? $producto->proveedor->razon_social ?? $producto->proveedor->descripcion }}
                                        </span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Precios y Rentabilidad -->
                <div class="col-md-6">
                    <div class="card modern-card pricing-card">
                        <div class="card-header modern-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-dollar-sign me-2 text-success"></i>
                                Precios y Rentabilidad
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="pricing-grid">
                                <div class="price-item purchase">
                                    <div class="price-label">
                                        <i class="fas fa-shopping-cart text-info"></i>
                                        Precio de Compra
                                    </div>
                                    <div class="price-value">
                                        <span class="price-main">S/ {{ number_format($producto->precio_compra, 2) }}</span>
                                        <span class="price-conversion">${{ number_format($producto->precio_compra / $tipoCambio, 2) }} USD</span>
                                    </div>
                                </div>

                                <div class="price-item sale">
                                    <div class="price-label">
                                        <i class="fas fa-hand-holding-usd text-success"></i>
                                        Precio de Venta
                                    </div>
                                    <div class="price-value">
                                        <span class="price-main sale-price">S/ {{ number_format($producto->precio_venta, 2) }}</span>
                                        <span class="price-conversion">${{ number_format($producto->precio_venta / $tipoCambio, 2) }} USD</span>
                                    </div>
                                </div>

                                @if($producto->precio_compra > 0)
                                <div class="margin-indicator">
                                    @php
                                        $margen = (($producto->precio_venta - $producto->precio_compra) / $producto->precio_compra) * 100;
                                    @endphp
                                    <div class="margin-card margin-{{ $margen >= 20 ? 'good' : ($margen >= 0 ? 'fair' : 'poor') }}">
                                        <div class="margin-header">
                                            <i class="fas fa-chart-line me-2"></i>
                                            Margen de Ganancia
                                        </div>
                                        <div class="margin-value">
                                            {{ number_format($margen, 1) }}%
                                            <i class="fas fa-{{ $margen >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($producto->observaciones)
            <!-- Observaciones -->
            <div class="card modern-card mt-4">
                <div class="card-header modern-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-sticky-note me-2 text-warning"></i>
                        Observaciones
                    </h5>
                </div>
                <div class="card-body">
                    <div class="observation-content">
                        <p class="mb-0">{{ $producto->observaciones }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Panel Lateral -->
        <div class="col-lg-4">
            <!-- Estado de Inventario -->
            <div class="card modern-card inventory-card mb-4">
                <div class="card-header modern-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cubes me-2 text-warning"></i>
                        Estado de Inventario
                    </h5>
                </div>
                <div class="card-body">
                    <div class="stock-display">
                        <div class="stock-current">
                            <div class="stock-number {{ $producto->stock_actual <= $producto->stock_minimo ? 'stock-critical' : 'stock-normal' }}">
                                {{ $producto->stock_actual }}
                            </div>
                            <div class="stock-label">Stock Actual</div>
                        </div>
                        
                        <div class="stock-indicator">
                            @if($producto->stock_actual <= $producto->stock_minimo)
                                <div class="status-alert status-critical">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span>Stock Crítico</span>
                                </div>
                            @else
                                <div class="status-alert status-good">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Stock Normal</span>
                                </div>
                            @endif
                        </div>

                        <div class="stock-details">
                            <div class="stock-detail-item">
                                <span class="detail-label">Stock Mínimo:</span>
                                <span class="detail-value">{{ $producto->stock_minimo }}</span>
                            </div>
                            
                            @if($producto->stock_actual > 0)
                            <div class="stock-progress">
                                @php
                                    $porcentajeStock = min(100, ($producto->stock_actual / max($producto->stock_minimo * 3, $producto->stock_actual)) * 100);
                                @endphp
                                <div class="progress-container">
                                    <div class="progress-bar {{ $producto->stock_actual <= $producto->stock_minimo ? 'progress-critical' : 'progress-good' }}" 
                                         style="width: {{ $porcentajeStock }}%"></div>
                                </div>
                                <small class="progress-text">Nivel de inventario</small>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Valor del Inventario -->
            <div class="card modern-card value-card mb-4">
                <div class="card-header modern-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calculator me-2 text-success"></i>
                        Valor del Inventario
                    </h5>
                </div>
                <div class="card-body">
                    <div class="value-grid">
                        <div class="value-item purchase">
                            <div class="value-header">
                                <i class="fas fa-shopping-cart text-info"></i>
                                <span>Valor Total de Compra</span>
                            </div>
                            <div class="value-main">S/ {{ number_format($producto->precio_compra * $producto->stock_actual, 2) }}</div>
                            <div class="value-conversion">${{ number_format(($producto->precio_compra * $producto->stock_actual) / $tipoCambio, 2) }} USD</div>
                        </div>
                        
                        <div class="value-item sale">
                            <div class="value-header">
                                <i class="fas fa-hand-holding-usd text-success"></i>
                                <span>Valor Estimado de Venta</span>
                            </div>
                            <div class="value-main sale-value">S/ {{ number_format($producto->precio_venta * $producto->stock_actual, 2) }}</div>
                            <div class="value-conversion">${{ number_format(($producto->precio_venta * $producto->stock_actual) / $tipoCambio, 2) }} USD</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones -->
            <div class="card modern-card actions-card mb-4">
                <div class="card-header modern-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-tools me-2 text-primary"></i>
                        Acciones Disponibles
                    </h5>
                </div>
                <div class="card-body">
                    <div class="action-buttons">
                        <a href="{{ route('productos.edit', $producto) }}" class="btn btn-warning btn-modern w-100 mb-2">
                            <i class="fas fa-edit me-2"></i>Editar Producto
                        </a>
                        <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary btn-modern w-100 mb-2">
                            <i class="fas fa-arrow-left me-2"></i>Volver al Listado
                        </a>
                        <a href="{{ route('productos.create') }}" class="btn btn-outline-primary btn-modern w-100 mb-3">
                            <i class="fas fa-plus me-2"></i>Crear Nuevo Producto
                        </a>
                        
                        <div class="danger-zone">
                            <div class="danger-header">
                                <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                                <span>Zona de Peligro</span>
                            </div>
                            @if(auth()->check() && auth()->user()->id_rol === 1)
                            <form action="{{ route('productos.destroy', $producto) }}" method="POST" 
                                  onsubmit="return confirmDelete('{{ $producto->descripcion }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-modern w-100">
                                    <i class="fas fa-trash me-2"></i>Eliminar Producto
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del Sistema -->
            <div class="card modern-card system-info-card">
                <div class="card-header modern-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2 text-info"></i>
                        Información del Sistema
                    </h5>
                </div>
                <div class="card-body">
                    <div class="system-info-grid">
                        <div class="info-row">
                            <i class="fas fa-calendar-plus text-success"></i>
                            <div class="info-content">
                                <span class="info-title">Fecha de Creación</span>
                                <span class="info-data">{{ $producto->created_at ? $producto->created_at->format('d/m/Y H:i') : 'N/A' }}</span>
                            </div>
                        </div>
                        
                        @if($producto->updated_at && $producto->updated_at != $producto->created_at)
                        <div class="info-row">
                            <i class="fas fa-calendar-edit text-warning"></i>
                            <div class="info-content">
                                <span class="info-title">Última Modificación</span>
                                <span class="info-data">{{ $producto->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Información Adicional -->
    <div class="card modern-card info-footer mt-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="footer-info">
                        <i class="fas fa-exchange-alt text-info me-2"></i>
                        <strong>Tipo de Cambio:</strong>
                        Los precios en dólares son calculados automáticamente con el TC actual:
                        <span class="badge bg-info">S/ {{ number_format($tipoCambio, 2) }} por USD</span>
                        <small class="text-muted d-block mt-1">
                            Los valores pueden variar según el tipo de cambio del momento.
                        </small>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <button class="btn btn-outline-info btn-modern" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Imprimir Ficha
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Estilos modernos para mostrar producto */
.modern-container {
    padding: 20px;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
}

.page-header {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    border: 1px solid rgba(255,255,255,0.2);
}

.page-icon {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
}

.page-title {
    font-size: 28px;
    font-weight: 700;
    color: #2d3748;
    margin: 0;
}

.page-subtitle {
    color: #718096;
    font-size: 16px;
}

.breadcrumb-modern {
    font-size: 14px;
    color: #a0aec0;
}

.breadcrumb-link {
    color: #4299e1;
    text-decoration: none;
    transition: color 0.3s ease;
}

.breadcrumb-link:hover {
    color: #3182ce;
}

.breadcrumb-separator {
    margin: 0 8px;
    color: #cbd5e0;
}

.breadcrumb-current {
    color: #718096;
}

.product-status {
    display: flex;
    align-items: center;
}

.status-badge {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
    display: flex;
    align-items: center;
}

.status-active {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3);
}

.status-inactive {
    background: linear-gradient(135deg, #718096 0%, #4a5568 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(113, 128, 150, 0.3);
}

.tipo-cambio-badge {
    background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 14px;
    box-shadow: 0 4px 15px rgba(66, 153, 225, 0.3);
}

.btn-modern {
    padding: 12px 24px;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}

/* Cards modernos */
.modern-card {
    background: white;
    border: none;
    border-radius: 15px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: transform 0.3s ease;
}

.modern-card:hover {
    transform: translateY(-2px);
}

.modern-header {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border-bottom: 1px solid #e2e8f0;
    padding: 20px 25px;
}

/* Hero Card */
.hero-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    overflow: hidden;
    position: relative;
}

.hero-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 100%;
    height: 100%;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
}

.hero-header {
    padding: 30px;
    position: relative;
    z-index: 2;
}

.product-code-badge {
    background: rgba(255,255,255,0.2);
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    margin-bottom: 15px;
}

.product-title {
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 20px;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.product-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.tag {
    background: rgba(255,255,255,0.2);
    color: white;
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: 500;
    display: flex;
    align-items: center;
    backdrop-filter: blur(10px);
}

.tag-imported {
    background: rgba(255, 193, 7, 0.2);
}

.tag-national {
    background: rgba(40, 167, 69, 0.2);
}

/* Info Cards */
.info-card .card-body {
    padding: 25px;
}

.info-grid {
    display: grid;
    gap: 20px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background: #f8fafc;
    border-radius: 10px;
    border-left: 4px solid #e2e8f0;
}

.info-label {
    display: flex;
    align-items: center;
    color: #4a5568;
    font-weight: 600;
    font-size: 14px;
    gap: 8px;
}

.info-value {
    font-weight: 600;
    color: #2d3748;
}

.location-badge, .supplier-badge {
    background: #edf2f7;
    color: #4a5568;
    padding: 4px 12px;
    border-radius: 15px;
    font-size: 12px;
}

/* Pricing Card */
.pricing-card .card-body {
    padding: 25px;
}

.pricing-grid {
    display: grid;
    gap: 20px;
}

.price-item {
    padding: 20px;
    border-radius: 12px;
    text-align: center;
}

.price-item.purchase {
    background: linear-gradient(135deg, #ebf8ff 0%, #bee3f8 100%);
    border: 2px solid #90cdf4;
}

.price-item.sale {
    background: linear-gradient(135deg, #f0fff4 0%, #c6f6d5 100%);
    border: 2px solid #9ae6b4;
}

.price-label {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-bottom: 10px;
    font-weight: 600;
    color: #4a5568;
}

.price-main {
    font-size: 24px;
    font-weight: 700;
    color: #2d3748;
    display: block;
}

.sale-price {
    color: #38a169;
}

.price-conversion {
    font-size: 14px;
    color: #718096;
    display: block;
    margin-top: 5px;
}

.margin-indicator {
    margin-top: 20px;
}

.margin-card {
    padding: 20px;
    border-radius: 12px;
    text-align: center;
}

.margin-good {
    background: linear-gradient(135d, #d4edda 0%, #c3e6cb 100%);
    border: 2px solid #28a745;
    color: #155724;
}

.margin-fair {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    border: 2px solid #ffc107;
    color: #856404;
}

.margin-poor {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    border: 2px solid #dc3545;
    color: #721c24;
}

.margin-header {
    font-weight: 600;
    margin-bottom: 10px;
}

.margin-value {
    font-size: 28px;
    font-weight: 700;
}

/* Inventory Card */
.inventory-card .card-body {
    padding: 25px;
}

.stock-display {
    text-align: center;
}

.stock-current {
    margin-bottom: 20px;
}

.stock-number {
    font-size: 48px;
    font-weight: 700;
    margin-bottom: 5px;
}

.stock-normal {
    color: #38a169;
}

.stock-critical {
    color: #e53e3e;
    animation: pulse 2s infinite;
}

.stock-label {
    font-size: 16px;
    color: #718096;
    font-weight: 600;
}

.stock-indicator {
    margin-bottom: 20px;
}

.status-alert {
    padding: 12px 16px;
    border-radius: 10px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.status-good {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
    border: 1px solid #c3e6cb;
}

.status-critical {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
    border: 1px solid #f5c6cb;
    animation: pulse 2s infinite;
}

.stock-details {
    text-align: left;
}

.stock-detail-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}

.detail-label {
    color: #718096;
    font-weight: 600;
}

.detail-value {
    color: #2d3748;
    font-weight: 700;
}

.stock-progress {
    margin-top: 15px;
}

.progress-container {
    width: 100%;
    height: 8px;
    background: #e2e8f0;
    border-radius: 4px;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    border-radius: 4px;
    transition: width 0.3s ease;
}

.progress-good {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
}

.progress-critical {
    background: linear-gradient(135deg, #fc8181 0%, #e53e3e 100%);
}

.progress-text {
    color: #718096;
    font-size: 12px;
    margin-top: 5px;
    display: block;
}

/* Value Card */
.value-card .card-body {
    padding: 25px;
}

.value-grid {
    display: grid;
    gap: 20px;
}

.value-item {
    padding: 20px;
    border-radius: 12px;
    text-align: center;
}

.value-item.purchase {
    background: linear-gradient(135deg, #ebf8ff 0%, #bee3f8 100%);
    border-left: 4px solid #3182ce;
}

.value-item.sale {
    background: linear-gradient(135deg, #f0fff4 0%, #c6f6d5 100%);
    border-left: 4px solid #38a169;
}

.value-header {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-bottom: 10px;
    font-weight: 600;
    color: #4a5568;
    font-size: 14px;
}

.value-main {
    font-size: 20px;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 5px;
}

.sale-value {
    color: #38a169;
}

.value-conversion {
    font-size: 12px;
    color: #718096;
}

/* Actions Card */
.actions-card .card-body {
    padding: 25px;
}

.action-buttons {
    display: flex;
    flex-direction: column;
}

.danger-zone {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #e2e8f0;
}

.danger-header {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    font-weight: 600;
    color: #e53e3e;
}

/* System Info Card */
.system-info-card .card-body {
    padding: 25px;
}

.system-info-grid {
    display: grid;
    gap: 15px;
}

.info-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #f8fafc;
    border-radius: 8px;
}

.info-content {
    display: flex;
    flex-direction: column;
}

.info-title {
    font-weight: 600;
    color: #4a5568;
    font-size: 14px;
}

.info-data {
    color: #2d3748;
    font-size: 13px;
}

/* Footer Info */
.info-footer .card-body {
    padding: 20px 25px;
}

.footer-info {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
}

/* Observation Content */
.observation-content {
    background: #f7fafc;
    padding: 20px;
    border-radius: 10px;
    border-left: 4px solid #ed8936;
}

/* Animaciones */
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modern-card {
    animation: fadeInUp 0.6s ease-out;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        padding: 20px;
        flex-direction: column;
        text-align: center;
    }
    
    .page-title {
        font-size: 24px;
    }
    
    .header-actions {
        margin-top: 15px;
        justify-content: center;
        flex-direction: column;
    }
    
    .product-tags {
        justify-content: center;
    }
    
    .info-item {
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }
}
</style>

<script>
// JavaScript para la página de mostrar producto
document.addEventListener('DOMContentLoaded', function() {
    // Animaciones al cargar
    const cards = document.querySelectorAll('.modern-card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 150);
    });
    
    // Tooltips para elementos informativos
    const tooltipElements = document.querySelectorAll('[title]');
    tooltipElements.forEach(element => {
        element.setAttribute('data-bs-toggle', 'tooltip');
    });
    
    // Efectos de hover para las cards
    const hoverCards = document.querySelectorAll('.info-item, .price-item, .value-item');
    hoverCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.02)';
            this.style.transition = 'transform 0.2s ease';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
});

// Confirmación mejorada para eliminar
function confirmDelete(productName) {
    return confirm(`¿Está seguro de que desea eliminar el producto "${productName}"?\n\nEsta acción no se puede deshacer y eliminará toda la información asociada.`);
}

// Función para imprimir con estilo
function printProduct() {
    window.print();
}
</script>
@endsection