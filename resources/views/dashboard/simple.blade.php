<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - IRM Maquinarias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-tools"></i> IRM Maquinarias
            </a>
            <div class="ml-auto">
                @if(Auth::check())
                    <span class="navbar-text text-white me-3">
                        Bienvenido: {{ Auth::user()->nombre }}
                    </span>
                    <a href="{{ route('logout') }}" class="btn btn-outline-light btn-sm" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                @endif
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
                <p>Panel de Control - Sistema de Gestión IRM Maquinarias</p>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-users fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Clientes</h5>
                        <p class="card-text display-6">{{ $clientes }}</p>
                        <a href="{{ route('clientes.index') }}" class="btn btn-primary">Ver Clientes</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-boxes fa-3x text-success mb-3"></i>
                        <h5 class="card-title">Productos</h5>
                        <p class="card-text display-6">{{ $productos }}</p>
                        <a href="{{ route('productos.index') }}" class="btn btn-success">Ver Productos</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-dollar-sign fa-3x text-info mb-3"></i>
                        <h5 class="card-title">Monedas</h5>
                        <p class="card-text display-6">{{ $monedas }}</p>
                        <a href="{{ route('monedas.index') }}" class="btn btn-info">Ver Monedas</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                        <h5 class="card-title">Alertas Stock</h5>
                        <p class="card-text display-6">{{ $alertas }}</p>
                        <a href="{{ route('productos.index') }}" class="btn btn-warning">Ver Alertas</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-shopping-cart"></i> Acceso Rápido - Ventas</h5>
                    </div>
                    <div class="card-body">
                        <p>Gestiona las ventas y comprobantes electrónicos</p>
                        <div class="d-grid gap-2">
                            <a href="{{ route('ventas.index') }}" class="btn btn-primary">
                                <i class="fas fa-list"></i> Ver Ventas
                            </a>
                            <a href="{{ route('ventas.create') }}" class="btn btn-success">
                                <i class="fas fa-plus"></i> Nueva Venta
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-cog"></i> Gestión de Datos</h5>
                    </div>
                    <div class="card-body">
                        <p>Administra los datos maestros del sistema</p>
                        <div class="row">
                            <div class="col-6">
                                <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary btn-sm w-100 mb-2">
                                    <i class="fas fa-box"></i> Productos
                                </a>
                                <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary btn-sm w-100 mb-2">
                                    <i class="fas fa-users"></i> Clientes
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('marcas.index') }}" class="btn btn-outline-secondary btn-sm w-100 mb-2">
                                    <i class="fas fa-tags"></i> Marcas
                                </a>
                                <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary btn-sm w-100 mb-2">
                                    <i class="fas fa-list-alt"></i> Categorías
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>