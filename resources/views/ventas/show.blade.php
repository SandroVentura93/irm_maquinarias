@extends('layouts.dashboard')

@section('content')
<div class="container">
    <h1>Detalles de la Venta</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Venta #{{ $venta->id_venta }}</h5>
            <p class="card-text"><strong>Cliente:</strong> {{ $venta->cliente->razon_social ?: $venta->cliente->nombre }}</p>
            <p class="card-text"><strong>Vendedor:</strong> {{ $venta->vendedor->name ?: 'Sin vendedor asignado' }}</p>
            <p class="card-text"><strong>Serie:</strong> {{ $venta->serie }}</p>
            <p class="card-text"><strong>Número:</strong> {{ $venta->numero }}</p>
            <p class="card-text"><strong>Fecha:</strong> {{ $venta->fecha }}</p>
            <p class="card-text"><strong>Subtotal:</strong> S/ {{ number_format($venta->subtotal, 2) }}</p>
            <p class="card-text"><strong>IGV:</strong> S/ {{ number_format($venta->igv, 2) }}</p>
            <p class="card-text"><strong>Total:</strong> S/ {{ number_format($venta->total, 2) }}</p>
            <p class="card-text"><strong>Estado XML:</strong> 
                <span class="badge badge-{{ $venta->xml_estado === 'ANULADO' ? 'danger' : ($venta->xml_estado === 'ACEPTADO' ? 'success' : 'info') }}">
                    {{ $venta->xml_estado }}
                </span>
            </p>
            @if($venta->xml_estado === 'ANULADO')
            <div class="alert alert-danger mt-3">
                <h6><i class="fas fa-exclamation-triangle"></i> Venta Anulada</h6>
                <p class="mb-1"><strong>Fecha de Anulación:</strong> {{ \Carbon\Carbon::parse($venta->fecha_anulacion)->format('d/m/Y H:i:s') }}</p>
                <p class="mb-0"><strong>Motivo:</strong> {{ $venta->motivo_anulacion }}</p>
            </div>
            @endif
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('ventas.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        @if($venta->xml_estado !== 'ANULADO')
        <a href="{{ route('ventas.pdf', $venta) }}" class="btn btn-success" target="_blank">
            <i class="fas fa-file-pdf"></i> Imprimir Comprobante
        </a>
        @endif
        @if($venta->xml_estado === 'PENDIENTE')
        <a href="{{ route('ventas.edit', $venta) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Editar
        </a>
        @endif
        @if(in_array($venta->xml_estado, ['PENDIENTE', 'ENVIADO', 'ACEPTADO']))
        <a href="{{ route('ventas.confirm-cancel', $venta) }}" class="btn btn-danger">
            <i class="fas fa-times"></i> Anular Venta
        </a>
        @endif
    </div>
</div>
@endsection