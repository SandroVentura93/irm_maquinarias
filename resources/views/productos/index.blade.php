@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header con estadísticas -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="mb-0"><i class="fas fa-boxes"></i> Gestión de Productos</h1>
                <div class="d-flex align-items-center">
                    <div class="alert alert-info mb-0 mr-3">
                        <small><i class="fas fa-exchange-alt"></i> TC: S/ {{ number_format($tipoCambio, 2) }}/USD</small>
                    </div>
                    <a href="{{ route('productos.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nuevo Producto
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <h3 class="text-primary">{{ $estadisticas['total_productos'] }}</h3>
                    <small class="text-muted">Total Productos</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <h3 class="text-warning">{{ $estadisticas['productos_bajo_stock'] }}</h3>
                    <small class="text-muted">Stock Bajo</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body text-center">
                    <h3 class="text-success">S/ {{ number_format($estadisticas['valor_total_inventario'], 2) }}</h3>
                    <small class="text-muted">Valor Total Inventario</small>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <!-- Filtros y búsqueda -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-filter"></i> Filtros y Búsqueda</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('productos.index') }}" id="filterForm">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="search">Buscar:</label>
                            <input type="text" name="search" id="search" class="form-control" 
                                   placeholder="Código o descripción..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="categoria_id">Categoría:</label>
                            <select name="categoria_id" id="categoria_id" class="form-control">
                                <option value="">Todas</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" {{ request('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->descripcion }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="marca_id">Marca:</label>
                            <select name="marca_id" id="marca_id" class="form-control">
                                <option value="">Todas</option>
                                @foreach($marcas as $marca)
                                    <option value="{{ $marca->id }}" {{ request('marca_id') == $marca->id ? 'selected' : '' }}>
                                        {{ $marca->descripcion }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="stock_status">Estado Stock:</label>
                            <select name="stock_status" id="stock_status" class="form-control">
                                <option value="">Todos</option>
                                <option value="bajo" {{ request('stock_status') == 'bajo' ? 'selected' : '' }}>Stock Bajo</option>
                                <option value="normal" {{ request('stock_status') == 'normal' ? 'selected' : '' }}>Stock Normal</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="sort_by">Ordenar por:</label>
                            <select name="sort_by" id="sort_by" class="form-control">
                                <option value="descripcion" {{ request('sort_by') == 'descripcion' ? 'selected' : '' }}>Descripción</option>
                                <option value="codigo" {{ request('sort_by') == 'codigo' ? 'selected' : '' }}>Código</option>
                                <option value="precio_venta" {{ request('sort_by') == 'precio_venta' ? 'selected' : '' }}>Precio</option>
                                <option value="stock_actual" {{ request('sort_by') == 'stock_actual' ? 'selected' : '' }}>Stock</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="d-flex">
                                <button type="submit" class="btn btn-primary mr-1">
                                    <i class="fas fa-search"></i>
                                </button>
                                <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de productos -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Lista de Productos</h5>
            <small class="text-muted">{{ $productos->total() }} productos encontrados</small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Código</th>
                            <th>Descripción</th>
                            <th>Categoría</th>
                            <th>Marca</th>
                            <th>Stock</th>
                            <th>Precio Compra</th>
                            <th>Precio Venta</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($productos as $producto)
                            <tr>
                                <td>
                                    <span class="badge badge-secondary">{{ $producto->codigo }}</span>
                                </td>
                                <td>
                                    <strong>{{ $producto->descripcion }}</strong>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $producto->categoria->descripcion ?? 'Sin categoría' }}</small>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $producto->marca->descripcion ?? 'Sin marca' }}</small>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="badge badge-{{ $producto->stock_actual <= $producto->stock_minimo ? 'danger' : 'success' }}">
                                            Actual: {{ $producto->stock_actual }}
                                        </span>
                                        <small class="text-muted">Mín: {{ $producto->stock_minimo }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-info font-weight-bold">S/ {{ number_format($producto->precio_compra, 2) }}</div>
                                    <small class="text-muted">${{ number_format($producto->precio_compra / $tipoCambio, 2) }}</small>
                                </td>
                                <td>
                                    <div class="text-success font-weight-bold">S/ {{ number_format($producto->precio_venta, 2) }}</div>
                                    <small class="text-muted">${{ number_format($producto->precio_venta / $tipoCambio, 2) }}</small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('productos.show', $producto) }}" class="btn btn-info btn-sm" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('productos.edit', $producto) }}" class="btn btn-warning btn-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('productos.destroy', $producto) }}" method="POST" style="display:inline;" 
                                              onsubmit="return confirm('¿Estás seguro de eliminar este producto?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="alert alert-info mb-0">
                                        <i class="fas fa-info-circle"></i>
                                        No se encontraron productos con los criterios especificados.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($productos->hasPages())
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Mostrando {{ $productos->firstItem() }} - {{ $productos->lastItem() }} de {{ $productos->total() }} productos
                    </small>
                    {{ $productos->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- Información adicional -->
    <div class="row mt-3">
        <div class="col-12">
            <small class="text-muted">
                <i class="fas fa-info-circle"></i>
                Los precios en dólares son calculados con el tipo de cambio actual: S/ {{ number_format($tipoCambio, 2) }} por USD.
                Los valores pueden variar según el tipo de cambio del momento.
            </small>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit en cambios de filtros
    document.querySelectorAll('#categoria_id, #marca_id, #stock_status, #sort_by').forEach(function(element) {
        element.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });
    
    // Búsqueda con delay
    let searchTimeout;
    document.getElementById('search').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            document.getElementById('filterForm').submit();
        }, 500);
    });
});
</script>
@endsection