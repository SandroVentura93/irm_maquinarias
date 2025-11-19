
@extends('layouts.dashboard')

@section('content')
<div class="container py-4">
    <a href="{{ route('compras.index') }}" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left me-2"></i> Volver al listado</a>
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <h4 class="mb-0">Detalle de Compra #{{ $compra->id_compra }}</h4>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4"><strong>Proveedor:</strong> {{ $compra->proveedor->razon_social ?? '-' }}</div>
                <div class="col-md-4"><strong>Moneda:</strong> {{ $compra->moneda->nombre ?? $compra->moneda->descripcion ?? '-' }}</div>
                <div class="col-md-4"><strong>Fecha:</strong> {{ $compra->fecha }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Subtotal:</strong> S/. {{ number_format($compra->subtotal, 2) }}</div>
                <div class="col-md-4"><strong>IGV:</strong> S/. {{ number_format($compra->igv, 2) }}</div>
                <div class="col-md-4"><strong>Total:</strong> <span class="h5 text-success">S/. {{ number_format($compra->total, 2) }}</span></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Estado:</strong> <span class="badge bg-info">{{ $compra->estado ?? '-' }}</span></div>
            </div>
            <h5 class="mt-4">Productos Comprados</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Subtotal</th>
                            <th>IGV</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($compra->detalles as $detalle)
                        <tr>
                            <td>{{ $detalle->producto->descripcion ?? $detalle->producto->nombre ?? '-' }}</td>
                            <td>{{ $detalle->cantidad }}</td>
                            <td>S/. {{ number_format($detalle->precio_unitario, 2) }}</td>
                            <td>S/. {{ number_format($detalle->subtotal, 2) }}</td>
                            <td>S/. {{ number_format($detalle->igv, 2) }}</td>
                            <td>S/. {{ number_format($detalle->total, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No hay productos registrados en esta compra.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
