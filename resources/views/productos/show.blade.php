@extends('layouts.dashboard')

@section('content')
<div class="container">
    <h1>Detalles del Producto</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $producto->descripcion }}</h5>
            <p class="card-text"><strong>Código:</strong> {{ $producto->codigo }}</p>
            <p class="card-text"><strong>Precio Venta:</strong> {{ $producto->precio_venta }}</p>
        </div>
    </div>

    <a href="{{ route('productos.index') }}" class="btn btn-secondary mt-3">Volver</a>
</div>
@endsection