@extends('layouts.dashboard')

@section('content')
<div class="container">
    <h1>Detalles del Cliente</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $cliente->nombre }}</h5>
            <p class="card-text"><strong>Tipo Documento:</strong> {{ $cliente->tipo_documento }}</p>
            <p class="card-text"><strong>Número Documento:</strong> {{ $cliente->numero_documento }}</p>
            <p class="card-text"><strong>Razón Social:</strong> {{ $cliente->razon_social }}</p>
            <p class="card-text"><strong>Dirección:</strong> {{ $cliente->direccion }}</p>
            <p class="card-text"><strong>Teléfono:</strong> {{ $cliente->telefono }}</p>
            <p class="card-text"><strong>Correo:</strong> {{ $cliente->correo }}</p>
            <p class="card-text"><strong>Activo:</strong> {{ $cliente->activo ? 'Sí' : 'No' }}</p>
            <p class="card-text"><strong>Ubigeo:</strong> {{ $cliente->id_ubigeo }}</p>
        </div>
    </div>
    <h2>Historial de Compras</h2>
    <ul>
        @forelse ($cliente->compras as $compra)
            <li>Compra #{{ $compra->id }} - {{ $compra->created_at->format('d/m/Y') }} - Total: {{ $compra->total }}</li>
        @empty
            <li>No hay compras registradas para este cliente.</li>
        @endforelse
    </ul>
    <a href="{{ route('clientes.index') }}" class="btn btn-primary mt-3">Volver</a>
</div>
@endsection