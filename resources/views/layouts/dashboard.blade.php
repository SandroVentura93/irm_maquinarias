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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Animate.css para animaciones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-dark: #4338ca;
            --primary-light: #6366f1;
            --secondary-color: #0ea5e9;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-bg: #1e293b;
            --darker-bg: #0f172a;
            --sidebar-bg: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            --text-light: #f1f5f9;
            --text-muted: #94a3b8;
            --border-color: #334155;
            --hover-bg: #334155;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            display: flex;
            min-height: 100vh;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            background-attachment: fixed;
            overflow-x: hidden;
        }

        /* Navbar superior mejorada */
        .top-navbar {
            position: fixed;
            top: 0;
            left: 280px;
            right: 0;
            height: 70px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: var(--shadow-md);
            z-index: 1000;
            display: flex;
            align-items: center;
            padding: 0 30px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        }

        .top-navbar .logo-text {
            font-family: 'Poppins', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .top-navbar .user-info {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .top-navbar .welcome-text {
            font-size: 0.95rem;
            color: #64748b;
        }

        .top-navbar .user-name {
            font-weight: 600;
            color: var(--dark-bg);
            font-size: 1rem;
        }

        .top-navbar .btn-logout {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
        }

        .top-navbar .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
        }

        /* Sidebar moderna y elegante */
        .sidebar {
            width: 280px;
            background: var(--sidebar-bg);
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            padding: 0;
            overflow-y: auto;
            box-shadow: var(--shadow-xl);
            z-index: 1001;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: var(--darker-bg);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--hover-bg);
        }

        .sidebar-header {
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid var(--border-color);
            background: rgba(79, 70, 229, 0.1);
        }

        .sidebar-header .brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 8px;
        }

        .sidebar-header .brand-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            box-shadow: var(--shadow-md);
        }

        .sidebar-header h4 {
            font-family: 'Poppins', sans-serif;
            color: var(--text-light);
            font-size: 1.4rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .sidebar-header .brand-tagline {
            color: var(--text-muted);
            font-size: 0.75rem;
            font-weight: 400;
            margin-top: 4px;
        }

        .sidebar-nav {
            padding: 20px 15px;
        }

        .nav-section-title {
            color: var(--text-muted);
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 15px 15px 10px;
            margin-top: 10px;
        }

        .nav-item {
            margin: 4px 0;
        }

        .nav-link {
            color: var(--text-muted);
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 12px 15px;
            border-radius: 10px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
            font-size: 0.95rem;
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .nav-link:hover {
            background: var(--hover-bg);
            color: var(--text-light);
            transform: translateX(5px);
            box-shadow: var(--shadow-sm);
        }

        .nav-link:hover::before {
            transform: scaleY(1);
        }

        .nav-link.active {
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.2), rgba(14, 165, 233, 0.2));
            color: var(--text-light);
            box-shadow: var(--shadow-md);
            border-left: 3px solid var(--primary-color);
        }

        .nav-link i {
            margin-right: 12px;
            width: 20px;
            font-size: 1.1rem;
            text-align: center;
        }

        /* Menús colapsables mejorados */
        .menu-item {
            margin: 8px 0;
        }

        .menu-item h5 {
            cursor: pointer;
            margin: 0;
            padding: 12px 15px;
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-light);
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid transparent;
        }

        .menu-item h5:hover {
            background: var(--hover-bg);
            border-color: var(--border-color);
            transform: translateX(3px);
        }

        .menu-item h5::after {
            content: '\f107';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            transition: transform 0.3s ease;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .menu-item.open h5::after {
            transform: rotate(180deg);
        }

        .submenu {
            display: none;
            margin-top: 5px;
            margin-left: 10px;
            padding-left: 10px;
            border-left: 2px solid var(--border-color);
        }

        .submenu a {
            color: var(--text-muted);
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 10px 15px;
            margin: 2px 0;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            font-weight: 400;
        }

        .submenu a:hover {
            background: rgba(255, 255, 255, 0.08);
            color: var(--text-light);
            transform: translateX(5px);
        }

        .submenu a i {
            margin-right: 10px;
            width: 18px;
            font-size: 0.95rem;
            text-align: center;
        }

        /* Área de contenido */
        .content {
            margin-left: 280px;
            margin-top: 70px;
            padding: 30px;
            flex: 1;
            min-height: calc(100vh - 70px);
        }

        .content-wrapper {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: var(--shadow-xl);
            min-height: calc(100vh - 130px);
        }

        /* Animaciones */
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

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .animate-in {
            animation: fadeIn 0.5s ease-out;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .content {
                margin-left: 0;
            }

            .top-navbar {
                left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar Superior -->
    <div class="top-navbar animate-in">
        <div class="logo-text">
            <i class="fas fa-cogs"></i> IRM Maquinarias
        </div>
        <div class="user-info">
            <div>
                <div class="welcome-text">Bienvenido</div>
                <div class="user-name">{{ Auth::check() ? (Auth::user()->nombre ?: 'Usuario') : 'Invitado' }}</div>
            </div>
            @if(Auth::check())
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </button>
            </form>
            @else
            <a href="{{ route('login') }}" class="btn-logout" style="text-decoration: none;">
                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
            </a>
            @endif
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar animate-in">
        <div class="sidebar-header">
            <div class="brand">
                <div class="brand-icon">
                    <i class="fas fa-industry"></i>
                </div>
            </div>
            <h4>IRM Maquinarias</h4>
            <div class="brand-tagline">Sistema de Gestión</div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-title">MENÚ PRINCIPAL</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fas fa-chart-pie"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('compras.*') ? 'active' : '' }}" href="{{ route('compras.index') }}">
                        <i class="fas fa-shopping-bag"></i> Compras
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('ventas.*') ? 'active' : '' }}" href="{{ route('ventas.index') }}">
                        <i class="fas fa-cash-register"></i> Ventas
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

            <div class="nav-section-title">GESTIÓN</div>
            <div class="menu-item">
                <h5><i class="fas fa-box-open"></i> Gestión de Productos</h5>
                <div class="submenu">
                    @if(Auth::check() && in_array(Auth::user()->id_rol, [1,2,3,4]))
                    <a href="{{ route('productos.index') }}">
                        <i class="fas fa-boxes"></i> Productos
                    </a>
                    <a href="{{ route('categorias.index') }}">
                        <i class="fas fa-layer-group"></i> Categorías
                    </a>
                    <a href="{{ route('marcas.index') }}">
                        <i class="fas fa-tags"></i> Marcas
                    </a>
                    @endif
                    @if(Auth::check() && in_array(Auth::user()->id_rol, [1,2,4]))
                    <a href="{{ route('proveedores.index') }}">
                        <i class="fas fa-truck-loading"></i> Proveedores
                    </a>
                    @endif
                </div>
            </div>

            <div class="menu-item">
                <h5><i class="fas fa-shopping-cart"></i> Gestión de Ventas</h5>
                <div class="submenu">
                    @if(Auth::check() && in_array(Auth::user()->id_rol, [1,2,3,5]))
                    <a href="{{ route('ventas.index') }}">
                        <i class="fas fa-list-ul"></i> Ver Todas las Ventas
                    </a>
                    @endif
                    @if(Auth::check() && in_array(Auth::user()->id_rol, [1,2,3]))
                    <a href="{{ route('ventas.create') }}">
                        <i class="fas fa-plus-circle"></i> Nueva Venta
                    </a>
                    <a href="{{ route('clientes.index') }}">
                        <i class="fas fa-user-friends"></i> Gestionar Clientes
                    </a>
                    @endif
                </div>
            </div>

            @if(Auth::check() && in_array(Auth::user()->id_rol, [1,2,4]))
            <div class="menu-item">
                <h5><i class="fas fa-shopping-bag"></i> Gestión de Compras</h5>
                <div class="submenu">
                    <a href="{{ route('compras.index') }}">
                        <i class="fas fa-clipboard-list"></i> Ver Todas las Compras
                    </a>
                    <a href="{{ route('compras.create') }}">
                        <i class="fas fa-plus-circle"></i> Nueva Compra
                    </a>
                </div>
            </div>
            @endif

            @if(Auth::check() && in_array(Auth::user()->id_rol, [1,2]))
            <div class="nav-section-title">ADMINISTRACIÓN</div>
            <div class="menu-item">
                <h5><i class="fas fa-cog"></i> Configuración</h5>
                <div class="submenu">
                    <a href="{{ route('monedas.index') }}">
                        <i class="fas fa-dollar-sign"></i> Monedas
                    </a>
                    @if(Auth::check() && Auth::user()->id_rol == 1)
                    <a href="{{ route('usuarios.index') }}">
                        <i class="fas fa-users-cog"></i> Usuarios
                    </a>
                    @endif
                </div>
            </div>
            @endif

            @if(Auth::check() && in_array(Auth::user()->id_rol, [1,2,5]))
            <div class="nav-section-title">REPORTES</div>
            <div class="menu-item">
                <h5><i class="fas fa-chart-line"></i> Reportes</h5>
                <div class="submenu">
                    <a href="{{ route('reportes.diario') }}">
                        <i class="fas fa-calendar-day"></i> Reporte Diario
                    </a>
                    <a href="{{ route('semanal') }}">
                        <i class="fas fa-calendar-week"></i> Reporte Semanal
                    </a>
                    <a href="{{ route('mensual') }}">
                        <i class="fas fa-calendar-alt"></i> Reporte Mensual
                    </a>
                    <a href="{{ route('trimestral') }}">
                        <i class="fas fa-calendar"></i> Reporte Trimestral
                    </a>
                    <a href="{{ route('reportes.semestral') }}">
                        <i class="fas fa-calendar-check"></i> Reporte Semestral
                    </a>
                    <a href="{{ route('reportes.anual') }}">
                        <i class="fas fa-chart-bar"></i> Reporte Anual
                    </a>
                </div>
            </div>
            @endif
        </nav>
    </div>

    <!-- Contenido Principal -->
    <div class="content animate-in">
        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>

<!-- Scripts modernos -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Sección personalizada para estilos adicionales -->
@yield('styles')

<!-- Script personalizado del layout -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Menú colapsible mejorado con animaciones suaves
        const menuHeaders = document.querySelectorAll('.menu-item h5');
        
        menuHeaders.forEach(header => {
            header.addEventListener('click', function () {
                const menuItem = this.parentElement;
                const submenu = this.nextElementSibling;
                const isOpen = submenu.style.display === 'block';
                
                // Cerrar todos los otros menús
                document.querySelectorAll('.menu-item').forEach(item => {
                    if (item !== menuItem) {
                        item.classList.remove('open');
                        item.querySelector('.submenu').style.display = 'none';
                    }
                });
                
                // Toggle del menú actual
                if (isOpen) {
                    menuItem.classList.remove('open');
                    submenu.style.display = 'none';
                } else {
                    menuItem.classList.add('open');
                    submenu.style.display = 'block';
                    submenu.style.animation = 'slideDown 0.3s ease-out';
                }
            });
        });

        // Efecto de hover mejorado en enlaces
        const navLinks = document.querySelectorAll('.nav-link, .submenu a');
        navLinks.forEach(link => {
            link.addEventListener('mouseenter', function() {
                this.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
            });
        });

        // Indicador de carga global mejorado
        window.showGlobalLoading = function(message = 'Cargando...') {
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
                        background: rgba(15, 23, 42, 0.95);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        z-index: 10000;
                        backdrop-filter: blur(8px);
                        animation: fadeIn 0.3s ease-out;
                    ">
                        <div style="
                            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
                            padding: 3rem 4rem;
                            border-radius: 20px;
                            text-align: center;
                            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
                            transform: scale(1);
                            animation: scaleIn 0.3s ease-out;
                        ">
                            <div style="
                                width: 50px;
                                height: 50px;
                                border: 4px solid #e2e8f0;
                                border-top-color: #4f46e5;
                                border-radius: 50%;
                                animation: spin 1s linear infinite;
                                margin: 0 auto 1.5rem;
                            "></div>
                            <p style="
                                margin: 0;
                                font-weight: 600;
                                font-size: 1.1rem;
                                color: #1e293b;
                                font-family: 'Inter', sans-serif;
                            ">${message}</p>
                        </div>
                    </div>
                `;
                
                // Agregar animaciones CSS
                const style = document.createElement('style');
                style.textContent = `
                    @keyframes spin {
                        to { transform: rotate(360deg); }
                    }
                    @keyframes scaleIn {
                        from {
                            opacity: 0;
                            transform: scale(0.9);
                        }
                        to {
                            opacity: 1;
                            transform: scale(1);
                        }
                    }
                `;
                document.head.appendChild(style);
                document.body.appendChild(loading);
            }
        };

        window.hideGlobalLoading = function() {
            const loading = document.getElementById('globalLoading');
            if (loading) {
                loading.style.animation = 'fadeOut 0.3s ease-out';
                setTimeout(() => loading.remove(), 300);
            }
        };

        // Notificaciones toast mejoradas
        window.showToast = function(message, type = 'info') {
            const colors = {
                success: '#10b981',
                error: '#ef4444',
                warning: '#f59e0b',
                info: '#0ea5e9'
            };

            const icons = {
                success: 'fa-check-circle',
                error: 'fa-times-circle',
                warning: 'fa-exclamation-triangle',
                info: 'fa-info-circle'
            };

            const toast = document.createElement('div');
            toast.style.cssText = `
                position: fixed;
                top: 90px;
                right: 30px;
                background: white;
                color: #1e293b;
                padding: 1rem 1.5rem;
                border-radius: 12px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.2);
                z-index: 10001;
                display: flex;
                align-items: center;
                gap: 12px;
                min-width: 300px;
                border-left: 4px solid ${colors[type]};
                animation: slideInRight 0.3s ease-out;
                font-family: 'Inter', sans-serif;
            `;

            toast.innerHTML = `
                <i class="fas ${icons[type]}" style="color: ${colors[type]}; font-size: 1.5rem;"></i>
                <span style="flex: 1; font-weight: 500;">${message}</span>
                <button onclick="this.parentElement.remove()" style="
                    background: none;
                    border: none;
                    color: #64748b;
                    cursor: pointer;
                    font-size: 1.2rem;
                    padding: 0;
                    width: 24px;
                    height: 24px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: color 0.2s;
                " onmouseover="this.style.color='#1e293b'" onmouseout="this.style.color='#64748b'">
                    <i class="fas fa-times"></i>
                </button>
            `;

            // Agregar animación
            const style = document.createElement('style');
            style.textContent = `
                @keyframes slideInRight {
                    from {
                        opacity: 0;
                        transform: translateX(100%);
                    }
                    to {
                        opacity: 1;
                        transform: translateX(0);
                    }
                }
            `;
            document.head.appendChild(style);

            document.body.appendChild(toast);
            setTimeout(() => {
                toast.style.animation = 'slideOutRight 0.3s ease-out';
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        };

        // Efectos de scroll suave
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Auto-cierre de alertas
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                setTimeout(() => bsAlert.close(), 5000);
            });
        }, 100);
    });
</script>

<!-- Sección personalizada para scripts adicionales -->
@yield('scripts')
</body>
</html>