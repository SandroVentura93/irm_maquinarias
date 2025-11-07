@extends('layouts.dashboard')

@section('content')
<div class="container">
    <h1>Detalles de la Venta</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Venta #{{ $venta->id }}</h5>
            <p class="card-text"><strong>Cliente:</strong> {{ $venta->cliente->nombre }}</p>
            <p class="card-text"><strong>Vendedor:</strong> {{ $venta->vendedor->nombre }}</p>
            <p class="card-text"><strong>Tipo de Venta:</strong> {{ $venta->tipo_venta }}</p>
            <p class="card-text"><strong>Tipo de Comprobante:</strong> {{ $venta->tipo_comprobante }}</p>
            <p class="card-text"><strong>Número de Comprobante:</strong> {{ $venta->numero_comprobante }}</p>
            <p class="card-text"><strong>Fecha:</strong> {{ $venta->fecha }}</p>
            <p class="card-text"><strong>Subtotal:</strong> {{ $venta->subtotal }}</p>
            <p class="card-text"><strong>Descuento:</strong> {{ $venta->descuento }}</p>
            <p class="card-text"><strong>Impuesto:</strong> {{ $venta->impuesto }}</p>
            <p class="card-text"><strong>Total:</strong> {{ $venta->total }}</p>
            <p class="card-text"><strong>Método de Pago:</strong> {{ $venta->metodo_pago }}</p>
            <p class="card-text"><strong>Estado:</strong> {{ $venta->estado }}</p>
        </div>
    </div>

    <a href="{{ route('ventas.index') }}" class="btn btn-secondary mt-3">Volver</a>
</div>
@endsection