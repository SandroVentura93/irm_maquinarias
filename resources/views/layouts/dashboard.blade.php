<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IRM Maquinarias - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            margin: 0;
            padding-top: 70px; /* Ajuste para que el contenido no quede tapado por la barra de navegación */
        }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            position: fixed;
            top: 0;
            bottom: 0;
            padding: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            margin: 10px 0;
        }
        .sidebar a:hover {
            text-decoration: underline;
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
            padding: 10px;
            background-color: #343a40;
            color: white;
            border-radius: 5px;
        }
        .menu-item h5:hover {
            background-color: #495057;
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
                <span class="navbar-text text-white text-uppercase font-weight-bold mr-3">Bienvenido</span>
                <span class="navbar-text text-white mr-3">{{ Auth::user()->name ?? 'Usuario' }}</span>
                <a href="{{ route('logout') }}" class="btn btn-outline-light btn-sm">Cerrar Sesión</a>
            </div>
        </div>
    </nav>
<div class="sidebar">
    <h4>IRM Maquinarias</h4>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('clientes.index') }}">Gestión de Clientes</a>
        </li>
    </ul>
    <div class="menu-item">
        <h5>Gestión de Productos</h5>
        <div class="submenu">
            <a href="{{ route('marcas.index') }}">Marcas</a>
            <a href="{{ route('proveedores.index') }}">Proveedores</a>
            <a href="{{ route('categorias.index') }}">Categorías</a>
            <a href="{{ route('productos.index') }}">Productos</a>
        </div>
    </div>
    <div class="menu-item">
        <h5>Gestión de Clientes</h5>
        <div class="submenu">
            <a href="{{ route('clientes.index') }}">Registro y Búsqueda</a>
            <a href="{{ route('clientes.index') }}">Historial de Compras</a>
            <a href="{{ route('clientes.index') }}">Clasificación por Tipo</a>
        </div>
    </div>
    <div class="menu-item">
        <h5>Gestión de Ventas</h5>
        <div class="submenu">
            <a href="#">Creación de Boletas o Facturas</a>
            <a href="#">Cálculo Automático de Precios</a>
            <a href="#">Selección de Método de Pago</a>
            <a href="#">Generación de Comprobantes</a>
        </div>
    </div>
    <div class="menu-item">
        <h5>Gestión de Usuarios y Permisos</h5>
        <div class="submenu">
            <a href="#">Roles</a>
            <a href="#">Registro de Actividades</a>
        </div>
    </div>
    <div class="menu-item">
        <h5>Reportes y Estadísticas</h5>
        <div class="submenu">
            <a href="#">Ventas Diarias, Semanales y Mensuales</a>
            <a href="#">Ranking de Productos</a>
            <a href="#">Análisis por Vendedor o Cliente</a>
            <a href="#">Exportación a Excel o PDF</a>
        </div>
    </div>
    <div class="menu-item">
        <h5>Inventario y Compras</h5>
        <div class="submenu">
            <a href="#">Control de Entradas y Salidas</a>
            <a href="#">Registro de Compras</a>
            <a href="#">Kardex Valorizado y No Valorizado</a>
        </div>
    </div>
    <a href="{{ route('monedas.index') }}">Monedas</a>
</div>
<div class="content">
    @yield('content')
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const menuHeaders = document.querySelectorAll('.menu-item h5');
        menuHeaders.forEach(header => {
            header.addEventListener('click', function () {
                const submenu = this.nextElementSibling;
                submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
            });
        });
    });
</script>
</body>
</html>