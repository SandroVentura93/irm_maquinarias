@extends('layouts.dashboard')

@section('content')
<div class="container">
    <h1>Listado de Ventas</h1>
    <a href="{{ route('ventas.create') }}" class="btn btn-primary mb-3">
        <i class="fas fa-plus"></i> Nueva Venta
    </a>

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

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Tipo Comprobante</th>
                <th>Número</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ventas as $venta)
                <tr>
                    <td>{{ $venta->id_venta }}</td>
                    <td>{{ $venta->cliente->razon_social ?: $venta->cliente->nombre }}</td>
                    <td>{{ $venta->tipoComprobante->descripcion ?? 'N/A' }}</td>
                    <td>{{ $venta->serie }}-{{ $venta->numero }}</td>
                    <td>{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i') }}</td>
                    <td>S/ {{ number_format($venta->total, 2) }}</td>
                    <td>
                        <span class="badge badge-{{ $venta->xml_estado === 'ANULADO' ? 'danger' : ($venta->xml_estado === 'ACEPTADO' ? 'success' : ($venta->xml_estado === 'RECHAZADO' ? 'warning' : 'info')) }}">
                            {{ $venta->xml_estado }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('ventas.show', $venta) }}" class="btn btn-info btn-sm" title="Ver Detalle">
                            <i class="fas fa-eye"></i> Ver
                        </a>
                        @if($venta->xml_estado !== 'ANULADO')
                        <a href="{{ route('ventas.pdf', $venta) }}" class="btn btn-success btn-sm" title="Imprimir Comprobante" target="_blank">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                        @endif
                        
                        {{-- Botón Convertir Cotización - Solo para comprobantes tipo 4 (Cotización) --}}
                        @if($venta->id_tipo_comprobante == 4 && $venta->xml_estado === 'PENDIENTE')
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false" title="Convertir Cotización">
                                <i class="fas fa-exchange-alt"></i> Convertir Cotización
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('ventas.convertir-factura', $venta) }}" title="Convertir a Factura">
                                    <i class="fas fa-file-invoice text-success"></i> Convertir a Factura
                                </a>
                                <a class="dropdown-item" href="{{ route('ventas.convertir-boleta', $venta) }}" title="Convertir a Boleta">
                                    <i class="fas fa-receipt text-info"></i> Convertir a Boleta
                                </a>
                            </div>
                        </div>
                        @endif
                        
                        @if($venta->xml_estado === 'PENDIENTE')
                        <a href="{{ route('ventas.edit', $venta) }}" class="btn btn-warning btn-sm" title="Editar">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        @endif
                        @if(in_array($venta->xml_estado, ['PENDIENTE', 'ENVIADO', 'ACEPTADO']))
                            <a href="{{ route('ventas.confirm-cancel', $venta) }}" class="btn btn-danger btn-sm" title="Anular Venta">
                                <i class="fas fa-times"></i> Anular
                            </a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($ventas->isEmpty())
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i>
            No hay ventas registradas aún.
        </div>
    @endif
</div>

<style>
.btn-group .dropdown-menu {
    min-width: 180px;
    border-radius: 0.375rem;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.btn-group .dropdown-item {
    padding: 0.5rem 1rem;
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

.btn-group .btn-info {
    background-color: #36b9cc;
    border-color: #36b9cc;
}

.btn-group .btn-info:hover {
    background-color: #2c9faf;
    border-color: #2a96a5;
}
</style>

@endsection