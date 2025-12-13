@extends('layouts.dashboard')

@section('content')
<style>
    /* Variables de Color - Tema Cyan/Azul para Usuarios */
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --usuarios-gradient: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);
        --card-shadow: 0 8px 32px rgba(6, 182, 212, 0.1);
        --card-shadow-hover: 0 16px 48px rgba(6, 182, 212, 0.2);
    }

    /* Layout Principal */
    .usuarios-container {
        padding-left: 0px;
        padding-right: 20px;
        min-height: 100vh;
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 50%, #f0f9ff 100%);
        animation: fadeIn 0.6s ease-in-out;
    }
    
    .content-wrapper {
        max-width: 100%;
        margin: 0;
        padding: 0;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Header Moderno con Gradiente */
    .page-header {
        background: var(--usuarios-gradient);
        padding: 48px 40px;
        margin: 0 0 40px 0;
        border-radius: 0 0 32px 32px;
        box-shadow: 0 8px 32px rgba(6, 182, 212, 0.2);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }

    .page-header-content {
        position: relative;
        z-index: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 1400px;
        margin: 0 auto;
    }

    .page-title-section h1 {
        color: white;
        font-size: 42px;
        font-weight: 800;
        margin: 0 0 8px 0;
        display: flex;
        align-items: center;
        gap: 16px;
        text-shadow: 0 2px 12px rgba(0,0,0,0.1);
    }

    .page-title-section h1 i {
        font-size: 38px;
    }

    .page-subtitle {
        color: rgba(255,255,255,0.95);
        font-size: 16px;
        margin: 0;
        font-weight: 500;
    }

    /* Estadísticas en el Header */
    .header-stats {
        display: flex;
        gap: 24px;
        margin-top: 24px;
    }

    .stat-card {
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(10px);
        padding: 16px 24px;
        border-radius: 16px;
        border: 1px solid rgba(255,255,255,0.2);
    }

    .stat-value {
        font-size: 32px;
        font-weight: 800;
        color: white;
        margin: 0;
        line-height: 1;
    }

    .stat-label {
        font-size: 13px;
        color: rgba(255,255,255,0.9);
        margin: 4px 0 0 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }

    /* Botón Crear */
    .btn-create {
        background: white;
        color: #06b6d4;
        padding: 14px 32px;
        border-radius: 16px;
        font-weight: 700;
        font-size: 15px;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        text-decoration: none;
    }

    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 32px rgba(0,0,0,0.25);
        color: #0891b2;
        background: #ffffff;
    }

    .btn-create i {
        font-size: 18px;
    }

    /* Controles de Vista */
    .view-controls {
        background: white;
        padding: 20px 32px;
        border-radius: 20px;
        box-shadow: 0 4px 16px rgba(6, 182, 212, 0.08);
        margin: 0 0 32px 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .view-toggle {
        display: flex;
        gap: 8px;
        background: #f0f9ff;
        padding: 6px;
        border-radius: 12px;
    }

    .view-toggle-btn {
        padding: 10px 20px;
        border: none;
        background: transparent;
        border-radius: 8px;
        color: #06b6d4;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .view-toggle-btn.active {
        background: var(--usuarios-gradient);
        color: white;
        box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3);
    }

    .view-toggle-btn:hover:not(.active) {
        background: rgba(6, 182, 212, 0.1);
    }

    .search-box {
        position: relative;
        width: 320px;
    }

    .search-box input {
        width: 100%;
        padding: 12px 20px 12px 48px;
        border: 2px solid #e0f2fe;
        border-radius: 12px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .search-box input:focus {
        outline: none;
        border-color: #06b6d4;
        box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.1);
    }

    .search-box i {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #06b6d4;
        font-size: 16px;
    }

    /* Vista Grid */
    .usuarios-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 28px;
        margin-bottom: 40px;
    }

    .usuario-card {
        background: white;
        border-radius: 24px;
        padding: 32px;
        box-shadow: var(--card-shadow);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .usuario-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: var(--usuarios-gradient);
        transform: scaleX(0);
        transition: transform 0.4s ease;
    }

    .usuario-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--card-shadow-hover);
    }

    .usuario-card:hover::before {
        transform: scaleX(1);
    }

    .usuario-header {
        display: flex;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 24px;
    }

    .usuario-avatar {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        background: var(--usuarios-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        color: white;
        font-weight: 700;
        flex-shrink: 0;
        box-shadow: 0 8px 24px rgba(6, 182, 212, 0.3);
    }

    .usuario-info {
        flex: 1;
        min-width: 0;
    }

    .usuario-nombre {
        font-size: 22px;
        font-weight: 800;
        color: #1e293b;
        margin: 0 0 8px 0;
        line-height: 1.2;
    }

    .usuario-username {
        font-size: 14px;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 12px;
    }

    .usuario-rol {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        color: white;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .usuario-details {
        display: grid;
        gap: 12px;
        margin-bottom: 24px;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        background: #f8fafc;
        border-radius: 12px;
        font-size: 14px;
    }

    .detail-item i {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--usuarios-gradient);
        color: white;
        border-radius: 8px;
        font-size: 14px;
        flex-shrink: 0;
    }

    .detail-item .label {
        font-weight: 600;
        color: #475569;
        min-width: 70px;
    }

    .detail-item .value {
        color: #1e293b;
        font-weight: 500;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .usuario-status {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 20px;
    }

    .usuario-status.activo {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .usuario-status.inactivo {
        background: linear-gradient(135deg, #94a3b8 0%, #64748b 100%);
        color: white;
    }

    .usuario-status i {
        font-size: 14px;
    }

    .usuario-actions {
        display: flex;
        gap: 10px;
        padding-top: 20px;
        border-top: 2px solid #f1f5f9;
    }

    .action-btn {
        flex: 1;
        padding: 12px;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
    }

    .action-btn.btn-view {
        background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        color: white;
    }

    .action-btn.btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(6, 182, 212, 0.3);
    }

    .action-btn.btn-edit {
        background: #f0f9ff;
        color: #06b6d4;
    }

    .action-btn.btn-edit:hover {
        background: #e0f2fe;
        transform: translateY(-2px);
    }

    .action-btn.btn-delete {
        background: #fef2f2;
        color: #dc2626;
    }

    .action-btn.btn-delete:hover {
        background: #fee2e2;
        transform: translateY(-2px);
    }

    /* Vista Lista */
    .usuarios-list {
        display: none;
        background: white;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: var(--card-shadow);
    }

    .usuarios-list.active {
        display: block;
    }

    .list-header {
        background: var(--usuarios-gradient);
        color: white;
        padding: 20px 32px;
        font-weight: 700;
        display: grid;
        grid-template-columns: 80px 1.5fr 1fr 1.5fr 1fr 1fr 200px;
        gap: 20px;
        align-items: center;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .list-item {
        display: grid;
        grid-template-columns: 80px 1.5fr 1fr 1.5fr 1fr 1fr 200px;
        gap: 20px;
        align-items: center;
        padding: 20px 32px;
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .list-item:hover {
        background: #f8fafc;
    }

    .list-avatar {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: var(--usuarios-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: white;
        font-weight: 700;
    }

    .list-nombre {
        font-weight: 700;
        color: #1e293b;
    }

    .list-username {
        color: #64748b;
        font-size: 13px;
    }

    .list-rol {
        display: inline-block;
        padding: 4px 12px;
        background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        color: white;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .list-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
    }

    .list-status.activo {
        background: #d1fae5;
        color: #059669;
    }

    .list-status.inactivo {
        background: #f1f5f9;
        color: #64748b;
    }

    .list-actions {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
    }

    .list-actions .action-btn {
        flex: none;
        width: 36px;
        height: 36px;
        padding: 0;
    }

    /* Alertas */
    .alert-modern {
        padding: 16px 24px;
        border-radius: 16px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 600;
        animation: slideDown 0.4s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-modern.alert-success {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
        border-left: 4px solid #10b981;
    }

    .alert-modern i {
        font-size: 20px;
    }

    /* Responsive */
    @media (max-width: 1400px) {
        .usuarios-grid {
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .usuarios-container {
            padding-left: 0;
        }

        .page-header-content {
            flex-direction: column;
            align-items: flex-start;
            gap: 24px;
        }

        .usuarios-grid {
            grid-template-columns: 1fr;
        }

        .view-controls {
            flex-direction: column;
            gap: 16px;
        }

        .search-box {
            width: 100%;
        }

        .list-header,
        .list-item {
            grid-template-columns: 1fr;
            gap: 12px;
        }
    }
</style>

<div class="usuarios-container">
    <div class="content-wrapper">
        <!-- Header -->
        <div class="page-header">
            <div class="page-header-content">
                <div class="page-title-section">
                    <h1>
                        <i class="fas fa-users"></i>
                        Usuarios
                    </h1>
                    <p class="page-subtitle">Gestiona los usuarios y sus permisos del sistema</p>
                    
                    <div class="header-stats">
                        <div class="stat-card">
                            <div class="stat-value">{{ $usuarios->where('activo', 1)->count() }}</div>
                            <div class="stat-label">Activos</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value">{{ $usuarios->count() }}</div>
                            <div class="stat-label">Total</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value">{{ $usuarios->unique('id_rol')->count() }}</div>
                            <div class="stat-label">Roles</div>
                        </div>
                    </div>
                </div>
                @if(auth()->check() && auth()->user()->id_rol === 1)
                <a href="{{ route('usuarios.create') }}" class="btn-create">
                    <i class="fas fa-user-plus"></i>
                    Nuevo Usuario
                </a>
                @endif
            </div>
        </div>

        <!-- Alertas -->
        @if (session('success'))
        <div class="alert-modern alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- Controles de Vista -->
        <div class="view-controls">
            <div class="view-toggle">
                <button class="view-toggle-btn active" onclick="switchView('grid')">
                    <i class="fas fa-th-large"></i>
                    Tarjetas
                </button>
                <button class="view-toggle-btn" onclick="switchView('list')">
                    <i class="fas fa-list"></i>
                    Lista
                </button>
            </div>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Buscar usuarios..." onkeyup="filterUsuarios()">
            </div>
        </div>

        <!-- Vista Grid -->
        <div class="usuarios-grid" id="gridView">
            @foreach ($usuarios as $usuario)
            <div class="usuario-card" data-nombre="{{ strtolower($usuario->nombre) }}" data-username="{{ strtolower($usuario->usuario) }}" data-correo="{{ strtolower($usuario->correo) }}">
                <div class="usuario-header">
                    <div class="usuario-avatar">
                        {{ strtoupper(substr($usuario->nombre, 0, 1)) }}
                    </div>
                    <div class="usuario-info">
                        <h3 class="usuario-nombre">{{ $usuario->nombre }}</h3>
                        <div class="usuario-username">
                            <i class="fas fa-user"></i>
                            {{ $usuario->usuario }}
                        </div>
                        <span class="usuario-rol">
                            <i class="fas fa-shield-alt"></i>
                            {{ $usuario->rol->nombre }}
                        </span>
                    </div>
                </div>

                <div class="usuario-details">
                    <div class="detail-item">
                        <i class="fas fa-envelope"></i>
                        <span class="label">Correo:</span>
                        <span class="value" title="{{ $usuario->correo }}">{{ $usuario->correo }}</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-phone"></i>
                        <span class="label">Teléfono:</span>
                        <span class="value">{{ $usuario->telefono ?? 'No especificado' }}</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-hashtag"></i>
                        <span class="label">ID:</span>
                        <span class="value">{{ $usuario->id_usuario }}</span>
                    </div>
                </div>

                <div class="usuario-status {{ $usuario->activo ? 'activo' : 'inactivo' }}">
                    <i class="fas fa-{{ $usuario->activo ? 'check-circle' : 'times-circle' }}"></i>
                    {{ $usuario->activo ? 'Usuario Activo' : 'Usuario Inactivo' }}
                </div>

                <div class="usuario-actions">
                    <a href="{{ route('usuarios.show', $usuario) }}" class="action-btn btn-view">
                        <i class="fas fa-eye"></i>
                        Ver
                    </a>
                    <a href="{{ route('usuarios.edit', $usuario) }}" class="action-btn btn-edit">
                        <i class="fas fa-edit"></i>
                        Editar
                    </a>
                    @if(auth()->check() && auth()->user()->id_rol === 1)
                    <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" style="flex: 1; margin: 0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn btn-delete" onclick="return confirm('¿Estás seguro de eliminar este usuario?')" style="width: 100%;">
                            <i class="fas fa-trash-alt"></i>
                            Eliminar
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Vista Lista -->
        <div class="usuarios-list" id="listView">
            <div class="list-header">
                <div>Avatar</div>
                <div>Nombre</div>
                <div>Usuario</div>
                <div>Correo</div>
                <div>Rol</div>
                <div>Estado</div>
                <div>Acciones</div>
            </div>
            @foreach ($usuarios as $usuario)
            <div class="list-item" data-nombre="{{ strtolower($usuario->nombre) }}" data-username="{{ strtolower($usuario->usuario) }}" data-correo="{{ strtolower($usuario->correo) }}">
                <div class="list-avatar">
                    {{ strtoupper(substr($usuario->nombre, 0, 1)) }}
                </div>
                <div>
                    <div class="list-nombre">{{ $usuario->nombre }}</div>
                    <div class="list-username">ID: {{ $usuario->id_usuario }}</div>
                </div>
                <div>{{ $usuario->usuario }}</div>
                <div>{{ $usuario->correo }}</div>
                <div>
                    <span class="list-rol">{{ $usuario->rol->nombre }}</span>
                </div>
                <div>
                    <span class="list-status {{ $usuario->activo ? 'activo' : 'inactivo' }}">
                        <i class="fas fa-circle" style="font-size: 8px;"></i>
                        {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>
                <div class="list-actions">
                    <a href="{{ route('usuarios.show', $usuario) }}" class="action-btn btn-view" title="Ver">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('usuarios.edit', $usuario) }}" class="action-btn btn-edit" title="Editar">
                        <i class="fas fa-edit"></i>
                    </a>
                    @if(auth()->check() && auth()->user()->id_rol === 1)
                    <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" style="margin: 0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn btn-delete" onclick="return confirm('¿Estás seguro?')" title="Eliminar">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    // Alternar entre vista grid y lista
    function switchView(view) {
        const gridView = document.getElementById('gridView');
        const listView = document.getElementById('listView');
        const buttons = document.querySelectorAll('.view-toggle-btn');
        
        buttons.forEach(btn => btn.classList.remove('active'));
        
        if (view === 'grid') {
            gridView.style.display = 'grid';
            listView.classList.remove('active');
            buttons[0].classList.add('active');
        } else {
            gridView.style.display = 'none';
            listView.classList.add('active');
            buttons[1].classList.add('active');
        }
    }

    // Filtrar usuarios
    function filterUsuarios() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const gridCards = document.querySelectorAll('.usuario-card');
        const listItems = document.querySelectorAll('.list-item');
        
        // Filtrar vista grid
        gridCards.forEach(card => {
            const nombre = card.dataset.nombre;
            const username = card.dataset.username;
            const correo = card.dataset.correo;
            
            if (nombre.includes(searchTerm) || username.includes(searchTerm) || correo.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
        
        // Filtrar vista lista
        listItems.forEach(item => {
            const nombre = item.dataset.nombre;
            const username = item.dataset.username;
            const correo = item.dataset.correo;
            
            if (nombre.includes(searchTerm) || username.includes(searchTerm) || correo.includes(searchTerm)) {
                item.style.display = 'grid';
            } else {
                item.style.display = 'none';
            }
        });
    }
</script>

@endsection
