@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1><i class="fas fa-eye"></i> Detalles del Producto</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('productos.index') }}">Productos</a></li>
                    <li class="breadcrumb-item active">{{ $producto->codigo }}</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-4 text-right">
            <div class="alert alert-info mb-0">
                <small><i class="fas fa-exchange-alt"></i> TC: S/ {{ number_format($tipoCambio, 2) }}/USD</small>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Información Principal -->
        <div class="col-md-8">
            <!-- Información Básica -->
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-box"></i>
                        {{ $producto->descripcion }}
                    </h5>
                    <span class="badge badge-{{ $producto->activo ? 'success' : 'secondary' }} badge-lg">
                        {{ $producto->activo ? 'ACTIVO' : 'INACTIVO' }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary border-bottom pb-2"><i class="fas fa-info-circle"></i> INFORMACIÓN BÁSICA</h6>
                            
                            <div class="mb-3">
                                <label class="font-weight-bold text-muted">Código:</label>
                                <div class="h6">
                                    <span class="badge badge-secondary badge-lg">{{ $producto->codigo }}</span>
                                </div>
                            </div>

                            @if($producto->numero_parte)
                            <div class="mb-3">
                                <label class="font-weight-bold text-muted">Número de Parte:</label>
                                <div class="h6">{{ $producto->numero_parte }}</div>
                            </div>
                            @endif

                            @if($producto->modelo)
                            <div class="mb-3">
                                <label class="font-weight-bold text-muted">Modelo:</label>
                                <div class="h6">{{ $producto->modelo }}</div>
                            </div>
                            @endif

                            @if($producto->peso)
                            <div class="mb-3">
                                <label class="font-weight-bold text-muted">Peso:</label>
                                <div class="h6">{{ $producto->peso }} kg</div>
                            </div>
                            @endif

                            @if($producto->ubicacion)
                            <div class="mb-3">
                                <label class="font-weight-bold text-muted">Ubicación:</label>
                                <div class="h6">
                                    <span class="badge badge-info">
                                        <i class="fas fa-map-marker-alt"></i> {{ $producto->ubicacion }}
                                    </span>
                                </div>
                            </div>
                            @endif

                            <div class="mb-3">
                                <label class="font-weight-bold text-muted">Producto Importado:</label>
                                <div class="h6">
                                    <span class="badge badge-{{ $producto->importado ? 'warning' : 'success' }}">
                                        <i class="fas fa-{{ $producto->importado ? 'globe' : 'home' }}"></i>
                                        {{ $producto->importado ? 'SÍ' : 'NO' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-success border-bottom pb-2"><i class="fas fa-dollar-sign"></i> PRECIOS</h6>
                            
                            <div class="mb-3">
                                <label class="font-weight-bold text-muted">Precio de Compra:</label>
                                <div class="h4 text-info mb-1">S/ {{ number_format($producto->precio_compra, 2) }}</div>
                                <small class="text-muted">≈ ${{ number_format($producto->precio_compra / $tipoCambio, 2) }} USD</small>
                            </div>

                            <div class="mb-3">
                                <label class="font-weight-bold text-muted">Precio de Venta:</label>
                                <div class="h3 text-success mb-1">S/ {{ number_format($producto->precio_venta, 2) }}</div>
                                <small class="text-muted">≈ ${{ number_format($producto->precio_venta / $tipoCambio, 2) }} USD</small>
                            </div>

                            @if($producto->precio_compra > 0)
                            <div class="mb-3">
                                <label class="font-weight-bold text-muted">Margen de Ganancia:</label>
                                @php
                                    $margen = (($producto->precio_venta - $producto->precio_compra) / $producto->precio_compra) * 100;
                                @endphp
                                <div class="h5">
                                    <span class="badge badge-{{ $margen >= 20 ? 'success' : ($margen >= 0 ? 'warning' : 'danger') }} badge-lg">
                                        {{ number_format($margen, 1) }}%
                                        <i class="fas fa-{{ $margen >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                    </span>
                                </div>
                            </div>
                            @endif
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
                            <h6 class="text-muted">Categoría</h6>
                            <p class="h6">
                                @if($producto->categoria)
                                    <span class="badge badge-primary">{{ $producto->categoria->descripcion }}</span>
                                @else
                                    <span class="badge badge-secondary">Sin categoría</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted">Marca</h6>
                            <p class="h6">
                                @if($producto->marca)
                                    <span class="badge badge-info">{{ $producto->marca->descripcion }}</span>
                                @else
                                    <span class="badge badge-secondary">Sin marca</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted">Proveedor</h6>
                            <p class="h6">
                                @if($producto->proveedor)
                                    <span class="badge badge-warning">{{ $producto->proveedor->nombre ?? $producto->proveedor->razon_social ?? $producto->proveedor->descripcion }}</span>
                                @else
                                    <span class="badge badge-secondary">Sin proveedor</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @if($producto->observaciones)
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-sticky-note"></i> Observaciones</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">{{ $producto->observaciones }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Panel Lateral -->
        <div class="col-md-4">
            <!-- Estado de Stock -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cubes"></i> Estado de Inventario</h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <label class="font-weight-bold text-muted d-block">Stock Actual</label>
                        <div class="display-4 {{ $producto->stock_actual <= $producto->stock_minimo ? 'text-danger' : 'text-success' }}">
                            {{ $producto->stock_actual }}
                        </div>
                        @if($producto->stock_actual <= $producto->stock_minimo)
                            <small class="text-danger">
                                <i class="fas fa-exclamation-triangle"></i> Stock por debajo del mínimo
                            </small>
                        @else
                            <small class="text-success">
                                <i class="fas fa-check-circle"></i> Stock normal
                            </small>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <label class="font-weight-bold text-muted">Stock Mínimo:</label>
                        <div class="h4 text-warning">{{ $producto->stock_minimo }}</div>
                    </div>

                    @if($producto->stock_actual > 0)
                    <div class="progress mb-3">
                        @php
                            $porcentajeStock = ($producto->stock_actual / max($producto->stock_minimo * 2, $producto->stock_actual)) * 100;
                        @endphp
                        <div class="progress-bar bg-{{ $producto->stock_actual <= $producto->stock_minimo ? 'danger' : 'success' }}" 
                             style="width: {{ $porcentajeStock }}%"></div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Acciones -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-tools"></i> Acciones</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('productos.edit', $producto) }}" class="btn btn-warning btn-block">
                        <i class="fas fa-edit"></i> Editar Producto
                    </a>
                    <a href="{{ route('productos.index') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-arrow-left"></i> Volver al Listado
                    </a>
                    <a href="{{ route('productos.create') }}" class="btn btn-primary btn-block">
                        <i class="fas fa-plus"></i> Crear Nuevo Producto
                    </a>
                    <hr>
                    <form action="{{ route('productos.destroy', $producto) }}" method="POST" 
                          onsubmit="return confirm('¿Está seguro de eliminar este producto?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block">
                            <i class="fas fa-trash"></i> Eliminar Producto
                        </button>
                    </form>
                </div>
            </div>

            <!-- Información de Sistema -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-info"></i> Información del Sistema</h6>
                </div>
                <div class="card-body">
                    <small class="text-muted d-block">
                        <strong>Creado:</strong> {{ $producto->created_at ? $producto->created_at->format('d/m/Y H:i') : 'N/A' }}
                    </small>
                    @if($producto->updated_at && $producto->updated_at != $producto->created_at)
                    <small class="text-muted d-block">
                        <strong>Modificado:</strong> {{ $producto->updated_at->format('d/m/Y H:i') }}
                    </small>
                    @endif
                </div>
            </div>

            <!-- Valor del Inventario -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-calculator"></i> Valor del Inventario</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted">Valor Total (Precio Compra):</small>
                        <div class="h6 text-info">S/ {{ number_format($producto->precio_compra * $producto->stock_actual, 2) }}</div>
                        <small class="text-muted">${{ number_format(($producto->precio_compra * $producto->stock_actual) / $tipoCambio, 2) }} USD</small>
                    </div>
                    <div>
                        <small class="text-muted">Valor de Venta Estimado:</small>
                        <div class="h6 text-success">S/ {{ number_format($producto->precio_venta * $producto->stock_actual, 2) }}</div>
                        <small class="text-muted">${{ number_format(($producto->precio_venta * $producto->stock_actual) / $tipoCambio, 2) }} USD</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Nota sobre precios -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="alert alert-info">
                <small>
                    <i class="fas fa-info-circle"></i>
                    Los precios en dólares son calculados con el tipo de cambio actual: S/ {{ number_format($tipoCambio, 2) }} por USD.
                    Los valores pueden variar según el tipo de cambio del momento.
                </small>
            </div>
        </div>
    </div>
</div>
@endsection