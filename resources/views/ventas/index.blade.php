@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header con estadísticas -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="mb-0"><i class="fas fa-shopping-cart"></i> Gestión de Ventas</h1>
                <div class="d-flex align-items-center">
                    <div class="alert alert-info mb-0 mr-3">
                        <small><i class="fas fa-exchange-alt"></i> TC: S/ {{ number_format($tipoCambio, 2) }}/USD</small>
                    </div>
                    <a href="{{ route('ventas.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nueva Venta
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <h3 class="text-primary">{{ $estadisticas['total_ventas'] }}</h3>
                    <small class="text-muted">Total Ventas</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-success">
                <div class="card-body text-center">
                    <h3 class="text-success">{{ $estadisticas['ventas_hoy'] }}</h3>
                    <small class="text-muted">Ventas Hoy</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <h3 class="text-warning">{{ $estadisticas['ventas_pendientes'] }}</h3>
                    <small class="text-muted">Pendientes</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body text-center">
                    <h3 class="text-info">S/ {{ number_format($estadisticas['total_facturado'], 2) }}</h3>
                    <small class="text-muted">Total Facturado</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body text-center">
                    <h3 class="text-success">S/ {{ number_format($estadisticas['total_facturado_hoy'], 2) }}</h3>
                    <small class="text-muted">Facturado Hoy</small>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Filtros y búsqueda -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-filter"></i> Filtros y Búsqueda</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('ventas.index') }}" id="filterForm">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="search">Buscar:</label>
                            <input type="text" name="search" id="search" class="form-control" 
                                   placeholder="Cliente, número, serie..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tipo_comprobante">Tipo:</label>
                            <select name="tipo_comprobante" id="tipo_comprobante" class="form-control">
                                <option value="">Todos</option>
                                @foreach($tiposComprobante as $tipo)
                                    <option value="{{ $tipo->id_tipo_comprobante }}" {{ request('tipo_comprobante') == $tipo->id_tipo_comprobante ? 'selected' : '' }}>
                                        {{ $tipo->descripcion }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="estado">Estado:</label>
                            <select name="estado" id="estado" class="form-control">
                                <option value="">Todos</option>
                                @foreach($estados as $estado)
                                    <option value="{{ $estado }}" {{ request('estado') == $estado ? 'selected' : '' }}>
                                        {{ $estado }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_desde">Desde:</label>
                            <input type="date" name="fecha_desde" id="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_hasta">Hasta:</label>
                            <input type="date" name="fecha_hasta" id="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="d-flex">
                                <button type="submit" class="btn btn-primary mr-1">
                                    <i class="fas fa-search"></i>
                                </button>
                                <a href="{{ route('ventas.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Filtros adicionales -->
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="monto_desde">Monto desde:</label>
                            <input type="number" step="0.01" name="monto_desde" id="monto_desde" class="form-control" 
                                   placeholder="0.00" value="{{ request('monto_desde') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="monto_hasta">Monto hasta:</label>
                            <input type="number" step="0.01" name="monto_hasta" id="monto_hasta" class="form-control" 
                                   placeholder="0.00" value="{{ request('monto_hasta') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="sort_by">Ordenar por:</label>
                            <select name="sort_by" id="sort_by" class="form-control">
                                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Fecha Creación</option>
                                <option value="fecha" {{ request('sort_by') == 'fecha' ? 'selected' : '' }}>Fecha Venta</option>
                                <option value="total" {{ request('sort_by') == 'total' ? 'selected' : '' }}>Monto</option>
                                <option value="numero" {{ request('sort_by') == 'numero' ? 'selected' : '' }}>Número</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="sort_order">Orden:</label>
                            <select name="sort_order" id="sort_order" class="form-control">
                                <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>DESC</option>
                                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>ASC</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de ventas -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Lista de Ventas</h5>
            <small class="text-muted">{{ $ventas->total() }} ventas encontradas</small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Tipo</th>
                            <th>Comprobante</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ventas as $venta)
                            <tr>
                                <td>
                                    <span class="badge badge-secondary">#{{ $venta->id_venta }}</span>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $venta->cliente->razon_social ?: $venta->cliente->nombre }}</strong>
                                        <br><small class="text-muted">{{ $venta->cliente->documento }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ $venta->tipoComprobante->descripcion ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <strong>{{ $venta->serie }}-{{ str_pad($venta->numero, 8, '0', STR_PAD_LEFT) }}</strong>
                                </td>
                                <td>
                                    <div>
                                        {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}
                                        <br><small class="text-muted">{{ \Carbon\Carbon::parse($venta->fecha)->format('H:i') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-success font-weight-bold">S/ {{ number_format($venta->total, 2) }}</div>
                                    <small class="text-muted">${{ number_format($venta->total / $tipoCambio, 2) }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $venta->xml_estado === 'ANULADO' ? 'danger' : ($venta->xml_estado === 'ACEPTADO' ? 'success' : ($venta->xml_estado === 'RECHAZADO' ? 'warning' : 'info')) }}">
                                        {{ $venta->xml_estado }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('ventas.show', $venta) }}" class="btn btn-info btn-sm" title="Ver Detalle">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($venta->xml_estado !== 'ANULADO')
                                            <a href="{{ route('ventas.pdf', $venta) }}" class="btn btn-success btn-sm" title="Imprimir PDF" target="_blank">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        @endif
                                        
                                        @if($venta->xml_estado === 'PENDIENTE')
                                            <a href="{{ route('ventas.edit', $venta) }}" class="btn btn-warning btn-sm" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        
                                        @if(in_array($venta->xml_estado, ['PENDIENTE', 'ENVIADO', 'ACEPTADO']))
                                            <a href="{{ route('ventas.confirm-cancel', $venta) }}" class="btn btn-danger btn-sm" title="Anular Venta">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        @endif
                                    </div>
                                    
                                    <!-- Botón Convertir Cotización -->
                                    @if($venta->id_tipo_comprobante == 4 && $venta->xml_estado === 'PENDIENTE')
                                        <div class="btn-group mt-1" role="group">
                                            <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-toggle="dropdown" title="Convertir Cotización">
                                                <i class="fas fa-exchange-alt"></i> Convertir
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{ route('ventas.convertir-factura', $venta) }}">
                                                    <i class="fas fa-file-invoice text-success"></i> A Factura
                                                </a>
                                                <a class="dropdown-item" href="{{ route('ventas.convertir-boleta', $venta) }}">
                                                    <i class="fas fa-receipt text-info"></i> A Boleta
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="alert alert-info mb-0">
                                        <i class="fas fa-info-circle"></i>
                                        No se encontraron ventas con los criterios especificados.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($ventas->hasPages())
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Mostrando {{ $ventas->firstItem() }} - {{ $ventas->lastItem() }} de {{ $ventas->total() }} ventas
                    </small>
                    {{ $ventas->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- Información adicional -->
    <div class="row mt-3">
        <div class="col-12">
            <small class="text-muted">
                <i class="fas fa-info-circle"></i>
                Los montos en dólares son calculados con el tipo de cambio actual: S/ {{ number_format($tipoCambio, 2) }} por USD.
                Los valores pueden variar según el tipo de cambio del momento.
            </small>
        </div>
    </div>
</div>

<style>
.btn-group .dropdown-menu {
    min-width: 150px;
    border-radius: 0.375rem;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.btn-group .dropdown-item {
    padding: 0.4rem 0.8rem;
    transition: all 0.15s ease-in-out;
}

.btn-group .dropdown-item:hover {
    background-color: #f8f9fc;
    color: #5a5c69;
}

.btn-group .dropdown-item i {
    width: 1.25rem;
    margin-right: 0.5rem;
}

.card .table th {
    border-top: none;
    background-color: #5a5c69;
    color: white;
    font-weight: 600;
    font-size: 0.875rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit en cambios de filtros
    document.querySelectorAll('#tipo_comprobante, #estado, #sort_by, #sort_order').forEach(function(element) {
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
    
    // Auto-submit en cambios de fechas
    document.querySelectorAll('#fecha_desde, #fecha_hasta').forEach(function(element) {
        element.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });
});
</script>
@endsection