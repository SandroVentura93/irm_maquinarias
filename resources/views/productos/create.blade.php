@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1><i class="fas fa-plus-circle"></i> Nuevo Producto</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('productos.index') }}">Productos</a></li>
                    <li class="breadcrumb-item active">Nuevo Producto</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-4 text-right">
            <div class="alert alert-info mb-0">
                <small><i class="fas fa-exchange-alt"></i> TC: S/ {{ number_format($tipoCambio, 2) }}/USD</small>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Error en el formulario:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <form action="{{ route('productos.store') }}" method="POST" id="productForm">
        @csrf
        <div class="row">
            <!-- Información Básica -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información Básica</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="codigo"><i class="fas fa-barcode"></i> Código *</label>
                                    <input type="text" name="codigo" id="codigo" class="form-control" 
                                           value="{{ old('codigo') }}" required placeholder="Ej: PROD001">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="numero_parte"><i class="fas fa-tag"></i> Número de Parte</label>
                                    <input type="text" name="numero_parte" id="numero_parte" class="form-control" 
                                           value="{{ old('numero_parte') }}" placeholder="Ej: NP-001">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="descripcion"><i class="fas fa-align-left"></i> Descripción *</label>
                            <textarea name="descripcion" id="descripcion" class="form-control" rows="3" required 
                                      placeholder="Descripción detallada del producto">{{ old('descripcion') }}</textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="modelo"><i class="fas fa-cog"></i> Modelo</label>
                                    <input type="text" name="modelo" id="modelo" class="form-control" 
                                           value="{{ old('modelo') }}" placeholder="Modelo del producto">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="peso"><i class="fas fa-weight"></i> Peso (kg)</label>
                                    <input type="number" step="0.01" name="peso" id="peso" class="form-control" 
                                           value="{{ old('peso') }}" placeholder="0.00">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Clasificación -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-tags"></i> Clasificación</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="id_categoria"><i class="fas fa-list"></i> Categoría</label>
                                    <select name="id_categoria" id="id_categoria" class="form-control">
                                        <option value="">Seleccione una categoría</option>
                                        @foreach ($categorias as $categoria)
                                            <option value="{{ $categoria->id }}" {{ old('id_categoria') == $categoria->id ? 'selected' : '' }}>
                                                {{ $categoria->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="id_marca"><i class="fas fa-certificate"></i> Marca</label>
                                    <select name="id_marca" id="id_marca" class="form-control">
                                        <option value="">Seleccione una marca</option>
                                        @foreach ($marcas as $marca)
                                            <option value="{{ $marca->id }}" {{ old('id_marca') == $marca->id ? 'selected' : '' }}>
                                                {{ $marca->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="id_proveedor"><i class="fas fa-truck"></i> Proveedor</label>
                                    <select name="id_proveedor" id="id_proveedor" class="form-control">
                                        <option value="">Seleccione un proveedor</option>
                                        @foreach ($proveedores as $proveedor)
                                            <option value="{{ $proveedor->id }}" {{ old('id_proveedor') == $proveedor->id ? 'selected' : '' }}>
                                                {{ $proveedor->nombre ?? $proveedor->razon_social ?? $proveedor->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ubicacion"><i class="fas fa-map-marker-alt"></i> Ubicación</label>
                                    <input type="text" name="ubicacion" id="ubicacion" class="form-control" 
                                           value="{{ old('ubicacion') }}" placeholder="Ej: Almacén A-1">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="importado"><i class="fas fa-globe"></i> Importado</label>
                                    <select name="importado" id="importado" class="form-control">
                                        <option value="0" {{ old('importado') == '0' ? 'selected' : '' }}>No</option>
                                        <option value="1" {{ old('importado') == '1' ? 'selected' : '' }}>Sí</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="activo"><i class="fas fa-toggle-on"></i> Estado</label>
                                    <select name="activo" id="activo" class="form-control">
                                        <option value="1" {{ old('activo', '1') == '1' ? 'selected' : '' }}>Activo</option>
                                        <option value="0" {{ old('activo') == '0' ? 'selected' : '' }}>Inactivo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Lateral -->
            <div class="col-md-4">
                <!-- Stock -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-cubes"></i> Inventario</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="stock_actual"><i class="fas fa-boxes"></i> Stock Actual *</label>
                            <input type="number" name="stock_actual" id="stock_actual" class="form-control" 
                                   value="{{ old('stock_actual', 0) }}" required min="0">
                        </div>
                        <div class="form-group">
                            <label for="stock_minimo"><i class="fas fa-exclamation-triangle text-warning"></i> Stock Mínimo *</label>
                            <input type="number" name="stock_minimo" id="stock_minimo" class="form-control" 
                                   value="{{ old('stock_minimo', 1) }}" required min="0">
                        </div>
                        <div class="alert alert-info">
                            <small><i class="fas fa-lightbulb"></i> El sistema alertará cuando el stock esté por debajo del mínimo.</small>
                        </div>
                    </div>
                </div>

                <!-- Precios -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-dollar-sign"></i> Precios</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="precio_compra"><i class="fas fa-shopping-cart text-info"></i> Precio Compra (PEN) *</label>
                            <input type="number" step="0.01" name="precio_compra" id="precio_compra" 
                                   class="form-control" value="{{ old('precio_compra') }}" required min="0">
                            <small class="text-muted">≈ $<span id="precio_compra_usd">0.00</span> USD</small>
                        </div>
                        <div class="form-group">
                            <label for="precio_venta"><i class="fas fa-hand-holding-usd text-success"></i> Precio Venta (PEN) *</label>
                            <input type="number" step="0.01" name="precio_venta" id="precio_venta" 
                                   class="form-control" value="{{ old('precio_venta') }}" required min="0">
                            <small class="text-muted">≈ $<span id="precio_venta_usd">0.00</span> USD</small>
                        </div>
                        <div class="alert alert-success" id="margen_info" style="display: none;">
                            <small><strong>Margen:</strong> <span id="margen_porcentaje">0</span>%</small>
                        </div>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="card mt-3">
                    <div class="card-body">
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fas fa-save"></i> Guardar Producto
                        </button>
                        <a href="{{ route('productos.index') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                        <button type="reset" class="btn btn-warning btn-block" id="resetForm">
                            <i class="fas fa-undo"></i> Limpiar Formulario
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoCambio = {{ $tipoCambio }};
    const precioCompraInput = document.getElementById('precio_compra');
    const precioVentaInput = document.getElementById('precio_venta');
    const precioCompraUsd = document.getElementById('precio_compra_usd');
    const precioVentaUsd = document.getElementById('precio_venta_usd');
    const margenInfo = document.getElementById('margen_info');
    const margenPorcentaje = document.getElementById('margen_porcentaje');

    function calcularConversiones() {
        const precioCompra = parseFloat(precioCompraInput.value) || 0;
        const precioVenta = parseFloat(precioVentaInput.value) || 0;
        
        // Conversiones a USD
        precioCompraUsd.textContent = (precioCompra / tipoCambio).toFixed(2);
        precioVentaUsd.textContent = (precioVenta / tipoCambio).toFixed(2);
        
        // Calcular margen
        if (precioCompra > 0 && precioVenta > 0) {
            const margen = ((precioVenta - precioCompra) / precioCompra * 100);
            margenPorcentaje.textContent = margen.toFixed(1);
            margenInfo.style.display = 'block';
            
            if (margen < 0) {
                margenInfo.className = 'alert alert-danger';
                margenPorcentaje.innerHTML = margen.toFixed(1) + ' <i class="fas fa-arrow-down"></i>';
            } else if (margen < 20) {
                margenInfo.className = 'alert alert-warning';
                margenPorcentaje.innerHTML = margen.toFixed(1) + ' <i class="fas fa-minus"></i>';
            } else {
                margenInfo.className = 'alert alert-success';
                margenPorcentaje.innerHTML = margen.toFixed(1) + ' <i class="fas fa-arrow-up"></i>';
            }
        } else {
            margenInfo.style.display = 'none';
        }
    }

    // Event listeners para cálculos en tiempo real
    precioCompraInput.addEventListener('input', calcularConversiones);
    precioVentaInput.addEventListener('input', calcularConversiones);

    // Limpiar formulario
    document.getElementById('resetForm').addEventListener('click', function() {
        margenInfo.style.display = 'none';
        precioCompraUsd.textContent = '0.00';
        precioVentaUsd.textContent = '0.00';
    });

    // Validación de stock
    const stockActual = document.getElementById('stock_actual');
    const stockMinimo = document.getElementById('stock_minimo');
    
    function validarStock() {
        const actual = parseInt(stockActual.value) || 0;
        const minimo = parseInt(stockMinimo.value) || 0;
        
        if (actual <= minimo && actual > 0) {
            stockActual.style.borderColor = '#ffc107';
        } else {
            stockActual.style.borderColor = '';
        }
    }
    
    stockActual.addEventListener('input', validarStock);
    stockMinimo.addEventListener('input', validarStock);

    // Calcular conversiones iniciales
    calcularConversiones();
});
</script>
@endsection