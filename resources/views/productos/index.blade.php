@extends('layouts.dashboard')

@section('content')
<div class="container-fluid modern-container">
    <!-- Modern Header -->
    <div class="page-header mb-4">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="page-icon me-3">
                    <i class="fas fa-boxes"></i>
                </div>
                <div>
                    <h2 class="page-title mb-0">Gestión de Productos</h2>
                    <p class="page-subtitle mb-0">Administra el inventario y catálogo de productos</p>
                </div>
            </div>
            <div class="header-actions d-flex align-items-center">
                <div class="tipo-cambio-badge me-3">
                    <i class="fas fa-exchange-alt me-2"></i>
                    <strong>TC:</strong> S/ {{ number_format($tipoCambio, 2) }}/USD
                </div>
                <a href="{{ route('productos.create') }}" class="btn btn-success btn-modern">
                    <i class="fas fa-plus-circle me-2"></i>Nuevo Producto
                </a>
            </div>
        </div>
    </div>

    <!-- Estadísticas Modernas -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stats-card stats-primary">
                <div class="stats-icon">
                    <i class="fas fa-cubes"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number">{{ $estadisticas['total_productos'] }}</h3>
                    <p class="stats-label">Total Productos</p>
                    <div class="stats-trend">
                        <i class="fas fa-arrow-up me-1"></i>
                        <small>En inventario</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card stats-warning">
                <div class="stats-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number">{{ $estadisticas['productos_bajo_stock'] }}</h3>
                    <p class="stats-label">Stock Bajo</p>
                    <div class="stats-trend">
                        <i class="fas fa-alert-triangle me-1"></i>
                        <small>Requieren atención</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card stats-success">
                <div class="stats-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number">S/ {{ number_format($estadisticas['valor_total_inventario'], 2) }}</h3>
                    <p class="stats-label">Valor Total Inventario</p>
                    <div class="stats-trend">
                        <i class="fas fa-chart-line me-1"></i>
                        <small>Valor actual</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success modern-alert alert-dismissible fade show">
            <div class="alert-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="alert-content">
                <strong>¡Éxito!</strong>
                {{ session('success') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filtros Modernos -->
    <div class="card modern-card mb-4">
        <div class="card-header modern-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-filter me-2 text-primary"></i>
                Filtros y Búsqueda Avanzada
            </h5>
            @if(request('search') || request('categoria_id') || request('marca_id') || request('stock_status'))
            <div class="active-filters-badge">
                <span class="badge bg-success">
                    <i class="fas fa-check-circle me-1"></i>
                    Filtros Activos
                </span>
            </div>
            @endif
        </div>
        <div class="card-body">
            <!-- Indicadores de filtros activos -->
            @if(request('search') || request('categoria_id') || request('marca_id') || request('stock_status'))
            <div class="active-filters-display mb-3">
                <strong><i class="fas fa-info-circle me-2 text-primary"></i>Filtros aplicados:</strong>
                <div class="filter-tags mt-2">
                    @if(request('search'))
                    <span class="filter-tag">
                        <i class="fas fa-search"></i> Búsqueda: <strong>{{ request('search') }}</strong>
                    </span>
                    @endif
                    @if(request('categoria_id'))
                    @php
                        $categoriaSeleccionada = $categorias->firstWhere('id_categoria', request('categoria_id'));
                    @endphp
                    <span class="filter-tag">
                        <i class="fas fa-tags"></i> Categoría: <strong>{{ $categoriaSeleccionada->descripcion ?? 'N/A' }}</strong>
                    </span>
                    @endif
                    @if(request('marca_id'))
                    @php
                        $marcaSeleccionada = $marcas->firstWhere('id_marca', request('marca_id'));
                    @endphp
                    <span class="filter-tag">
                        <i class="fas fa-copyright"></i> Marca: <strong>{{ $marcaSeleccionada->nombre ?? 'N/A' }}</strong>
                    </span>
                    @endif
                    @if(request('stock_status'))
                    <span class="filter-tag">
                        <i class="fas fa-warehouse"></i> Stock: <strong>{{ request('stock_status') == 'bajo' ? 'Bajo' : 'Normal' }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            @endif

            <form method="GET" action="{{ route('productos.index') }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="modern-label">
                            <i class="fas fa-search me-1"></i>
                            Buscar Producto
                        </label>
                        <div class="search-input-group">
                            <input type="text" name="search" id="search" class="form-control modern-input" 
                                   placeholder="🔍 Código, descripción o marca..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="modern-label">
                            <i class="fas fa-tags me-1"></i>
                            Categoría
                        </label>
                        <select name="categoria_id" id="categoria_id" class="form-select modern-select">
                            <option value="">🏷️ Todas las categorías</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id_categoria }}" {{ request('categoria_id') == $categoria->id_categoria ? 'selected' : '' }}>
                                    {{ $categoria->descripcion }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="modern-label">
                            <i class="fas fa-copyright me-1"></i>
                            Marca
                        </label>
                        <select name="marca_id" id="marca_id" class="form-select modern-select">
                            <option value="">🏭 Todas las marcas</option>
                            @foreach($marcas as $marca)
                                <option value="{{ $marca->id_marca }}" {{ request('marca_id') == $marca->id_marca ? 'selected' : '' }}>
                                    {{ $marca->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="modern-label">
                            <i class="fas fa-warehouse me-1"></i>
                            Estado Stock
                        </label>
                        <select name="stock_status" id="stock_status" class="form-select modern-select">
                            <option value="">📦 Todos los estados</option>
                            <option value="bajo" {{ request('stock_status') == 'bajo' ? 'selected' : '' }}>⚠️ Stock Bajo</option>
                            <option value="normal" {{ request('stock_status') == 'normal' ? 'selected' : '' }}>✅ Stock Normal</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="modern-label">
                            <i class="fas fa-sort me-1"></i>
                            Ordenar por
                        </label>
                        <select name="sort_by" id="sort_by" class="form-select modern-select">
                            <option value="descripcion" {{ request('sort_by') == 'descripcion' ? 'selected' : '' }}>📝 Descripción</option>
                            <option value="codigo" {{ request('sort_by') == 'codigo' ? 'selected' : '' }}>🔢 Código</option>
                            <option value="precio_venta" {{ request('sort_by') == 'precio_venta' ? 'selected' : '' }}>💰 Precio</option>
                            <option value="stock_actual" {{ request('sort_by') == 'stock_actual' ? 'selected' : '' }}>📊 Stock</option>
                        </select>
                    </div>
                </div>
                <div class="filter-actions mt-3">
                    <button type="submit" class="btn btn-primary btn-modern me-2">
                        <i class="fas fa-search me-2"></i>Buscar
                    </button>
                    <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary btn-modern">
                        <i class="fas fa-eraser me-2"></i>Limpiar Filtros
                    </a>
                </div>
            </form>

            <!-- Tipo de Cambio Manual -->
            <div class="form-group mt-4">
                <label for="tipoCambioManual">Tipo de Cambio Manual:</label>
                <input type="number" step="0.01" class="form-control" id="tipoCambioManual" name="tipoCambioManual" placeholder="Ingrese el tipo de cambio">
            </div>
        </div>
    </div>

    <!-- Tabla Moderna de Productos -->
    <div class="card modern-card">
        <div class="card-header modern-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2 text-primary"></i>
                    Catálogo de Productos
                </h5>
                <small class="text-muted">Gestiona tu inventario de forma eficiente</small>
            </div>
            <div class="results-counter">
                <span class="badge bg-primary">{{ $productos->total() }} productos</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table modern-table mb-0">
                    <thead class="modern-thead">
                        <tr>
                            <th><i class="fas fa-barcode me-1"></i>Código</th>
                            <th><i class="fas fa-tag me-1"></i>Descripción</th>
                            <th><i class="fas fa-folder me-1"></i>Categoría</th>
                            <th><i class="fas fa-industry me-1"></i>Marca</th>
                            <th><i class="fas fa-boxes me-1"></i>Stock</th>
                            <th><i class="fas fa-shopping-cart me-1"></i>Precio Compra</th>
                            <th><i class="fas fa-money-bill me-1"></i>Precio Venta</th>
                            <th class="text-center"><i class="fas fa-cogs me-1"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($productos as $producto)
                            <tr class="product-row">
                                <td>
                                    <div class="product-code">
                                        <span class="code-badge">{{ $producto->codigo }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="product-info">
                                        <div class="product-name">{{ $producto->descripcion }}</div>
                                    </div>
                                </td>
                                <td>
                                    <span class="category-tag">
                                        <i class="fas fa-tag me-1"></i>
                                        {{ $producto->categoria->descripcion ?? 'Sin categoría' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="brand-tag">
                                        <i class="fas fa-copyright me-1"></i>
                                        {{ $producto->marca->nombre ?? 'Sin marca' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="stock-info">
                                        <div class="stock-badge {{ $producto->stock_actual <= $producto->stock_minimo ? 'stock-low' : 'stock-normal' }}">
                                            <i class="fas fa-boxes me-1"></i>
                                            <strong>{{ $producto->stock_actual }}</strong>
                                        </div>
                                        <small class="stock-min">Mín: {{ $producto->stock_minimo }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="price-info">
                                        <div class="price-pen">S/ {{ number_format($producto->precio_compra, 2) }}</div>
                                        <div class="price-usd">${{ number_format($producto->precio_compra / $tipoCambio, 2) }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="price-info">
                                        <div class="price-pen price-sale">S/ {{ number_format($producto->precio_venta, 2) }}</div>
                                        <div class="price-usd">${{ number_format($producto->precio_venta / $tipoCambio, 2) }}</div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="action-buttons">
                                        <a href="{{ route('productos.show', $producto) }}" class="btn btn-info btn-modern-sm" title="Ver Detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('productos.edit', $producto) }}" class="btn btn-warning btn-modern-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('productos.destroy', $producto) }}" method="POST" style="display:inline;" 
                                              onsubmit="return confirmDelete('{{ $producto->descripcion }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-modern-sm" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No se encontraron productos</h5>
                                        <p class="text-muted">Intenta cambiar los filtros o <a href="{{ route('productos.create') }}">crear un nuevo producto</a></p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($productos->hasPages())
            <div class="card-footer modern-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="pagination-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <small class="text-muted">
                            Mostrando {{ $productos->firstItem() }} - {{ $productos->lastItem() }} de {{ $productos->total() }} productos
                        </small>
                    </div>
                    <div class="pagination-wrapper">
                        {{ $productos->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Footer Informativo -->
    <div class="card modern-card mt-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="info-section">
                        <i class="fas fa-exchange-alt text-info me-2"></i>
                        <strong>Tipo de Cambio:</strong> 
                        Los precios en dólares son calculados automáticamente con el TC actual: 
                        <span class="badge bg-info">S/ {{ number_format($tipoCambio, 2) }} por USD</span>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <button class="btn btn-outline-info btn-modern" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Imprimir Lista
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Estilos modernos para la página de productos */
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

/* Estadísticas modernas */
.stats-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    border: none;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stats-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
}

.stats-card.stats-primary::before { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.stats-card.stats-warning::before { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.stats-card.stats-success::before { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.15);
}

.stats-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
    margin-bottom: 15px;
}

.stats-primary .stats-icon { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.stats-warning .stats-icon { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.stats-success .stats-icon { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }

.stats-number {
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 5px;
    color: #2d3748;
}

.stats-label {
    font-size: 14px;
    color: #718096;
    margin-bottom: 10px;
}

.stats-trend {
    font-size: 12px;
    color: #4a5568;
}

/* Card moderno */
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
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modern-footer {
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    padding: 20px 25px;
}

/* Indicadores de filtros activos */
.active-filters-badge {
    display: inline-flex;
    align-items: center;
}

.active-filters-display {
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    border: 2px solid #86efac;
    border-radius: 12px;
    padding: 16px 20px;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.filter-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.filter-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: white;
    padding: 8px 16px;
    border-radius: 8px;
    border: 2px solid #10b981;
    color: #065f46;
    font-size: 14px;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2);
    transition: all 0.2s ease;
}

.filter-tag:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.filter-tag i {
    color: #10b981;
}

.filter-tag strong {
    color: #047857;
}

/* Inputs y selects modernos */
.modern-label {
    font-weight: 600;
    color: #4a5568;
    font-size: 14px;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
}

.modern-input, .modern-select {
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    padding: 12px 16px;
    font-size: 14px;
    transition: all 0.3s ease;
    background: #f8fafc;
}

.modern-input:focus, .modern-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    background: white;
    outline: none;
}

/* Tabla moderna */
.modern-table {
    border: none;
}

.modern-thead {
    background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);
    color: white;
}

.modern-thead th {
    border: none;
    padding: 15px 12px;
    font-weight: 600;
    font-size: 13px;
    letter-spacing: 0.5px;
}

.product-row {
    transition: all 0.3s ease;
    border: none;
}

.product-row:hover {
    background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
    transform: scale(1.01);
}

.product-row td {
    padding: 15px 12px;
    vertical-align: middle;
    border-top: 1px solid #e2e8f0;
}

.code-badge {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 12px;
    letter-spacing: 1px;
}

.product-name {
    font-weight: 600;
    color: #2d3748;
    font-size: 14px;
}

.category-tag, .brand-tag {
    background: #edf2f7;
    color: #4a5568;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
}

.stock-info {
    text-align: center;
}

.stock-badge {
    padding: 6px 10px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 4px;
    display: inline-block;
}

.stock-normal {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    color: white;
}

.stock-low {
    background: linear-gradient(135deg, #fc8181 0%, #e53e3e 100%);
    color: white;
    animation: pulse 2s infinite;
}

.stock-min {
    color: #718096;
    font-size: 11px;
}

.price-info {
    text-align: center;
}

.price-pen {
    font-weight: 700;
    font-size: 14px;
    color: #2d3748;
    margin-bottom: 2px;
}

.price-sale {
    color: #38a169;
}

.price-usd {
    font-size: 11px;
    color: #718096;
}

.action-buttons {
    display: flex;
    gap: 5px;
    justify-content: center;
}

.btn-modern-sm {
    width: 35px;
    height: 35px;
    border-radius: 8px;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    transition: all 0.3s ease;
}

.btn-modern-sm:hover {
    transform: translateY(-2px) scale(1.1);
}

.empty-state {
    padding: 40px 20px;
}

.modern-alert {
    border: none;
    border-radius: 12px;
    padding: 20px;
    border-left: 4px solid;
}

.alert-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
}

.results-counter .badge {
    font-size: 12px;
    padding: 8px 12px;
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
    }
    
    .page-title {
        font-size: 24px;
    }
    
    .stats-card {
        margin-bottom: 15px;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 8px;
    }
    
    .btn-modern-sm {
        width: 100%;
        height: 35px;
    }
}
</style>

<script>
// JavaScript mejorado para la página de productos
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit en cambios de filtros con delay para mejor UX
    document.querySelectorAll('#categoria_id, #marca_id, #stock_status, #sort_by').forEach(function(element) {
        element.addEventListener('change', function() {
            const selectElement = this;
            const selectedText = selectElement.options[selectElement.selectedIndex].text;
            
            // Mostrar feedback visual inmediato
            selectElement.style.borderColor = '#10b981';
            selectElement.style.backgroundColor = '#f0fdf4';
            
            // Pequeño delay para que el usuario vea su selección
            setTimeout(function() {
                showLoading();
                document.getElementById('filterForm').submit();
            }, 300);
        });
    });
    
    // Búsqueda con delay mejorada
    let searchTimeout;
    const searchInput = document.getElementById('search');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                showLoading();
                document.getElementById('filterForm').submit();
            }, 800);
        });
    }
    
    // Loading indicator
    function showLoading() {
        const submitBtn = document.querySelector('[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Buscando...';
            submitBtn.disabled = true;
        }
        
        // También mostrar overlay de carga en la tabla
        const cardBody = document.querySelector('.table-responsive');
        if (cardBody) {
            cardBody.style.opacity = '0.5';
            cardBody.style.pointerEvents = 'none';
        }
    }
    
    // Animaciones al cargar
    const cards = document.querySelectorAll('.stats-card, .modern-card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
    
    // Tooltips para botones
    const tooltips = document.querySelectorAll('[title]');
    tooltips.forEach(element => {
        element.setAttribute('data-bs-toggle', 'tooltip');
    });
});

// Confirmación de eliminación mejorada
function confirmDelete(productName) {
    return confirm(`¿Estás seguro de que deseas eliminar el producto "${productName}"?\n\nEsta acción no se puede deshacer.`);
}

// Función para exportar (futura implementación)
function exportProducts() {
    alert('Función de exportación en desarrollo...');
}

// Función de impresión mejorada
function printProductList() {
    window.print();
}
</script>
@endsection