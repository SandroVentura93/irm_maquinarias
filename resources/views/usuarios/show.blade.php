@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header con Gradiente -->
    <div class="page-header-modern">
        <div class="header-content">
            <div class="header-left">
                <a href="{{ route('usuarios.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="header-info">
                    <h1 class="header-title">
                        <i class="fas fa-user me-3"></i>
                        Detalles del Usuario
                    </h1>
                    <p class="header-subtitle">Información completa del usuario</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Perfil del Usuario -->
        <div class="col-lg-4">
            <div class="card-modern">
                <div class="card-body-modern text-center">
                    <div class="profile-avatar">
                        <div class="avatar-large">
                            {{ strtoupper(substr($usuario->nombre, 0, 1)) }}
                        </div>
                        <div class="status-badge {{ $usuario->activo ? 'active' : 'inactive' }}">
                            <i class="fas fa-{{ $usuario->activo ? 'check' : 'times' }}-circle"></i>
                        </div>
                    </div>
                    
                    <h2 class="profile-name">{{ $usuario->nombre }}</h2>
                    <p class="profile-username">@<span>{{ $usuario->usuario }}</span></p>
                    
                    <div class="profile-role">
                        <i class="fas fa-shield-alt"></i>
                        {{ $usuario->rol->nombre }}
                    </div>

                    <div class="profile-status">
                        @if($usuario->activo)
                            <span class="badge-status active">
                                <i class="fas fa-check-circle"></i> Usuario Activo
                            </span>
                        @else
                            <span class="badge-status inactive">
                                <i class="fas fa-times-circle"></i> Usuario Inactivo
                            </span>
                        @endif
                    </div>

                    <div class="profile-actions">
                        <a href="{{ route('usuarios.edit', $usuario) }}" class="btn-action-primary">
                            <i class="fas fa-edit"></i>
                            Editar Usuario
                        </a>
                    </div>
                </div>
            </div>

            <!-- Estadísticas Rápidas -->
            <div class="card-modern mt-4">
                <div class="card-header-gradient">
                    <i class="fas fa-chart-line"></i>
                    <span>Estadísticas</span>
                </div>
                <div class="card-body-modern">
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-calendar-plus"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-label">Miembro desde</span>
                            <span class="stat-value">{{ $usuario->created_at ? $usuario->created_at->format('d/m/Y') : '-' }}</span>
                        </div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-label">Última actualización</span>
                            <span class="stat-value">{{ $usuario->updated_at ? $usuario->updated_at->format('d/m/Y') : '-' }}</span>
                        </div>
                    </div>

                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-user-clock"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-label">Tiempo en sistema</span>
                            <span class="stat-value">{{ $usuario->created_at ? $usuario->created_at->diffForHumans() : '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información Detallada -->
        <div class="col-lg-8">
            <!-- Información de Contacto -->
            <div class="card-modern">
                <div class="card-header-gradient">
                    <i class="fas fa-address-card"></i>
                    <span>Información de Contacto</span>
                </div>
                <div class="card-body-modern">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Correo Electrónico</span>
                                <span class="info-value">{{ $usuario->correo ?? 'No registrado' }}</span>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Teléfono</span>
                                <span class="info-value">{{ $usuario->telefono ?? 'No registrado' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información de Seguridad -->
            <div class="card-modern mt-4">
                <div class="card-header-gradient">
                    <i class="fas fa-lock"></i>
                    <span>Información de Seguridad</span>
                </div>
                <div class="card-body-modern">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Nombre de Usuario</span>
                                <span class="info-value">{{ $usuario->usuario }}</span>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Rol Asignado</span>
                                <span class="info-value">
                                    <span class="role-badge">{{ $usuario->rol->nombre }}</span>
                                </span>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-toggle-{{ $usuario->activo ? 'on' : 'off' }}"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Estado de Acceso</span>
                                <span class="info-value">
                                    @if($usuario->activo)
                                        <span class="status-text active">
                                            <i class="fas fa-check-circle"></i> Puede acceder al sistema
                                        </span>
                                    @else
                                        <span class="status-text inactive">
                                            <i class="fas fa-ban"></i> Acceso bloqueado
                                        </span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del Sistema -->
            <div class="card-modern mt-4">
                <div class="card-header-gradient">
                    <i class="fas fa-database"></i>
                    <span>Información del Sistema</span>
                </div>
                <div class="card-body-modern">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-hashtag"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">ID de Usuario</span>
                                <span class="info-value">#{{ $usuario->id_usuario }}</span>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-calendar-plus"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Fecha de Creación</span>
                                <span class="info-value">{{ $usuario->created_at ? $usuario->created_at->format('d/m/Y H:i:s') : '-' }}</span>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Última Modificación</span>
                                <span class="info-value">{{ $usuario->updated_at ? $usuario->updated_at->format('d/m/Y H:i:s') : '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones -->
            <div class="actions-section mt-4">
                <a href="{{ route('usuarios.index') }}" class="btn-secondary-modern">
                    <i class="fas fa-arrow-left me-2"></i>
                    Volver al Listado
                </a>
                <a href="{{ route('usuarios.edit', $usuario) }}" class="btn-primary-modern">
                    <i class="fas fa-edit me-2"></i>
                    Editar Usuario
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    /* Tema Cyan para Usuarios */
    :root {
        --usuarios-color: #06b6d4;
        --usuarios-gradient: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);
    }

    /* Header Moderno */
    .page-header-modern {
        background: var(--usuarios-gradient);
        padding: 32px;
        border-radius: 20px;
        margin-bottom: 32px;
        box-shadow: 0 8px 32px rgba(6, 182, 212, 0.2);
        position: relative;
        overflow: hidden;
    }

    .page-header-modern::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    .header-content {
        position: relative;
        z-index: 1;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .btn-back {
        width: 48px;
        height: 48px;
        background: rgba(255,255,255,0.2);
        border: none;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-back:hover {
        background: rgba(255,255,255,0.3);
        transform: translateX(-5px);
        color: white;
    }

    .header-title {
        color: white;
        font-size: 32px;
        font-weight: 800;
        margin: 0;
        display: flex;
        align-items: center;
    }

    .header-subtitle {
        color: rgba(255,255,255,0.9);
        margin: 8px 0 0 0;
        font-size: 16px;
    }

    /* Card Moderno */
    .card-modern {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(6, 182, 212, 0.08);
        margin-bottom: 0;
        overflow: hidden;
        border: none;
    }

    .card-header-gradient {
        background: var(--usuarios-gradient);
        padding: 20px 24px;
        color: white;
        font-weight: 700;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .card-body-modern {
        padding: 32px;
    }

    /* Perfil del Usuario */
    .profile-avatar {
        position: relative;
        margin-bottom: 24px;
    }

    .avatar-large {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        background: var(--usuarios-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        color: white;
        font-size: 56px;
        font-weight: 700;
        box-shadow: 0 12px 40px rgba(6, 182, 212, 0.3);
        position: relative;
    }

    .status-badge {
        position: absolute;
        bottom: 10px;
        right: calc(50% - 70px);
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        font-size: 18px;
    }

    .status-badge.active {
        color: #10b981;
    }

    .status-badge.inactive {
        color: #64748b;
    }

    .profile-name {
        font-size: 28px;
        font-weight: 800;
        color: #1e293b;
        margin: 0 0 8px 0;
    }

    .profile-username {
        font-size: 16px;
        color: #64748b;
        margin: 0 0 20px 0;
    }

    .profile-username span {
        color: var(--usuarios-color);
        font-weight: 600;
    }

    .profile-role {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--usuarios-gradient);
        color: white;
        padding: 10px 24px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14px;
        margin-bottom: 20px;
    }

    .profile-status {
        margin-bottom: 24px;
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 20px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
    }

    .badge-status.active {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
        border: 2px solid rgba(16, 185, 129, 0.3);
    }

    .badge-status.inactive {
        background: rgba(100, 116, 139, 0.1);
        color: #64748b;
        border: 2px solid rgba(100, 116, 139, 0.3);
    }

    .profile-actions {
        padding-top: 24px;
        border-top: 2px solid #f1f5f9;
    }

    .btn-action-primary {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 32px;
        background: var(--usuarios-gradient);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 15px;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 16px rgba(6, 182, 212, 0.3);
    }

    .btn-action-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(6, 182, 212, 0.4);
        color: white;
    }

    /* Estadísticas */
    .stat-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .stat-item:last-child {
        border-bottom: none;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        background: var(--usuarios-gradient);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        flex-shrink: 0;
    }

    .stat-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .stat-label {
        font-size: 13px;
        color: #64748b;
        font-weight: 600;
    }

    .stat-value {
        font-size: 15px;
        color: #1e293b;
        font-weight: 700;
    }

    /* Grid de Información */
    .info-grid {
        display: grid;
        gap: 24px;
    }

    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 20px;
        background: #f8fafc;
        border-radius: 12px;
        border: 2px solid #e0f2fe;
        transition: all 0.3s ease;
    }

    .info-item:hover {
        background: #f0f9ff;
        border-color: var(--usuarios-color);
        transform: translateX(5px);
    }

    .info-icon {
        width: 48px;
        height: 48px;
        background: var(--usuarios-gradient);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        flex-shrink: 0;
    }

    .info-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .info-label {
        font-size: 13px;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        font-size: 16px;
        color: #1e293b;
        font-weight: 700;
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        background: var(--usuarios-gradient);
        color: white;
        padding: 6px 16px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 700;
    }

    .status-text {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
    }

    .status-text.active {
        color: #10b981;
    }

    .status-text.inactive {
        color: #64748b;
    }

    /* Acciones */
    .actions-section {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-primary-modern {
        padding: 16px 32px;
        background: var(--usuarios-gradient);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 16px rgba(6, 182, 212, 0.3);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    .btn-primary-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(6, 182, 212, 0.4);
        color: white;
    }

    .btn-secondary-modern {
        padding: 16px 32px;
        background: #f1f5f9;
        color: #64748b;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    .btn-secondary-modern:hover {
        background: #e2e8f0;
        color: #475569;
        transform: translateY(-2px);
    }

    /* Responsive */
    @media (max-width: 991px) {
        .col-lg-4 {
            margin-bottom: 24px;
        }

        .actions-section {
            flex-direction: column;
        }

        .btn-primary-modern,
        .btn-secondary-modern {
            width: 100%;
            justify-content: center;
        }
    }
</style>

@endsection