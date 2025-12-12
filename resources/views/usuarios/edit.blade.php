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
                        <i class="fas fa-user-edit me-3"></i>
                        Editar Usuario
                    </h1>
                    <p class="header-subtitle">Actualiza la información del usuario</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas de Error -->
    @if ($errors->any())
        <div class="alert alert-danger alert-modern alert-dismissible fade show" role="alert">
            <div class="alert-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="alert-content">
                <h5 class="alert-title">¡Ups! Hay algunos errores</h5>
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Formulario Principal -->
        <div class="col-lg-8">
            <div class="card-modern">
                <div class="card-header-gradient">
                    <i class="fas fa-edit"></i>
                    <span>Información del Usuario</span>
                </div>
                <div class="card-body-modern">
                    <form action="{{ route('usuarios.update', $usuario) }}" method="POST" id="usuarioForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Información Personal -->
                        <div class="form-section">
                            <h6 class="section-title">
                                <i class="fas fa-user text-cyan"></i>
                                Información Personal
                            </h6>
                            
                            <div class="row g-4">
                                <div class="col-md-12">
                                    <div class="form-group-modern">
                                        <label for="nombre" class="form-label-modern">
                                            Nombre Completo <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-icon-wrapper">
                                            <i class="fas fa-id-card input-icon"></i>
                                            <input type="text" 
                                                   name="nombre" 
                                                   id="nombre" 
                                                   class="form-control-modern" 
                                                   value="{{ old('nombre', $usuario->nombre) }}"
                                                   placeholder="Ej: Juan Pérez García"
                                                   required>
                                        </div>
                                        <small class="form-help">Nombre completo del usuario</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="correo" class="form-label-modern">
                                            Correo Electrónico
                                        </label>
                                        <div class="input-icon-wrapper">
                                            <i class="fas fa-envelope input-icon"></i>
                                            <input type="email" 
                                                   name="correo" 
                                                   id="correo" 
                                                   class="form-control-modern" 
                                                   value="{{ old('correo', $usuario->correo) }}"
                                                   placeholder="usuario@ejemplo.com">
                                        </div>
                                        <small class="form-help">Correo para notificaciones</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="telefono" class="form-label-modern">
                                            Teléfono
                                        </label>
                                        <div class="input-icon-wrapper">
                                            <i class="fas fa-phone input-icon"></i>
                                            <input type="text" 
                                                   name="telefono" 
                                                   id="telefono" 
                                                   class="form-control-modern" 
                                                   value="{{ old('telefono', $usuario->telefono) }}"
                                                   placeholder="987654321">
                                        </div>
                                        <small class="form-help">Número de contacto</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Credenciales de Acceso -->
                        <div class="form-section">
                            <h6 class="section-title">
                                <i class="fas fa-key text-cyan"></i>
                                Credenciales de Acceso
                            </h6>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="usuario" class="form-label-modern">
                                            Nombre de Usuario <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-icon-wrapper">
                                            <i class="fas fa-user-circle input-icon"></i>
                                            <input type="text" 
                                                   name="usuario" 
                                                   id="usuario" 
                                                   class="form-control-modern" 
                                                   value="{{ old('usuario', $usuario->usuario) }}"
                                                   placeholder="usuario123"
                                                   required>
                                        </div>
                                        <small class="form-help">Usuario para iniciar sesión</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="id_rol" class="form-label-modern">
                                            Rol <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-icon-wrapper">
                                            <i class="fas fa-shield-alt input-icon"></i>
                                            <select name="id_rol" 
                                                    id="id_rol" 
                                                    class="form-control-modern" 
                                                    required>
                                                @foreach ($roles as $rol)
                                                    <option value="{{ $rol->id_rol }}" {{ old('id_rol', $usuario->id_rol) == $rol->id_rol ? 'selected' : '' }}>
                                                        {{ $rol->nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <small class="form-help">Permisos del usuario</small>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group-modern">
                                        <label for="contrasena" class="form-label-modern">
                                            Nueva Contraseña
                                        </label>
                                        <div class="input-icon-wrapper">
                                            <i class="fas fa-lock input-icon"></i>
                                            <input type="password" 
                                                   name="contrasena" 
                                                   id="contrasena" 
                                                   class="form-control-modern" 
                                                   placeholder="Dejar vacío para mantener la actual"
                                                   onkeyup="updatePasswordPreview()">
                                            <i class="fas fa-eye input-icon-right toggle-password" 
                                               onclick="togglePassword()"
                                               style="cursor: pointer;"></i>
                                        </div>
                                        <small class="form-help">Deja en blanco para mantener la contraseña actual</small>
                                        <div class="password-strength-bar mt-2">
                                            <div class="strength-indicator" id="strengthIndicator"></div>
                                        </div>
                                        <small id="strengthText" class="form-help"></small>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-check-modern">
                                        <input type="checkbox" 
                                               name="activo" 
                                               id="activo" 
                                               class="form-check-input-modern"
                                               value="1"
                                               {{ old('activo', $usuario->activo) ? 'checked' : '' }}>
                                        <label for="activo" class="form-check-label-modern">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            Usuario activo (puede acceder al sistema)
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información de Auditoría -->
                        @if($usuario->created_at)
                        <div class="form-section">
                            <h6 class="section-title">
                                <i class="fas fa-clock text-cyan"></i>
                                Información de Auditoría
                            </h6>
                            
                            <div class="audit-info">
                                <div class="audit-item">
                                    <i class="fas fa-calendar-plus"></i>
                                    <div>
                                        <span class="audit-label">Fecha de Creación:</span>
                                        <span class="audit-value">{{ $usuario->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>
                                @if($usuario->updated_at)
                                <div class="audit-item">
                                    <i class="fas fa-calendar-check"></i>
                                    <div>
                                        <span class="audit-label">Última Modificación:</span>
                                        <span class="audit-value">{{ $usuario->updated_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <div class="form-actions">
                            <button type="submit" class="btn-primary-modern">
                                <i class="fas fa-save me-2"></i>
                                Guardar Cambios
                            </button>
                            <a href="{{ route('usuarios.index') }}" class="btn-secondary-modern">
                                <i class="fas fa-times me-2"></i>
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Zona de Peligro -->
            <div class="card-modern card-danger">
                <div class="card-header-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Zona de Peligro</span>
                </div>
                <div class="card-body-modern">
                    <div class="danger-content">
                        <div class="danger-info">
                            <h6 class="danger-title">Eliminar Usuario</h6>
                            <p class="danger-text">
                                Esta acción es permanente y no se puede deshacer. 
                                Se eliminarán todos los datos asociados a este usuario.
                            </p>
                        </div>
                        <form action="{{ route('usuarios.destroy', $usuario) }}" 
                              method="POST" 
                              onsubmit="return confirm('¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger-modern">
                                <i class="fas fa-trash-alt me-2"></i>
                                Eliminar Usuario
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Lateral -->
        <div class="col-lg-4">
            <!-- Vista Previa -->
            <div class="card-modern">
                <div class="card-header-gradient">
                    <i class="fas fa-eye"></i>
                    <span>Vista Previa</span>
                </div>
                <div class="card-body-modern">
                    <div class="preview-section">
                        <div class="preview-avatar">
                            <div class="avatar-circle" id="previewAvatar">
                                {{ strtoupper(substr($usuario->nombre, 0, 1)) }}
                            </div>
                        </div>
                        <div class="preview-info">
                            <div class="preview-item">
                                <span class="preview-label">Nombre:</span>
                                <span class="preview-value" id="previewNombre">{{ $usuario->nombre }}</span>
                            </div>
                            <div class="preview-item">
                                <span class="preview-label">Usuario:</span>
                                <span class="preview-value" id="previewUsuario">{{ $usuario->usuario }}</span>
                            </div>
                            <div class="preview-item">
                                <span class="preview-label">Correo:</span>
                                <span class="preview-value" id="previewCorreo">{{ $usuario->correo ?? '-' }}</span>
                            </div>
                            <div class="preview-item">
                                <span class="preview-label">Teléfono:</span>
                                <span class="preview-value" id="previewTelefono">{{ $usuario->telefono ?? '-' }}</span>
                            </div>
                            <div class="preview-item">
                                <span class="preview-label">Rol:</span>
                                <span class="preview-badge" id="previewRol">{{ $usuario->rol->nombre }}</span>
                            </div>
                            <div class="preview-item">
                                <span class="preview-label">Estado:</span>
                                <span class="preview-status {{ $usuario->activo ? 'active' : 'inactive' }}" id="previewEstado">
                                    <i class="fas fa-{{ $usuario->activo ? 'check-circle' : 'times-circle' }}"></i> 
                                    {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel de Ayuda -->
            <div class="card-modern mt-4">
                <div class="card-header-gradient">
                    <i class="fas fa-info-circle"></i>
                    <span>Información</span>
                </div>
                <div class="card-body-modern">
                    <div class="help-content">
                        <div class="help-item">
                            <i class="fas fa-check text-success"></i>
                            <span>Los campos con (*) son obligatorios</span>
                        </div>
                        <div class="help-item">
                            <i class="fas fa-check text-success"></i>
                            <span>Deja la contraseña vacía para mantenerla</span>
                        </div>
                        <div class="help-item">
                            <i class="fas fa-check text-success"></i>
                            <span>El nombre de usuario debe ser único</span>
                        </div>
                        <div class="help-item">
                            <i class="fas fa-check text-success"></i>
                            <span>Cambiar el rol afecta los permisos</span>
                        </div>
                        <div class="help-item">
                            <i class="fas fa-exclamation-triangle text-warning"></i>
                            <span>Desactivar bloquea el acceso inmediatamente</span>
                        </div>
                    </div>
                </div>
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

    .text-cyan {
        color: var(--usuarios-color) !important;
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
        margin-bottom: 24px;
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

    /* Card de Peligro */
    .card-danger {
        border: 2px solid #fee2e2;
    }

    .card-header-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        padding: 20px 24px;
        color: white;
        font-weight: 700;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .danger-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 24px;
    }

    .danger-info {
        flex: 1;
    }

    .danger-title {
        font-size: 18px;
        font-weight: 700;
        color: #991b1b;
        margin: 0 0 8px 0;
    }

    .danger-text {
        color: #64748b;
        margin: 0;
        font-size: 14px;
        line-height: 1.6;
    }

    .btn-danger-modern {
        padding: 12px 24px;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .btn-danger-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(239, 68, 68, 0.4);
    }

    /* Secciones del Formulario */
    .form-section {
        margin-bottom: 40px;
    }

    .form-section:last-child {
        margin-bottom: 0;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 24px;
        padding-bottom: 12px;
        border-bottom: 2px solid #f0f9ff;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Form Group Moderno */
    .form-group-modern {
        margin-bottom: 0;
    }

    .form-label-modern {
        display: block;
        font-weight: 600;
        color: #475569;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .input-icon-wrapper {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--usuarios-color);
        font-size: 16px;
        z-index: 1;
    }

    .input-icon-right {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 16px;
        z-index: 1;
    }

    .form-control-modern {
        width: 100%;
        padding: 14px 16px 14px 48px;
        border: 2px solid #e0f2fe;
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s ease;
        background: #f8fafc;
    }

    .form-control-modern:focus {
        outline: none;
        border-color: var(--usuarios-color);
        box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.1);
        background: white;
    }

    .form-help {
        display: block;
        color: #94a3b8;
        font-size: 13px;
        margin-top: 6px;
    }

    /* Password Strength */
    .password-strength-bar {
        height: 4px;
        background: #e2e8f0;
        border-radius: 2px;
        overflow: hidden;
    }

    .strength-indicator {
        height: 100%;
        width: 0%;
        transition: all 0.3s ease;
        border-radius: 2px;
    }

    /* Checkbox Moderno */
    .form-check-modern {
        display: flex;
        align-items: center;
        padding: 16px;
        background: #f0f9ff;
        border-radius: 12px;
        border: 2px solid #e0f2fe;
        transition: all 0.3s ease;
    }

    .form-check-modern:hover {
        background: #e0f2fe;
        border-color: var(--usuarios-color);
    }

    .form-check-input-modern {
        width: 24px;
        height: 24px;
        margin-right: 12px;
        cursor: pointer;
        accent-color: var(--usuarios-color);
    }

    .form-check-label-modern {
        margin: 0;
        cursor: pointer;
        font-weight: 600;
        color: #0891b2;
        font-size: 14px;
    }

    /* Información de Auditoría */
    .audit-info {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .audit-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        background: #f8fafc;
        border-radius: 12px;
        border: 2px solid #e0f2fe;
    }

    .audit-item i {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--usuarios-gradient);
        color: white;
        border-radius: 10px;
        font-size: 18px;
    }

    .audit-label {
        display: block;
        font-weight: 600;
        color: #64748b;
        font-size: 13px;
        margin-bottom: 4px;
    }

    .audit-value {
        display: block;
        color: #1e293b;
        font-weight: 700;
        font-size: 15px;
    }

    /* Botones */
    .form-actions {
        display: flex;
        gap: 12px;
        padding-top: 32px;
        border-top: 2px solid #f1f5f9;
        margin-top: 32px;
    }

    .btn-primary-modern {
        flex: 1;
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
    }

    .btn-primary-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(6, 182, 212, 0.4);
    }

    .btn-secondary-modern {
        flex: 1;
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
        justify-content: center;
    }

    .btn-secondary-modern:hover {
        background: #e2e8f0;
        color: #475569;
        transform: translateY(-2px);
    }

    /* Preview */
    .preview-section {
        text-align: center;
    }

    .preview-avatar {
        margin-bottom: 24px;
    }

    .avatar-circle {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: var(--usuarios-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        color: white;
        font-size: 40px;
        font-weight: 700;
        box-shadow: 0 8px 24px rgba(6, 182, 212, 0.3);
    }

    .preview-info {
        text-align: left;
    }

    .preview-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .preview-label {
        font-weight: 600;
        color: #64748b;
        font-size: 14px;
    }

    .preview-value {
        color: #1e293b;
        font-weight: 500;
        font-size: 14px;
    }

    .preview-badge {
        background: var(--usuarios-gradient);
        color: white;
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
    }

    .preview-status {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 600;
    }

    .preview-status.active {
        color: #10b981;
    }

    .preview-status.inactive {
        color: #64748b;
    }

    /* Panel de Ayuda */
    .help-content {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .help-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        font-size: 14px;
        color: #475569;
    }

    .help-item i {
        margin-top: 2px;
        flex-shrink: 0;
    }

    /* Alertas Modernas */
    .alert-modern {
        border-radius: 16px;
        border: none;
        padding: 20px;
        display: flex;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 24px;
    }

    .alert-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .alert-danger .alert-icon {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }

    .alert-content {
        flex: 1;
    }

    .alert-title {
        font-size: 16px;
        font-weight: 700;
        margin: 0 0 8px 0;
        color: #991b1b;
    }

    .error-list {
        margin: 0;
        padding-left: 20px;
        color: #dc2626;
    }

    .error-list li {
        margin-bottom: 4px;
    }

    /* Responsive */
    @media (max-width: 991px) {
        .col-lg-4 {
            margin-top: 24px;
        }
        
        .danger-content {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .btn-danger-modern {
            width: 100%;
        }
    }
</style>

<script>
    // Vista previa en tiempo real
    document.getElementById('nombre')?.addEventListener('input', function(e) {
        const value = e.target.value || '-';
        document.getElementById('previewNombre').textContent = value;
        
        // Actualizar avatar con inicial
        const inicial = value.charAt(0).toUpperCase();
        document.getElementById('previewAvatar').textContent = inicial || '';
    });

    document.getElementById('usuario')?.addEventListener('input', function(e) {
        document.getElementById('previewUsuario').textContent = e.target.value || '-';
    });

    document.getElementById('correo')?.addEventListener('input', function(e) {
        document.getElementById('previewCorreo').textContent = e.target.value || '-';
    });

    document.getElementById('telefono')?.addEventListener('input', function(e) {
        document.getElementById('previewTelefono').textContent = e.target.value || '-';
    });

    document.getElementById('id_rol')?.addEventListener('change', function(e) {
        const selectedOption = e.target.options[e.target.selectedIndex];
        document.getElementById('previewRol').textContent = selectedOption.text;
    });

    document.getElementById('activo')?.addEventListener('change', function(e) {
        const statusEl = document.getElementById('previewEstado');
        if (e.target.checked) {
            statusEl.innerHTML = '<i class="fas fa-check-circle"></i> Activo';
            statusEl.className = 'preview-status active';
        } else {
            statusEl.innerHTML = '<i class="fas fa-times-circle"></i> Inactivo';
            statusEl.className = 'preview-status inactive';
        }
    });

    // Toggle password visibility
    function togglePassword() {
        const passwordInput = document.getElementById('contrasena');
        const toggleIcon = document.querySelector('.toggle-password');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }

    // Password strength indicator
    function updatePasswordPreview() {
        const password = document.getElementById('contrasena').value;
        const indicator = document.getElementById('strengthIndicator');
        const strengthText = document.getElementById('strengthText');
        
        if (!password) {
            indicator.style.width = '0%';
            strengthText.textContent = '';
            return;
        }
        
        let strength = 0;
        let text = '';
        let color = '';

        if (password.length >= 8) strength += 25;
        if (password.match(/[a-z]+/)) strength += 25;
        if (password.match(/[A-Z]+/)) strength += 25;
        if (password.match(/[0-9]+/)) strength += 25;

        indicator.style.width = strength + '%';

        if (strength <= 25) {
            text = 'Muy débil';
            color = '#ef4444';
        } else if (strength <= 50) {
            text = 'Débil';
            color = '#f59e0b';
        } else if (strength <= 75) {
            text = 'Media';
            color = '#3b82f6';
        } else {
            text = 'Fuerte';
            color = '#10b981';
        }

        indicator.style.background = color;
        strengthText.textContent = text;
        strengthText.style.color = color;
    }
</script>

@endsection

@section('content')
<style>
    .edit-glass {
        background: rgba(255,255,255,0.13);
        border-radius: 24px;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.25);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,0.18);
        padding: 2.5rem 2rem;
        margin-top: 40px;
        max-width: 520px;
        margin-left: auto;
        margin-right: auto;
    }
    .edit-avatar {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        object-fit: cover;
        box-shadow: 0 2px 16px #00c3ff44;
        border: 3px solid #ff00cc;
        margin-bottom: 18px;
        animation: avatarGlow 2s infinite alternate;
    }
    @keyframes avatarGlow {
        0% { box-shadow: 0 0 12px #00c3ff88; border-color: #00c3ff; }
        100% { box-shadow: 0 0 24px #ff00cc88; border-color: #ff00cc; }
    }
    .edit-title {
        font-size: 2rem;
        font-weight: 800;
        color: #2c5364;
        text-shadow: 0 2px 8px #00c3ff22;
        margin-bottom: 0.5rem;
        text-align: center;
    }
    .form-label {
        font-weight: 700;
        color: #00c3ff;
        margin-bottom: 0.3em;
    }
    .form-control, .form-select {
        border-radius: 12px;
        border: 1px solid #00c3ff44;
        box-shadow: 0 2px 8px #00c3ff11;
        background: linear-gradient(90deg, #f0f8ff 0%, #fff 100%);
        margin-bottom: 1em;
        font-size: 1.1rem;
    }
    .form-check-input {
        accent-color: #ff00cc;
        box-shadow: 0 2px 8px #ff00cc44;
    }
    .btn-futurista {
        background: linear-gradient(90deg, #00c3ff 0%, #ff00cc 100%);
        color: #fff;
        font-weight: 700;
        border: none;
        border-radius: 50px;
        box-shadow: 0 2px 8px #00c3ff44;
        padding: 0.7em 2em;
        font-size: 1.1rem;
        transition: background 0.3s, box-shadow 0.3s;
        margin-top: 1em;
    }
    .btn-futurista:hover {
        background: linear-gradient(90deg, #ff00cc 0%, #00c3ff 100%);
        box-shadow: 0 4px 16px #ff00cc44;
    }
</style>
<div class="container">
    <div class="edit-glass animate__animated animate__fadeIn">
        <div class="d-flex flex-column align-items-center mb-3">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($usuario->nombre) }}&background=ff00cc&color=fff&size=128" alt="Avatar" class="edit-avatar img-fluid">
            <div class="edit-title"><i class="fas fa-user-edit me-2"></i>Editar Usuario</div>
        </div>
        <form action="{{ route('usuarios.update', $usuario) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-control" value="{{ $usuario->nombre }}" required>
            </div>
            <div class="mb-3">
                <label for="usuario" class="form-label">Usuario</label>
                <input type="text" name="usuario" id="usuario" class="form-control" value="{{ $usuario->usuario }}" required>
            </div>
            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña</label>
                <input type="password" name="contrasena" id="contrasena" class="form-control">
                <small class="form-text text-muted">Dejar en blanco para mantener la contraseña actual.</small>
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" name="correo" id="correo" class="form-control" value="{{ $usuario->correo }}">
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" name="telefono" id="telefono" class="form-control" value="{{ $usuario->telefono }}">
            </div>
            <div class="mb-3">
                <label for="id_rol" class="form-label">Rol</label>
                <select name="id_rol" id="id_rol" class="form-select" required>
                    @foreach ($roles as $rol)
                        <option value="{{ $rol->id_rol }}" {{ $usuario->id_rol == $rol->id_rol ? 'selected' : '' }}>{{ $rol->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group form-check mb-3">
                <input type="checkbox" name="activo" id="activo" class="form-check-input" {{ $usuario->activo ? 'checked' : '' }}>
                <label for="activo" class="form-check-label">Activo</label>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-futurista"><i class="fas fa-save me-2"></i>Guardar Cambios</button>
                <a href="{{ route('usuarios.index') }}" class="btn btn-futurista ms-2"><i class="fas fa-arrow-left me-2"></i>Volver</a>
            </div>
        </form>
    </div>
</div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection