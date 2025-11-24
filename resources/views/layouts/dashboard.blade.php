<li class="nav-item">
            <a class="nav-link {{ request()->routeIs('reportes.*') ? 'active' : '' }}" href="{{ route('reportes.diario') }}">
                <i class="fas fa-chart-bar"></i> Reportes
            </a>
        </li>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>IRM Maquinarias - Dashboard</title>
    
    <!-- Bootstrap 5 y librerías modernas -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Fonts para tipografía moderna -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Animate.css para animaciones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        * {
            box-sizing: border-box;
        }
        
        body {
            display: flex;
            min-height: 100vh;
            margin: 0;
            padding-top: 70px;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            position: fixed;
            top: 0;
            bottom: 0;
            padding: 20px;
            overflow-y: auto;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        .sidebar h4 {
            color: #ecf0f1;
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #34495e;
        }
        .sidebar .nav-link {
            color: #bdc3c7;
            text-decoration: none;
            display: block;
            margin: 5px 0;
            padding: 10px 15px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover {
            background-color: #34495e;
            color: #ecf0f1;
            text-decoration: none;
            transform: translateX(5px);
        }
        .sidebar .nav-link.active {
            background-color: #3498db;
            color: white;
        }
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            flex: 1;
        }
        .menu-item {
            margin-bottom: 15px;
        }
        .menu-item h5 {
            cursor: pointer;
            margin: 0;
            padding: 12px 15px;
            background-color: #34495e;
            color: #ecf0f1;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .menu-item h5:hover {
            background-color: #3498db;
            transform: translateX(3px);
        }
        .submenu {
            display: none;
            margin-top: 5px;
            margin-left: 15px;
        }
        .submenu a {
            color: #bdc3c7;
            text-decoration: none;
            display: block;
            padding: 8px 15px;
            margin: 2px 0;
            border-radius: 3px;
            font-size: 13px;
            transition: all 0.3s ease;
        }
        .submenu a:hover {
            background-color: #34495e;
            color: #ecf0f1;
            text-decoration: none;
            transform: translateX(5px);
        }
        .submenu a i {
            margin-right: 8px;
            width: 16px;
        }
        }
        .menu-item .submenu {
            display: none;
            margin-top: 10px;
            padding-left: 15px;
        }
        .menu-item .submenu a {
            display: block;
            color: white;
            text-decoration: none;
            margin: 5px 0;
        }
        .menu-item .submenu a:hover {
            text-decoration: underline;
        }
        .navbar {
            z-index: 1030; /* Asegura que la barra de navegación esté siempre encima */
        }
        .navbar-text {
            text-transform: uppercase;
            font-weight: bold;
            margin: 0 auto; /* Centra el mensaje horizontalmente */
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">IRM Maquinarias</a>
            <div class="ml-auto d-flex align-items-center">
                <span class="navbar-text text-white text-uppercase font-weight-bold mr-3">Bienvenido:&nbsp;</span>
                <span class="navbar-text text-white mr-3">{{ Auth::check() ? (Auth::user()->nombre ?: 'Usuario') : 'Invitado' }}&nbsp;</span>
                @if(Auth::check())
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">Cerrar Sesión</button>
                </form>
                @else
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">Iniciar Sesión</a>
                @endif
            </div>
        </div>
    </nav>
<div class="sidebar">
    <h4>IRM Maquinarias</h4>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('compras.*') ? 'active' : '' }}" href="{{ route('compras.index') }}">
                <i class="fas fa-file-invoice-dollar"></i> Compras
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('ventas.*') ? 'active' : '' }}" href="{{ route('ventas.index') }}">
                <i class="fas fa-shopping-cart"></i> Ventas
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}" href="{{ route('clientes.index') }}">
                <i class="fas fa-users"></i> Clientes
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('productos.*') ? 'active' : '' }}" href="{{ route('productos.index') }}">
                <i class="fas fa-boxes"></i> Productos
            </a>
        </li>
    </ul>
    <div class="menu-item">
        <h5>Gestión de Productos</h5>
        <div class="submenu">
            <a href="{{ route('productos.index') }}">
                <i class="fas fa-boxes"></i> Productos
            </a>
            <a href="{{ route('categorias.index') }}">
                <i class="fas fa-list-alt"></i> Categorías
            </a>
            <a href="{{ route('marcas.index') }}">
                <i class="fas fa-tags"></i> Marcas
            </a>
            <a href="{{ route('proveedores.index') }}">
                <i class="fas fa-truck"></i> Proveedores
            </a>
        </div>
    </div>
    <div class="menu-item">
        <h5>Administración</h5>
        <div class="submenu">
            <a href="{{ route('monedas.index') }}">
                <i class="fas fa-dollar-sign"></i> Monedas
            </a>
            <a href="{{ route('usuarios.index') }}">
                <i class="fas fa-users-cog"></i> Usuarios
            </a>
        </div>
    </div>
    <div class="menu-item">
        <h5>Gestión de Ventas</h5>
        <div class="submenu">
            <a href="{{ route('ventas.index') }}">
                <i class="fas fa-list"></i> Ver Todas las Ventas
            </a>
            <a href="{{ route('ventas.create') }}">
                <i class="fas fa-plus"></i> Nueva Venta
            </a>
            <a href="{{ route('clientes.index') }}">
                <i class="fas fa-users"></i> Gestionar Clientes
            </a>
        </div>
    </div>
    <div class="menu-item">
        <h5>Reportes</h5>
        <div class="submenu">
            <a href="{{ route('reportes.diario') }}">
                <i class="fas fa-chart-line"></i> Reporte Diario
            </a>
            <a href="{{ route('semanal') }}">
                <i class="fas fa-chart-line"></i> Reporte Semanal
            </a>
            <a href="{{ route('mensual') }}">
                <i class="fas fa-chart-line"></i> Reporte Mensual
            </a>
            <a href="{{ route('trimestral') }}">
                <i class="fas fa-chart-line"></i> Reporte Trimestral
            </a>
            <a href="{{ route('reportes.semestral') }}">
                <i class="fas fa-chart-line"></i> Reporte Semestral
            </a>
            <a href="{{ route('reportes.anual') }}">
                <i class="fas fa-chart-line"></i> Reporte Anual
            </a>
        </div>
    </div>
</div>
<div class="content">
    @yield('content')
</div>

<!-- Scripts modernos -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Sección personalizada para estilos adicionales -->
@yield('styles')

<!-- Script personalizado del layout -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Menú colapsible mejorado
        const menuHeaders = document.querySelectorAll('.menu-item h5');
        menuHeaders.forEach(header => {
            header.addEventListener('click', function () {
                const submenu = this.nextElementSibling;
                const isOpen = submenu.style.display === 'block';
                
                // Cerrar todos los otros menús
                document.querySelectorAll('.submenu').forEach(menu => {
                    menu.style.display = 'none';
                });
                
                // Abrir/cerrar el menú actual
                submenu.style.display = isOpen ? 'none' : 'block';
                
                // Agregar clase de animación
                if (!isOpen) {
                    submenu.style.animation = 'slideDown 0.3s ease-out';
                }
            });
        });

        // Indicador de carga global
        window.showGlobalLoading = function() {
            if (!document.getElementById('globalLoading')) {
                const loading = document.createElement('div');
                loading.id = 'globalLoading';
                loading.innerHTML = `
                    <div style="
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(0,0,0,0.8);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        z-index: 10000;
                        backdrop-filter: blur(5px);
                    ">
                        <div style="
                            background: white;
                            padding: 2rem;
                            border-radius: 15px;
                            text-align: center;
                            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
                        ">
                            <div class="spinner-border text-primary mb-3"></div>
                            <p style="margin: 0; font-weight: 600;">Cargando...</p>
                        </div>
                    </div>
                `;
                document.body.appendChild(loading);
            }
        };

        window.hideGlobalLoading = function() {
            const loading = document.getElementById('globalLoading');
            if (loading) {
                loading.remove();
            }
        };
    });
</script>

<!-- Sección personalizada para scripts adicionales -->
@yield('scripts')
</body>
</html>