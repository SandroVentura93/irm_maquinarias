@extends('layouts.dashboard')

@section('content')
<div class="container-fluid modern-container">
    <!-- Modern Header -->
    <div class="page-header mb-4">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="page-icon me-3">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <div>
                    <h2 class="page-title mb-0">Nuevo Producto</h2>
                    <p class="page-subtitle mb-0">Registra un nuevo producto en el inventario</p>
                    <nav class="breadcrumb-modern mt-1">
                        <a href="{{ route('productos.index') }}" class="breadcrumb-link">
                            <i class="fas fa-boxes me-1"></i>Productos
                        </a>
                        <span class="breadcrumb-separator">/</span>
                        <span class="breadcrumb-current">Nuevo Producto</span>
                    </nav>
                </div>
            </div>
            <div class="header-actions d-flex align-items-center">
                <div class="tipo-cambio-badge me-3">
                    <i class="fas fa-exchange-alt me-2"></i>
                    <strong>TC:</strong> S/ {{ number_format($tipoCambio, 2) }}/USD
                </div>
                <button type="submit" form="productForm" class="btn btn-success btn-modern">
                    <i class="fas fa-save me-2"></i>Guardar Producto
                </button>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger modern-alert alert-dismissible fade show">
            <div class="alert-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="alert-content">
                <strong>Errores en el formulario:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('productos.store') }}" method="POST" id="productForm">
        @csrf
        <div class="row">
            <!-- Información Básica -->
            <div class="col-lg-8">
                <!-- Información Principal -->
                <div class="card modern-card mb-4">
                    <div class="card-header modern-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2 text-primary"></i>
                            Información Básica
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="modern-label required">
                                    <i class="fas fa-barcode me-1"></i>
                                    Código del Producto
                                </label>
                                <input type="text" name="codigo" id="codigo" class="form-control modern-input" 
                                       value="{{ old('codigo') }}" required placeholder="🔢 Ej: PROD001" maxlength="50">
                                <div class="form-text">Código único identificador</div>
                            </div>
                            <div class="col-md-6">
                                <label class="modern-label">
                                    <i class="fas fa-tag me-1"></i>
                                    Número de Parte
                                </label>
                                <input type="text" name="numero_parte" id="numero_parte" class="form-control modern-input" 
                                       value="{{ old('numero_parte') }}" placeholder="🏷️ Ej: NP-001" maxlength="50">
                                <div class="form-text">Referencia del fabricante</div>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <label class="modern-label required">
                                <i class="fas fa-align-left me-1"></i>
                                Descripción del Producto
                            </label>
                            <textarea name="descripcion" id="descripcion" class="form-control modern-input" rows="3" required 
                                      placeholder="📝 Descripción detallada del producto...">{{ old('descripcion') }}</textarea>
                            <div class="form-text">Describe las características principales del producto</div>
                        </div>
                        
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label class="modern-label">
                                    <i class="fas fa-cog me-1"></i>
                                    Modelo
                                </label>
                                <input type="text" name="modelo" id="modelo" class="form-control modern-input" 
                                       value="{{ old('modelo') }}" placeholder="⚙️ Modelo del producto" maxlength="100">
                            </div>
                            <div class="col-md-6">
                                <label class="modern-label">
                                    <i class="fas fa-weight me-1"></i>
                                    Peso (Kilogramos)
                                </label>
                                <input type="number" step="0.01" name="peso" id="peso" class="form-control modern-input" 
                                       value="{{ old('peso') }}" placeholder="⚖️ 0.00" min="0" max="999999.99">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Clasificación y Configuración -->
                <div class="card modern-card">
                    <div class="card-header modern-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-tags me-2 text-success"></i>
                            Clasificación y Configuración
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="modern-label">
                                    <i class="fas fa-list me-1"></i>
                                    Categoría
                                </label>
                                <select name="id_categoria" id="id_categoria" class="form-select modern-select">
                                    <option value="">🏷️ Seleccione categoría</option>
                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id }}" {{ old('id_categoria') == $categoria->id ? 'selected' : '' }}>
                                            {{ $categoria->descripcion }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="modern-label">
                                    <i class="fas fa-certificate me-1"></i>
                                    Marca
                                </label>
                                <select name="id_marca" id="id_marca" class="form-select modern-select">
                                    <option value="">🏭 Seleccione marca</option>
                                    @foreach ($marcas as $marca)
                                        <option value="{{ $marca->id }}" {{ old('id_marca') == $marca->id ? 'selected' : '' }}>
                                            {{ $marca->descripcion }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="modern-label">
                                    <i class="fas fa-truck me-1"></i>
                                    Proveedor
                                </label>
                                <select name="id_proveedor" id="id_proveedor" class="form-select modern-select">
                                    <option value="">🚛 Seleccione proveedor</option>
                                    @foreach ($proveedores as $proveedor)
                                        <option value="{{ $proveedor->id }}" {{ old('id_proveedor') == $proveedor->id ? 'selected' : '' }}>
                                            {{ $proveedor->nombre ?? $proveedor->razon_social ?? $proveedor->descripcion }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label class="modern-label">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    Ubicación en Almacén
                                </label>
                                <input type="text" name="ubicacion" id="ubicacion" class="form-control modern-input" 
                                       value="{{ old('ubicacion') }}" placeholder="📍 Ej: Almacén A-1, Estante 3" maxlength="100">
                                <div class="form-text">Ubicación física del producto</div>
                            </div>
                            <div class="col-md-3">
                                <label class="modern-label">
                                    <i class="fas fa-globe me-1"></i>
                                    ¿Es Importado?
                                </label>
                                <select name="importado" id="importado" class="form-select modern-select">
                                    <option value="0" {{ old('importado') == '0' ? 'selected' : '' }}>🏠 Nacional</option>
                                    <option value="1" {{ old('importado') == '1' ? 'selected' : '' }}>🌍 Importado</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="modern-label">
                                    <i class="fas fa-toggle-on me-1"></i>
                                    Estado del Producto
                                </label>
                                <select name="activo" id="activo" class="form-select modern-select">
                                    <option value="1" {{ old('activo', '1') == '1' ? 'selected' : '' }}>✅ Activo</option>
                                    <option value="0" {{ old('activo') == '0' ? 'selected' : '' }}>❌ Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Lateral -->
            <div class="col-lg-4">
                <!-- Inventario -->
                <div class="card modern-card mb-4">
                    <div class="card-header modern-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-cubes me-2 text-warning"></i>
                            Control de Inventario
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="modern-label required">
                                <i class="fas fa-boxes me-1"></i>
                                Stock Actual
                            </label>
                            <input type="number" name="stock_actual" id="stock_actual" class="form-control modern-input" 
                                   value="{{ old('stock_actual', 0) }}" required min="0" max="999999">
                            <div class="form-text">Cantidad disponible actualmente</div>
                        </div>
                        <div class="mb-3">
                            <label class="modern-label required">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Stock Mínimo
                            </label>
                            <input type="number" name="stock_minimo" id="stock_minimo" class="form-control modern-input" 
                                   value="{{ old('stock_minimo', 1) }}" required min="0" max="999999">
                            <div class="form-text">Nivel de alerta de stock bajo</div>
                        </div>
                        <div class="stock-status-indicator mt-3" id="stockIndicator">
                            <div class="stock-info-card">
                                <i class="fas fa-info-circle text-info"></i>
                                <small>Configure los valores para ver el estado del stock</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Precios -->
                <div class="card modern-card mb-4">
                    <div class="card-header modern-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-dollar-sign me-2 text-success"></i>
                            Configuración de Precios
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="modern-label required">
                                <i class="fas fa-shopping-cart me-1 text-info"></i>
                                Precio de Compra (S/)
                            </label>
                            <div class="price-input-group">
                                <input type="number" step="0.01" name="precio_compra" id="precio_compra" 
                                       class="form-control modern-input price-input" value="{{ old('precio_compra') }}" 
                                       required min="0" max="999999.99" placeholder="0.00">
                            </div>
                            <div class="price-conversion">
                                <small class="text-muted">
                                    <i class="fas fa-equals me-1"></i>
                                    ≈ $<span id="precio_compra_usd" class="fw-bold">0.00</span> USD
                                </small>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="modern-label required">
                                <i class="fas fa-hand-holding-usd me-1 text-success"></i>
                                Precio de Venta (S/)
                            </label>
                            <div class="price-input-group">
                                <input type="number" step="0.01" name="precio_venta" id="precio_venta" 
                                       class="form-control modern-input price-input" value="{{ old('precio_venta') }}" 
                                       required min="0" max="999999.99" placeholder="0.00">
                            </div>
                            <div class="price-conversion">
                                <small class="text-muted">
                                    <i class="fas fa-equals me-1"></i>
                                    ≈ $<span id="precio_venta_usd" class="fw-bold">0.00</span> USD
                                </small>
                            </div>
                        </div>
                        
                        <!-- Indicador de Margen -->
                        <div class="margin-indicator mt-3" id="margen_info" style="display: none;">
                            <div class="margin-card">
                                <div class="margin-header">
                                    <i class="fas fa-chart-line me-2"></i>
                                    <strong>Margen de Ganancia</strong>
                                </div>
                                <div class="margin-value">
                                    <span id="margen_porcentaje">0</span>%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="card modern-card">
                    <div class="card-body">
                        <div class="action-buttons">
                            <button type="submit" class="btn btn-success btn-modern w-100 mb-3">
                                <i class="fas fa-save me-2"></i>Guardar Producto
                            </button>
                            <div class="row g-2">
                                <div class="col-6">
                                    <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary btn-modern w-100">
                                        <i class="fas fa-arrow-left me-2"></i>Cancelar
                                    </a>
                                </div>
                                <div class="col-6">
                                    <button type="reset" class="btn btn-outline-warning btn-modern w-100" id="resetForm">
                                        <i class="fas fa-undo me-2"></i>Limpiar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer modern-footer">
                        <div class="help-text">
                            <i class="fas fa-lightbulb text-warning me-2"></i>
                            <small class="text-muted">
                                Los campos marcados con <span class="text-danger">*</span> son obligatorios
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
/* Estilos modernos para crear productos */
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

.modern-footer {
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    padding: 15px 25px;
}

/* Labels e inputs modernos */
.modern-label {
    font-weight: 600;
    color: #4a5568;
    font-size: 14px;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
}

.modern-label.required::after {
    content: '*';
    color: #e53e3e;
    margin-left: 4px;
    font-weight: bold;
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

.form-text {
    font-size: 12px;
    color: #718096;
    margin-top: 5px;
}

/* Alertas modernas */
.modern-alert {
    border: none;
    border-radius: 12px;
    padding: 20px;
    border-left: 4px solid;
    display: flex;
    align-items: center;
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
}

.alert-content {
    flex: 1;
}

/* Indicadores de stock */
.stock-status-indicator {
    border-radius: 10px;
    padding: 15px;
    text-align: center;
}

.stock-info-card {
    background: #f0f9ff;
    border: 1px solid #bae6fd;
    border-radius: 8px;
    padding: 12px;
    color: #0369a1;
}

.stock-normal {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    border-left: 4px solid #28a745;
    color: #155724;
}

.stock-warning {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    border-left: 4px solid #ffc107;
    color: #856404;
    animation: pulse 2s infinite;
}

.stock-danger {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    border-left: 4px solid #dc3545;
    color: #721c24;
    animation: pulse 2s infinite;
}

/* Precios */
.price-input-group {
    position: relative;
}

.price-input {
    padding-left: 40px;
}

.price-input-group::before {
    content: 'S/';
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #718096;
    font-weight: 600;
    z-index: 3;
}

.price-conversion {
    margin-top: 8px;
    padding: 8px 12px;
    background: #f7fafc;
    border-radius: 6px;
    border-left: 3px solid #4299e1;
}

/* Indicador de margen */
.margin-indicator {
    border-radius: 10px;
    overflow: hidden;
}

.margin-card {
    padding: 15px;
    text-align: center;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.margin-header {
    font-size: 14px;
    margin-bottom: 8px;
    color: #4a5568;
}

.margin-value {
    font-size: 24px;
    font-weight: 700;
}

.margin-positive {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    border-left: 4px solid #28a745;
    color: #155724;
}

.margin-low {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    border-left: 4px solid #ffc107;
    color: #856404;
}

.margin-negative {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    border-left: 4px solid #dc3545;
    color: #721c24;
}

/* Botones de acción */
.action-buttons {
    text-align: center;
}

.help-text {
    text-align: center;
    margin: 0;
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
    }
    
    .tipo-cambio-badge {
        margin-bottom: 10px;
    }
}
</style>

<script>
// JavaScript mejorado para crear productos
document.addEventListener('DOMContentLoaded', function() {
    const tipoCambio = {{ $tipoCambio }};
    const precioCompraInput = document.getElementById('precio_compra');
    const precioVentaInput = document.getElementById('precio_venta');
    const precioCompraUsd = document.getElementById('precio_compra_usd');
    const precioVentaUsd = document.getElementById('precio_venta_usd');
    const margenInfo = document.getElementById('margen_info');
    const margenPorcentaje = document.getElementById('margen_porcentaje');
    const stockActual = document.getElementById('stock_actual');
    const stockMinimo = document.getElementById('stock_minimo');
    const stockIndicator = document.getElementById('stockIndicator');

    // Función para calcular conversiones y márgenes
    function calcularConversiones() {
        const precioCompra = parseFloat(precioCompraInput.value) || 0;
        const precioVenta = parseFloat(precioVentaInput.value) || 0;
        
        // Conversiones a USD
        precioCompraUsd.textContent = (precioCompra / tipoCambio).toFixed(2);
        precioVentaUsd.textContent = (precioVenta / tipoCambio).toFixed(2);
        
        // Calcular y mostrar margen
        if (precioCompra > 0 && precioVenta > 0) {
            const margen = ((precioVenta - precioCompra) / precioCompra * 100);
            margenPorcentaje.innerHTML = margen.toFixed(1);
            margenInfo.style.display = 'block';
            
            const marginCard = margenInfo.querySelector('.margin-card');
            marginCard.classList.remove('margin-positive', 'margin-low', 'margin-negative');
            
            if (margen < 0) {
                marginCard.classList.add('margin-negative');
                margenPorcentaje.innerHTML += ' <i class="fas fa-arrow-down"></i>';
            } else if (margen < 20) {
                marginCard.classList.add('margin-low');
                margenPorcentaje.innerHTML += ' <i class="fas fa-minus"></i>';
            } else {
                marginCard.classList.add('margin-positive');
                margenPorcentaje.innerHTML += ' <i class="fas fa-arrow-up"></i>';
            }
        } else {
            margenInfo.style.display = 'none';
        }
    }

    // Función para validar stock
    function validarStock() {
        const actual = parseInt(stockActual.value) || 0;
        const minimo = parseInt(stockMinimo.value) || 0;
        
        stockIndicator.innerHTML = '';
        
        if (actual === 0 && minimo === 0) {
            stockIndicator.innerHTML = `
                <div class="stock-info-card">
                    <i class="fas fa-info-circle text-info"></i>
                    <small>Configure los valores para ver el estado del stock</small>
                </div>
            `;
        } else if (actual <= minimo && actual > 0) {
            stockIndicator.innerHTML = `
                <div class="stock-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Stock Bajo:</strong> ${actual} ≤ ${minimo} (mínimo)
                </div>
            `;
        } else if (actual === 0) {
            stockIndicator.innerHTML = `
                <div class="stock-danger">
                    <i class="fas fa-times-circle"></i>
                    <strong>Sin Stock:</strong> Producto agotado
                </div>
            `;
        } else {
            stockIndicator.innerHTML = `
                <div class="stock-normal">
                    <i class="fas fa-check-circle"></i>
                    <strong>Stock Normal:</strong> ${actual} > ${minimo} (mínimo)
                </div>
            `;
        }
    }

    // Event listeners
    precioCompraInput.addEventListener('input', calcularConversiones);
    precioVentaInput.addEventListener('input', calcularConversiones);
    stockActual.addEventListener('input', validarStock);
    stockMinimo.addEventListener('input', validarStock);

    // Limpiar formulario
    document.getElementById('resetForm').addEventListener('click', function(e) {
        e.preventDefault();
        
        if (confirm('¿Está seguro de que desea limpiar todos los campos?')) {
            document.getElementById('productForm').reset();
            margenInfo.style.display = 'none';
            precioCompraUsd.textContent = '0.00';
            precioVentaUsd.textContent = '0.00';
            
            stockIndicator.innerHTML = `
                <div class="stock-info-card">
                    <i class="fas fa-info-circle text-info"></i>
                    <small>Configure los valores para ver el estado del stock</small>
                </div>
            `;
        }
    });

    // Validación del formulario antes de enviar
    document.getElementById('productForm').addEventListener('submit', function(e) {
        const codigo = document.getElementById('codigo').value.trim();
        const descripcion = document.getElementById('descripcion').value.trim();
        
        if (!codigo || !descripcion) {
            e.preventDefault();
            alert('Por favor, complete todos los campos obligatorios.');
            return false;
        }
        
        if (parseFloat(precioCompraInput.value) <= 0 || parseFloat(precioVentaInput.value) <= 0) {
            e.preventDefault();
            alert('Los precios deben ser mayores a cero.');
            return false;
        }
        
        // Mostrar loading en el botón
        const submitBtn = this.querySelector('[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';
        submitBtn.disabled = true;
    });

    // Animaciones al cargar
    const cards = document.querySelectorAll('.modern-card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 150);
    });

    // Calcular valores iniciales
    calcularConversiones();
    validarStock();
});
</script>
@endsection