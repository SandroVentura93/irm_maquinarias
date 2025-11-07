@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4">
                <i class="fas fa-tachometer-alt"></i> Dashboard - Panel de Control
            </h1>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-users fa-2x text-primary mb-2"></i>
                    <h5 class="card-title">Clientes</h5>
                    <p class="card-text display-6">{{ $clientes }}</p>
                    <a href="{{ route('clientes.index') }}" class="btn btn-primary btn-sm">Ver Clientes</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-boxes fa-2x text-success mb-2"></i>
                    <h5 class="card-title">Productos</h5>
                    <p class="card-text display-6">{{ $productos }}</p>
                    <a href="{{ route('productos.index') }}" class="btn btn-success btn-sm">Ver Productos</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-dollar-sign fa-2x text-info mb-2"></i>
                    <h5 class="card-title">Monedas</h5>
                    <p class="card-text display-6">{{ $monedas }}</p>
                    <a href="{{ route('monedas.index') }}" class="btn btn-info btn-sm">Ver Monedas</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-exclamation-triangle fa-2x text-warning mb-2"></i>
                    <h5 class="card-title">Alertas Stock</h5>
                    <p class="card-text display-6">{{ $alertas }}</p>
                    <a href="{{ route('productos.index') }}" class="btn btn-warning btn-sm">Ver Alertas</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de Ventas -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Sistema de Ventas</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Gestiona las ventas, comprobantes electrónicos y facturación del sistema.</p>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="d-grid">
                                <a href="{{ route('ventas.index') }}" class="btn btn-primary mb-2">
                                    <i class="fas fa-list"></i> Ver Todas las Ventas
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-grid">
                                <a href="{{ route('ventas.create') }}" class="btn btn-success mb-2">
                                    <i class="fas fa-plus"></i> Nueva Venta
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-grid">
                                <a href="{{ route('clientes.create') }}" class="btn btn-info mb-2">
                                    <i class="fas fa-user-plus"></i> Nuevo Cliente
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-cogs"></i> Gestión Rápida</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('productos.create') }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-box"></i> Nuevo Producto
                        </a>
                        <a href="{{ route('marcas.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-tags"></i> Gestionar Marcas
                        </a>
                        <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-list-alt"></i> Gestionar Categorías
                        </a>
                        <a href="{{ route('proveedores.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-truck"></i> Gestionar Proveedores
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection