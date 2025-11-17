@extends('layouts.dashboard')

@section('content')
<div class="container-fluid modern-container">
    <!-- Hero Header -->
    <div class="hero-header mb-4">
        <div class="hero-background"></div>
        <div class="hero-content">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="hero-icon me-4">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="hero-text">
                        <h1 class="hero-title">Editar Producto</h1>
                        <div class="hero-subtitle d-flex align-items-center">
                            <i class="fas fa-barcode me-2"></i>
                            <span class="product-code">{{ $producto->codigo }}</span>
                            <span class="product-separator">•</span>
                            <span class="product-name">{{ $producto->descripcion }}</span>
                        </div>
                        <div class="hero-breadcrumb">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb modern-breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('productos.index') }}">Productos</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('productos.show', $producto) }}">{{ $producto->codigo }}</a></li>
                                    <li class="breadcrumb-item active">Editar</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="hero-actions">
                    <div class="exchange-rate-badge">
                        <i class="fas fa-exchange-alt me-2"></i>
                        <span>TC: S/ {{ number_format($tipoCambio, 2) }}/USD</span>
                    </div>
                    <a href="{{ route('productos.show', $producto) }}" class="btn btn-info btn-hero me-2">
                        <i class="fas fa-eye me-2"></i>Ver Producto
                    </a>
                    <a href="{{ route('productos.index') }}" class="btn btn-secondary btn-hero">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger modern-alert alert-dismissible fade show mb-4">
            <div class="alert-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="alert-content">
                <strong>¡Atención! Errores en el formulario:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('productos.update', $producto) }}" method="POST" class="modern-form" id="productForm">
        @csrf
        @method('PUT')
        <div class="row">
            <!-- Información Principal -->
            <div class="col-lg-8">
                <!-- Información Básica -->
                <div class="card form-card mb-4">
                    <div class="card-header form-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="header-icon me-3">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <div>
                                    <h5 class="card-title mb-0">Información Básica</h5>
                                    <small class="text-muted">Datos principales del producto</small>
                                </div>
                            </div>
                            <div class="creation-info">
                                <small class="text-muted">
                                    <i class="fas fa-calendar-plus me-1"></i>
                                    Creado: {{ $producto->created_at ? $producto->created_at->format('d/m/Y H:i') : 'N/A' }}
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-section">
                                    <label class="modern-label required">
                                        <i class="fas fa-barcode text-primary me-2"></i>
                                        Código del Producto
                                    </label>
                                    <div class="input-group modern-input-group">
                                        <span class="input-group-text modern-input-addon">
                                            <i class="fas fa-barcode"></i>
                                        </span>
                                        <input type="text" 
                                               name="codigo" 
                                               id="codigo" 
                                               class="form-control modern-input @error('codigo') is-invalid @enderror" 
                                               value="{{ old('codigo', $producto->codigo) }}" 
                                               required 
                                               placeholder="Ej: PROD001"
                                               maxlength="50">
                                    </div>
                                    @error('codigo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-hint">
                                        <i class="fas fa-lightbulb me-1"></i>
                                        Código único e identificativo del producto
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-section">
                                    <label class="modern-label">
                                        <i class="fas fa-tag text-info me-2"></i>
                                        Número de Parte
                                    </label>
                                    <div class="input-group modern-input-group">
                                        <span class="input-group-text modern-input-addon">
                                            <i class="fas fa-tag"></i>
                                        </span>
                                        <input type="text" 
                                               name="numero_parte" 
                                               id="numero_parte" 
                                               class="form-control modern-input @error('numero_parte') is-invalid @enderror" 
                                               value="{{ old('numero_parte', $producto->numero_parte) }}" 
                                               placeholder="Ej: NP-001"
                                               maxlength="100">
                                    </div>
                                    @error('numero_parte')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-hint">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Número de parte del fabricante (opcional)
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-section">
                            <label class="modern-label required">
                                <i class="fas fa-align-left text-secondary me-2"></i>
                                Descripción del Producto
                            </label>
                            <div class="modern-textarea-wrapper">
                                <textarea name="descripcion" 
                                          id="descripcion" 
                                          class="form-control modern-textarea @error('descripcion') is-invalid @enderror" 
                                          rows="4" 
                                          required 
                                          placeholder="Descripción detallada del producto, características principales..."
                                          maxlength="1000">{{ old('descripcion', $producto->descripcion) }}</textarea>
                                <div class="textarea-counter">
                                    <span id="descCharCount">{{ strlen($producto->descripcion ?? '') }}</span>/1000 caracteres
                                </div>
                            </div>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-hint">
                                <i class="fas fa-pencil-alt me-1"></i>
                                Proporciona una descripción completa y detallada
                            </small>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-section">
                                    <label class="modern-label">
                                        <i class="fas fa-cog text-warning me-2"></i>
                                        Modelo
                                    </label>
                                    <div class="input-group modern-input-group">
                                        <span class="input-group-text modern-input-addon">
                                            <i class="fas fa-cog"></i>
                                        </span>
                                        <input type="text" 
                                               name="modelo" 
                                               id="modelo" 
                                               class="form-control modern-input @error('modelo') is-invalid @enderror" 
                                               value="{{ old('modelo', $producto->modelo) }}" 
                                               placeholder="Modelo del producto"
                                               maxlength="100">
                                    </div>
                                    @error('modelo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-hint">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Modelo específico del producto
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-section">
                                    <label class="modern-label">
                                        <i class="fas fa-weight text-dark me-2"></i>
                                        Peso (kg)
                                    </label>
                                    <div class="input-group modern-input-group">
                                        <span class="input-group-text modern-input-addon">
                                            <i class="fas fa-weight"></i>
                                        </span>
                                        <input type="number" 
                                               step="0.01" 
                                               name="peso" 
                                               id="peso" 
                                               class="form-control modern-input @error('peso') is-invalid @enderror" 
                                               value="{{ old('peso', $producto->peso) }}" 
                                               placeholder="0.00"
                                               min="0">
                                        <span class="input-group-text modern-input-suffix">kg</span>
                                    </div>
                                    @error('peso')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-hint">
                                        <i class="fas fa-balance-scale me-1"></i>
                                        Peso en kilogramos (opcional)
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Clasificación -->
                <div class="card form-card mb-4">
                    <div class="card-header form-header">
                        <div class="d-flex align-items-center">
                            <div class="header-icon me-3">
                                <i class="fas fa-tags"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0">Clasificación y Organización</h5>
                                <small class="text-muted">Categorización y datos de organización</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-section">
                                    <label class="modern-label">
                                        <i class="fas fa-list text-primary me-2"></i>
                                        Categoría
                                    </label>
                                    <div class="modern-select-wrapper">
                                        <select name="id_categoria" 
                                                id="id_categoria" 
                                                class="form-select modern-select @error('id_categoria') is-invalid @enderror">
                                            <option value="">Seleccione una categoría</option>
                                            @foreach ($categorias as $categoria)
                                                <option value="{{ $categoria->id }}" 
                                                        {{ old('id_categoria', $producto->categoria_id ?? $producto->id_categoria) == $categoria->id ? 'selected' : '' }}>
                                                    {{ $categoria->descripcion }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="select-icon">
                                            <i class="fas fa-chevron-down"></i>
                                        </div>
                                    </div>
                                    @error('id_categoria')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-hint">
                                        <i class="fas fa-folder me-1"></i>
                                        Clasifica el producto por tipo
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-section">
                                    <label class="modern-label">
                                        <i class="fas fa-certificate text-warning me-2"></i>
                                        Marca
                                    </label>
                                    <div class="modern-select-wrapper">
                                        <select name="id_marca" 
                                                id="id_marca" 
                                                class="form-select modern-select @error('id_marca') is-invalid @enderror">
                                            <option value="">Seleccione una marca</option>
                                            @foreach ($marcas as $marca)
                                                <option value="{{ $marca->id }}" 
                                                        {{ old('id_marca', $producto->marca_id ?? $producto->id_marca) == $marca->id ? 'selected' : '' }}>
                                                    {{ $marca->descripcion }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="select-icon">
                                            <i class="fas fa-chevron-down"></i>
                                        </div>
                                    </div>
                                    @error('id_marca')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-hint">
                                        <i class="fas fa-star me-1"></i>
                                        Marca del fabricante
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-section">
                                    <label class="modern-label">
                                        <i class="fas fa-truck text-info me-2"></i>
                                        Proveedor
                                    </label>
                                    <div class="modern-select-wrapper">
                                        <select name="id_proveedor" 
                                                id="id_proveedor" 
                                                class="form-select modern-select @error('id_proveedor') is-invalid @enderror">
                                            <option value="">Seleccione un proveedor</option>
                                            @foreach ($proveedores as $proveedor)
                                                <option value="{{ $proveedor->id }}" 
                                                        {{ old('id_proveedor', $producto->proveedor_id ?? $producto->id_proveedor) == $proveedor->id ? 'selected' : '' }}>
                                                    {{ $proveedor->nombre ?? $proveedor->razon_social ?? $proveedor->descripcion }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="select-icon">
                                            <i class="fas fa-chevron-down"></i>
                                        </div>
                                    </div>
                                    @error('id_proveedor')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-hint">
                                        <i class="fas fa-handshake me-1"></i>
                                        Proveedor principal del producto
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-section">
                                    <label class="modern-label">
                                        <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                        Ubicación en Almacén
                                    </label>
                                    <div class="input-group modern-input-group">
                                        <span class="input-group-text modern-input-addon">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </span>
                                        <input type="text" 
                                               name="ubicacion" 
                                               id="ubicacion" 
                                               class="form-control modern-input @error('ubicacion') is-invalid @enderror" 
                                               value="{{ old('ubicacion', $producto->ubicacion) }}" 
                                               placeholder="Ej: Almacén A-1, Estante B-2"
                                               maxlength="100">
                                    </div>
                                    @error('ubicacion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-hint">
                                        <i class="fas fa-warehouse me-1"></i>
                                        Ubicación física del producto
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-section">
                                    <label class="modern-label">
                                        <i class="fas fa-globe text-success me-2"></i>
                                        Producto Importado
                                    </label>
                                    <div class="modern-select-wrapper">
                                        <select name="importado" 
                                                id="importado" 
                                                class="form-select modern-select @error('importado') is-invalid @enderror">
                                            <option value="0" {{ old('importado', $producto->importado) == '0' ? 'selected' : '' }}>
                                                <i class="fas fa-times"></i> No
                                            </option>
                                            <option value="1" {{ old('importado', $producto->importado) == '1' ? 'selected' : '' }}>
                                                <i class="fas fa-check"></i> Sí
                                            </option>
                                        </select>
                                        <div class="select-icon">
                                            <i class="fas fa-chevron-down"></i>
                                        </div>
                                    </div>
                                    @error('importado')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-hint">
                                        <i class="fas fa-question-circle me-1"></i>
                                        ¿Es producto importado?
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-section">
                                    <label class="modern-label">
                                        <i class="fas fa-toggle-on text-success me-2"></i>
                                        Estado del Producto
                                    </label>
                                    <div class="modern-select-wrapper">
                                        <select name="activo" 
                                                id="activo" 
                                                class="form-select modern-select @error('activo') is-invalid @enderror">
                                            <option value="1" {{ old('activo', $producto->activo) == '1' ? 'selected' : '' }}>
                                                <i class="fas fa-check-circle"></i> Activo
                                            </option>
                                            <option value="0" {{ old('activo', $producto->activo) == '0' ? 'selected' : '' }}>
                                                <i class="fas fa-times-circle"></i> Inactivo
                                            </option>
                                        </select>
                                        <div class="select-icon">
                                            <i class="fas fa-chevron-down"></i>
                                        </div>
                                    </div>
                                    @error('activo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-hint">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Solo activos se muestran en ventas
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Lateral -->
            <div class="col-lg-4">
                <!-- Stock -->
                <div class="card sidebar-card mb-4">
                    <div class="card-header sidebar-header">
                        <div class="d-flex align-items-center">
                            <div class="header-icon-small me-3">
                                <i class="fas fa-cubes"></i>
                            </div>
                            <div>
                                <h6 class="card-title mb-0">Control de Inventario</h6>
                                <small class="text-muted">Gestión de stock</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-section">
                            <label class="modern-label required">
                                <i class="fas fa-boxes text-primary me-2"></i>
                                Stock Actual
                            </label>
                            <div class="input-group modern-input-group">
                                <span class="input-group-text modern-input-addon">
                                    <i class="fas fa-boxes"></i>
                                </span>
                                <input type="number" 
                                       name="stock_actual" 
                                       id="stock_actual" 
                                       class="form-control modern-input @error('stock_actual') is-invalid @enderror" 
                                       value="{{ old('stock_actual', $producto->stock_actual) }}" 
                                       required 
                                       min="0">
                                <span class="input-group-text modern-input-suffix">unid.</span>
                            </div>
                            @error('stock_actual')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($producto->stock_actual <= $producto->stock_minimo)
                                <div class="stock-warning">
                                    <i class="fas fa-exclamation-triangle me-1"></i> 
                                    Stock por debajo del mínimo
                                </div>
                            @endif
                        </div>
                        
                        <div class="form-section">
                            <label class="modern-label required">
                                <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                Stock Mínimo
                            </label>
                            <div class="input-group modern-input-group">
                                <span class="input-group-text modern-input-addon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </span>
                                <input type="number" 
                                       name="stock_minimo" 
                                       id="stock_minimo" 
                                       class="form-control modern-input @error('stock_minimo') is-invalid @enderror" 
                                       value="{{ old('stock_minimo', $producto->stock_minimo) }}" 
                                       required 
                                       min="0">
                                <span class="input-group-text modern-input-suffix">unid.</span>
                            </div>
                            @error('stock_minimo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="stock-indicator" id="stockIndicator">
                            <div class="indicator-header">
                                <small class="fw-bold">Estado del Stock</small>
                            </div>
                            <div class="indicator-content" id="stockStatus">
                                <!-- Se llena dinámicamente -->
                            </div>
                        </div>
                        
                        <div class="info-box">
                            <i class="fas fa-lightbulb text-warning"></i>
                            <small>El sistema alertará automáticamente cuando el stock esté por debajo del mínimo establecido.</small>
                        </div>
                    </div>
                </div>

                <!-- Precios -->
                <div class="card sidebar-card mb-4">
                    <div class="card-header sidebar-header">
                        <div class="d-flex align-items-center">
                            <div class="header-icon-small me-3">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div>
                                <h6 class="card-title mb-0">Gestión de Precios</h6>
                                <small class="text-muted">Costos y márgenes</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-section">
                            <label class="modern-label required">
                                <i class="fas fa-shopping-cart text-info me-2"></i>
                                Precio de Compra
                            </label>
                            <div class="input-group modern-input-group">
                                <span class="input-group-text modern-input-addon">
                                    S/
                                </span>
                                <input type="number" 
                                       step="0.01" 
                                       name="precio_compra" 
                                       id="precio_compra" 
                                       class="form-control modern-input @error('precio_compra') is-invalid @enderror" 
                                       value="{{ old('precio_compra', $producto->precio_compra) }}" 
                                       required 
                                       min="0">
                            </div>
                            <div class="currency-conversion">
                                <small class="text-muted">
                                    <i class="fas fa-exchange-alt me-1"></i>
                                    ≈ $<span id="precio_compra_usd">0.00</span> USD
                                </small>
                            </div>
                            @error('precio_compra')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-section">
                            <label class="modern-label required">
                                <i class="fas fa-hand-holding-usd text-success me-2"></i>
                                Precio de Venta
                            </label>
                            <div class="input-group modern-input-group">
                                <span class="input-group-text modern-input-addon">
                                    S/
                                </span>
                                <input type="number" 
                                       step="0.01" 
                                       name="precio_venta" 
                                       id="precio_venta" 
                                       class="form-control modern-input @error('precio_venta') is-invalid @enderror" 
                                       value="{{ old('precio_venta', $producto->precio_venta) }}" 
                                       required 
                                       min="0">
                            </div>
                            <div class="currency-conversion">
                                <small class="text-muted">
                                    <i class="fas fa-exchange-alt me-1"></i>
                                    ≈ $<span id="precio_venta_usd">0.00</span> USD
                                </small>
                            </div>
                            @error('precio_venta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="margin-calculator" id="margen_info">
                            <div class="margin-header">
                                <small class="fw-bold">
                                    <i class="fas fa-percentage me-1"></i>
                                    Análisis de Margen
                                </small>
                            </div>
                            <div class="margin-content">
                                <div class="margin-value">
                                    <span id="margen_porcentaje">0</span>%
                                </div>
                                <div class="margin-amount">
                                    Ganancia: S/ <span id="ganancia_amount">0.00</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="price-suggestions" id="priceSuggestions">
                            <small class="fw-bold text-muted mb-2 d-block">
                                <i class="fas fa-lightbulb me-1"></i>
                                Sugerencias de Precio
                            </small>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-success btn-sm" onclick="setMargin(20)">
                                    +20%
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="setMargin(30)">
                                    +30%
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="setMargin(50)">
                                    +50%
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="card actions-card">
                    <div class="card-header actions-header">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-cogs me-2"></i>
                            Acciones
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-action" id="updateBtn">
                                <i class="fas fa-save me-2"></i>
                                <span class="btn-text">Actualizar Producto</span>
                                <div class="btn-loader d-none">
                                    <i class="fas fa-spinner fa-spin me-2"></i>Guardando...
                                </div>
                            </button>
                            <a href="{{ route('productos.show', $producto) }}" class="btn btn-info btn-action">
                                <i class="fas fa-eye me-2"></i>
                                Ver Producto
                            </a>
                            <a href="{{ route('productos.index') }}" class="btn btn-secondary btn-action">
                                <i class="fas fa-arrow-left me-2"></i>
                                Volver al Listado
                            </a>
                            
                            <div class="action-separator"></div>
                            
                            <button type="button" class="btn btn-danger btn-action" onclick="confirmDelete()">
                                <i class="fas fa-trash me-2"></i>
                                Eliminar Producto
                            </button>
                        </div>
                        
                        <div class="action-info mt-3">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt me-1"></i>
                                Los cambios serán validados antes de guardar
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Historial de cambios -->
                @if($producto->updated_at && $producto->updated_at != $producto->created_at)
                <div class="card history-card mt-4">
                    <div class="card-header history-header">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-history me-2"></i>
                            Historial de Cambios
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="history-item">
                            <div class="history-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="history-content">
                                <div class="history-title">Última Modificación</div>
                                <div class="history-date">
                                    {{ $producto->updated_at->format('d/m/Y H:i:s') }}
                                </div>
                                <small class="history-time text-muted">
                                    {{ $producto->updated_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                        
                        <div class="history-item">
                            <div class="history-icon">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                            <div class="history-content">
                                <div class="history-title">Creación</div>
                                <div class="history-date">
                                    {{ $producto->created_at ? $producto->created_at->format('d/m/Y H:i:s') : 'N/A' }}
                                </div>
                                <small class="history-time text-muted">
                                    {{ $producto->created_at ? $producto->created_at->diffForHumans() : 'Fecha no disponible' }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </form>

    <!-- Modal de confirmación para eliminar -->
    <form action="{{ route('productos.destroy', $producto) }}" method="POST" id="deleteForm" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</div>

<style>
/* Estilos modernos para la edición de productos */
.modern-container {
    padding: 20px;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
}

/* Hero Header */
.hero-header {
    position: relative;
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    opacity: 0.05;
}

.hero-content {
    position: relative;
    padding: 30px;
    z-index: 1;
}

.hero-icon {
    width: 70px;
    height: 70px;
    border-radius: 18px;
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: white;
    box-shadow: 0 8px 25px rgba(240, 147, 251, 0.3);
}

.hero-title {
    font-size: 32px;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 8px;
}

.hero-subtitle {
    font-size: 16px;
    color: #718096;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
}

.product-code {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 4px 10px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 14px;
}

.product-separator {
    margin: 0 10px;
    color: #a0aec0;
}

.product-name {
    font-weight: 500;
    color: #4a5568;
}

.modern-breadcrumb {
    background: none;
    padding: 0;
    margin: 0;
    font-size: 13px;
}

.modern-breadcrumb .breadcrumb-item a {
    color: #667eea;
    text-decoration: none;
}

.exchange-rate-badge {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 10px;
    display: inline-block;
}

.btn-hero {
    padding: 12px 24px;
    border-radius: 12px;
    font-weight: 600;
    border: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.btn-hero:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}

/* Cards del formulario */
.form-card, .sidebar-card, .actions-card, .history-card {
    background: white;
    border: none;
    border-radius: 15px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.form-card:hover {
    transform: translateY(-3px);
}

.form-header, .sidebar-header, .actions-header, .history-header {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border-bottom: 1px solid #e2e8f0;
    padding: 20px 25px;
    border-radius: 15px 15px 0 0;
}

.header-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
}

.header-icon-small {
    width: 35px;
    height: 35px;
    border-radius: 8px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
}

/* Secciones del formulario */
.form-section {
    margin-bottom: 25px;
}

.modern-label {
    display: flex;
    align-items: center;
    font-size: 14px;
    font-weight: 600;
    color: #4a5568;
    margin-bottom: 8px;
}

.modern-label.required::after {
    content: ' *';
    color: #e53e3e;
    font-weight: bold;
}

/* Inputs modernos */
.modern-input-group {
    position: relative;
    margin-bottom: 8px;
}

.modern-input-addon {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border: 2px solid #e2e8f0;
    border-right: none;
    color: #718096;
}

.modern-input-suffix {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border: 2px solid #e2e8f0;
    border-left: none;
    color: #718096;
    font-size: 12px;
}

.modern-input {
    border: 2px solid #e2e8f0;
    padding: 12px 16px;
    font-size: 15px;
    transition: all 0.3s ease;
    background: #f8fafc;
}

.modern-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    background: white;
    outline: none;
}

/* Select moderno */
.modern-select-wrapper {
    position: relative;
}

.modern-select {
    border: 2px solid #e2e8f0;
    padding: 12px 40px 12px 16px;
    font-size: 15px;
    background: #f8fafc;
    appearance: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.modern-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    background: white;
    outline: none;
}

.select-icon {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #718096;
    pointer-events: none;
}

/* Textarea moderno */
.modern-textarea-wrapper {
    position: relative;
}

.modern-textarea {
    border: 2px solid #e2e8f0;
    padding: 12px 16px;
    font-size: 15px;
    background: #f8fafc;
    resize: vertical;
    min-height: 120px;
    transition: all 0.3s ease;
}

.modern-textarea:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    background: white;
    outline: none;
}

.textarea-counter {
    position: absolute;
    bottom: 8px;
    right: 12px;
    font-size: 12px;
    color: #a0aec0;
    background: rgba(255,255,255,0.9);
    padding: 2px 6px;
    border-radius: 4px;
}

/* Hints del formulario */
.form-hint {
    color: #718096;
    font-size: 12px;
    margin-top: 5px;
    display: flex;
    align-items: center;
}

/* Stock indicator */
.stock-indicator {
    background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 100%);
    padding: 15px;
    border-radius: 10px;
    margin: 15px 0;
    border-left: 4px solid #e2e8f0;
}

.indicator-header {
    margin-bottom: 8px;
}

.indicator-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.stock-warning {
    color: #d69e2e;
    font-size: 12px;
    margin-top: 5px;
    background: #fef5e7;
    padding: 4px 8px;
    border-radius: 4px;
    display: inline-block;
}

/* Calculadora de margen */
.margin-calculator {
    background: linear-gradient(135deg, #f0fff4 0%, #e6fffa 100%);
    padding: 15px;
    border-radius: 10px;
    margin: 15px 0;
    border-left: 4px solid #48bb78;
    transition: all 0.3s ease;
}

.margin-calculator.margin-danger {
    background: linear-gradient(135deg, #fed7d7 0%, #fbb6ce 100%);
    border-left-color: #e53e3e;
}

.margin-calculator.margin-warning {
    background: linear-gradient(135deg, #fefcbf 0%, #faf089 100%);
    border-left-color: #d69e2e;
}

.margin-header {
    margin-bottom: 8px;
}

.margin-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.margin-value {
    font-size: 18px;
    font-weight: 700;
    color: #2d3748;
}

.margin-amount {
    font-size: 12px;
    color: #4a5568;
}

.currency-conversion {
    margin-top: 4px;
}

/* Sugerencias de precio */
.price-suggestions {
    margin-top: 15px;
    padding: 12px;
    background: #f8fafc;
    border-radius: 8px;
}

/* Botones de acción */
.btn-action {
    padding: 12px 20px;
    border-radius: 10px;
    font-weight: 600;
    border: none;
    font-size: 14px;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}

.btn-action .btn-loader {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.action-separator {
    border-top: 1px solid #e2e8f0;
    margin: 15px 0;
}

.action-info {
    text-align: center;
    padding: 10px;
    background: #f8fafc;
    border-radius: 6px;
}

/* Información del historial */
.history-item {
    display: flex;
    align-items: flex-start;
    padding: 15px 0;
    border-bottom: 1px solid #f1f5f9;
}

.history-item:last-child {
    border-bottom: none;
}

.history-icon {
    width: 35px;
    height: 35px;
    border-radius: 8px;
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
    margin-right: 12px;
    flex-shrink: 0;
}

.history-title {
    font-weight: 600;
    color: #2d3748;
    font-size: 14px;
    margin-bottom: 2px;
}

.history-date {
    color: #4a5568;
    font-size: 13px;
    margin-bottom: 2px;
}

.history-time {
    font-size: 11px;
}

/* Caja de información */
.info-box {
    background: linear-gradient(135deg, #e6fffa 0%, #e0f2fe 100%);
    padding: 12px;
    border-radius: 8px;
    border-left: 3px solid #4facfe;
    margin-top: 15px;
    display: flex;
    align-items: flex-start;
    gap: 8px;
}

/* Alertas modernas */
.modern-alert {
    border: none;
    border-radius: 12px;
    padding: 20px;
    border-left: 4px solid;
    display: flex;
    align-items: flex-start;
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
    font-size: 18px;
    flex-shrink: 0;
}

.alert-content {
    flex: 1;
}

/* Animaciones */
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

.form-card, .sidebar-card, .actions-card, .history-card {
    animation: fadeInUp 0.6s ease-out;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-content {
        padding: 20px;
    }
    
    .hero-title {
        font-size: 24px;
    }
    
    .hero-actions {
        margin-top: 15px;
        text-align: center;
    }
    
    .hero-actions .btn {
        display: block;
        width: 100%;
        margin-bottom: 8px;
    }
    
    .exchange-rate-badge {
        display: block;
        text-align: center;
        margin-bottom: 10px;
    }
    
    .product-separator {
        display: none;
    }
    
    .hero-subtitle {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }
}
</style>

<script>
// JavaScript para la edición de productos
document.addEventListener('DOMContentLoaded', function() {
    const tipoCambio = {{ $tipoCambio }};
    const precioCompraInput = document.getElementById('precio_compra');
    const precioVentaInput = document.getElementById('precio_venta');
    const precioCompraUsd = document.getElementById('precio_compra_usd');
    const precioVentaUsd = document.getElementById('precio_venta_usd');
    const margenInfo = document.getElementById('margen_info');
    const margenPorcentaje = document.getElementById('margen_porcentaje');
    const gananciaAmount = document.getElementById('ganancia_amount');
    const stockActual = document.getElementById('stock_actual');
    const stockMinimo = document.getElementById('stock_minimo');
    const stockStatus = document.getElementById('stockStatus');
    const descripcionInput = document.getElementById('descripcion');
    const charCount = document.getElementById('descCharCount');
    const form = document.getElementById('productForm');
    const updateBtn = document.getElementById('updateBtn');

    // Cálculo de conversiones y márgenes
    function calcularConversiones() {
        const precioCompra = parseFloat(precioCompraInput.value) || 0;
        const precioVenta = parseFloat(precioVentaInput.value) || 0;
        
        // Conversiones a USD
        precioCompraUsd.textContent = (precioCompra / tipoCambio).toFixed(2);
        precioVentaUsd.textContent = (precioVenta / tipoCambio).toFixed(2);
        
        // Calcular margen
        if (precioCompra > 0 && precioVenta > 0) {
            const margen = ((precioVenta - precioCompra) / precioCompra * 100);
            const ganancia = precioVenta - precioCompra;
            
            margenPorcentaje.textContent = margen.toFixed(1);
            gananciaAmount.textContent = ganancia.toFixed(2);
            
            // Actualizar clases según el margen
            margenInfo.className = 'margin-calculator';
            if (margen < 0) {
                margenInfo.className += ' margin-danger';
            } else if (margen < 20) {
                margenInfo.className += ' margin-warning';
            }
        } else {
            margenPorcentaje.textContent = '0';
            gananciaAmount.textContent = '0.00';
        }
    }

    // Validación de stock
    function validarStock() {
        const actual = parseInt(stockActual.value) || 0;
        const minimo = parseInt(stockMinimo.value) || 0;
        
        let statusHTML = '';
        let statusClass = '';
        
        if (actual <= 0) {
            statusHTML = '<span class="badge bg-danger">Sin Stock</span>';
        } else if (actual <= minimo) {
            statusHTML = '<span class="badge bg-warning">Stock Bajo</span>';
        } else if (actual <= minimo * 2) {
            statusHTML = '<span class="badge bg-info">Stock Moderado</span>';
        } else {
            statusHTML = '<span class="badge bg-success">Stock Adecuado</span>';
        }
        
        if (stockStatus) {
            stockStatus.innerHTML = statusHTML;
        }
    }

    // Contador de caracteres
    function updateCharCount() {
        if (descripcionInput && charCount) {
            const count = descripcionInput.value.length;
            charCount.textContent = count;
            
            if (count > 800) {
                charCount.style.color = '#e53e3e';
            } else if (count > 600) {
                charCount.style.color = '#d69e2e';
            } else {
                charCount.style.color = '#a0aec0';
            }
        }
    }

    // Validación en tiempo real
    function validateField(field) {
        const value = field.value.trim();
        
        if (field.hasAttribute('required') && !value) {
            field.classList.add('is-invalid');
            field.classList.remove('is-valid');
        } else if (value) {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
        } else {
            field.classList.remove('is-invalid', 'is-valid');
        }
    }

    // Event listeners
    if (precioCompraInput) {
        precioCompraInput.addEventListener('input', calcularConversiones);
        precioCompraInput.addEventListener('blur', function() { validateField(this); });
    }
    
    if (precioVentaInput) {
        precioVentaInput.addEventListener('input', calcularConversiones);
        precioVentaInput.addEventListener('blur', function() { validateField(this); });
    }
    
    if (stockActual) {
        stockActual.addEventListener('input', validarStock);
        stockActual.addEventListener('blur', function() { validateField(this); });
    }
    
    if (stockMinimo) {
        stockMinimo.addEventListener('input', validarStock);
        stockMinimo.addEventListener('blur', function() { validateField(this); });
    }
    
    if (descripcionInput) {
        descripcionInput.addEventListener('input', updateCharCount);
        descripcionInput.addEventListener('blur', function() { validateField(this); });
        
        // Auto-resize del textarea
        descripcionInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.max(this.scrollHeight, 120) + 'px';
        });
    }

    // Envío del formulario
    if (form && updateBtn) {
        form.addEventListener('submit', function(e) {
            const btnText = updateBtn.querySelector('.btn-text');
            const btnLoader = updateBtn.querySelector('.btn-loader');
            
            if (btnText && btnLoader) {
                btnText.classList.add('d-none');
                btnLoader.classList.remove('d-none');
                updateBtn.disabled = true;
            }
        });
    }

    // Inicializar cálculos
    calcularConversiones();
    validarStock();
    updateCharCount();
    
    // Animaciones al cargar
    const cards = document.querySelectorAll('.form-card, .sidebar-card, .actions-card, .history-card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});

// Función para establecer margen sugerido
function setMargin(percentage) {
    const precioCompra = parseFloat(document.getElementById('precio_compra').value) || 0;
    if (precioCompra > 0) {
        const precioVenta = precioCompra * (1 + percentage / 100);
        document.getElementById('precio_venta').value = precioVenta.toFixed(2);
        
        // Disparar evento para recalcular
        document.getElementById('precio_venta').dispatchEvent(new Event('input'));
    } else {
        alert('Primero ingrese el precio de compra');
    }
}

// Confirmación de eliminación
function confirmDelete() {
    const productName = '{{ $producto->descripcion }}';
    const productCode = '{{ $producto->codigo }}';
    
    if (confirm(`¿Está seguro de que desea eliminar el producto "${productCode} - ${productName}"?\n\nEsta acción no se puede deshacer y eliminará:\n• El producto del inventario\n• Todas las referencias en ventas futuras\n• Los datos de stock y precios`)) {
        document.getElementById('deleteForm').submit();
    }
}

// Validación de código único (simulada)
document.getElementById('codigo').addEventListener('blur', function() {
    const codigo = this.value.trim();
    if (codigo && codigo !== '{{ $producto->codigo }}') {
        // Aquí podrías hacer una llamada AJAX para verificar unicidad
        // Por ahora solo validamos formato
        const pattern = /^[A-Z0-9\-_]+$/;
        if (!pattern.test(codigo)) {
            this.setCustomValidity('El código debe contener solo letras mayúsculas, números, guiones y guiones bajos');
        } else {
            this.setCustomValidity('');
        }
    }
});
</script>
@endsection