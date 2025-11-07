@extends('layouts.dashboard')

@section('content')
<div class="container">
    <h1>Listado de Ventas</h1>
    <a href="{{ route('ventas.create') }}" class="btn btn-primary mb-3">Nueva Venta</a>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
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
                    <td>{{ $venta->id_tipo_comprobante }}</td>
                    <td>{{ $venta->serie }}-{{ $venta->numero }}</td>
                    <td>{{ $venta->fecha }}</td>
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

    <!-- Formulario de Registro de Ventas -->
    <div class="card mb-4">
        <div class="card-header">Registro de Ventas</div>
        <div class="card-body">
            <form action="{{ route('ventas.store') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="fecha" name="fecha" required>
                    </div>
                    <div class="col-md-4">
                        <label for="cliente" class="form-label">Cliente</label>
                        <select class="form-select" id="cliente" name="cliente_id" required>
                            <option value="">Seleccione un cliente</option>
                            <!-- Aquí se llenarán los clientes dinámicamente -->
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="vendedor" class="form-label">Vendedor</label>
                        <select class="form-select" id="vendedor" name="vendedor_id" required>
                            <option value="">Seleccione un vendedor</option>
                            <!-- Aquí se llenarán los vendedores dinámicamente -->
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="tipo_venta" class="form-label">Tipo de Venta</label>
                        <select class="form-select" id="tipo_venta" name="tipo_venta" required>
                            <option value="contado">Contado</option>
                            <option value="credito">Crédito</option>
                            <option value="online">Online</option>
                            <option value="presencial">Presencial</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="metodo_pago" class="form-label">Método de Pago</label>
                        <select class="form-select" id="metodo_pago" name="metodo_pago" required>
                            <option value="efectivo">Efectivo</option>
                            <option value="tarjeta">Tarjeta</option>
                            <option value="transferencia">Transferencia</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="subtotal" class="form-label">Subtotal</label>
                        <input type="number" class="form-control" id="subtotal" name="subtotal" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="impuestos" class="form-label">Impuestos</label>
                        <input type="number" class="form-control" id="impuestos" name="impuestos" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="total" class="form-label">Total</label>
                        <input type="number" class="form-control" id="total" name="total" readonly>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="descuento" class="form-label">Descuento</label>
                    <input type="number" class="form-control" id="descuento" name="descuento">
                </div>

                <button type="submit" class="btn btn-primary">Registrar Venta</button>
            </form>
        </div>
    </div>
</div>
@endsection