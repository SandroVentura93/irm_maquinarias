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
                        <i class="fas fa-user-plus me-3"></i>
                        Nuevo Usuario
                    </h1>
                    <p class="header-subtitle">Registra un nuevo usuario en el sistema</p>
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
                    <i class="fas fa-plus-circle"></i>
                    <span>Información del Usuario</span>
                </div>
                <div class="card-body-modern">
                    <form action="{{ route('usuarios.store') }}" method="POST" id="usuarioForm">
                        @csrf
                        
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
                                                   value="{{ old('nombre') }}"
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
                                                   value="{{ old('correo') }}"
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
                                                   value="{{ old('telefono') }}"
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
                                                   value="{{ old('usuario') }}"
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
                                                <option value="">Seleccionar rol...</option>
                                                @foreach ($roles as $rol)
                                                    <option value="{{ $rol->id_rol }}" {{ old('id_rol') == $rol->id_rol ? 'selected' : '' }}>
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
                                            Contraseña <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-icon-wrapper">
                                            <i class="fas fa-lock input-icon"></i>
                                            <input type="password" 
                                                   name="contrasena" 
                                                   id="contrasena" 
                                                   class="form-control-modern" 
                                                   placeholder="Mínimo 8 caracteres"
                                                   required
                                                   onkeyup="updatePasswordPreview()">
                                            <i class="fas fa-eye input-icon-right toggle-password" 
                                               onclick="togglePassword()"
                                               style="cursor: pointer;"></i>
                                        </div>
                                        <small class="form-help">Debe tener al menos 8 caracteres</small>
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
                                               {{ old('activo', '1') ? 'checked' : '' }}>
                                        <label for="activo" class="form-check-label-modern">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            Usuario activo (puede acceder al sistema)
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-primary-modern">
                                <i class="fas fa-save me-2"></i>
                                Crear Usuario
                            </button>
                            <a href="{{ route('usuarios.index') }}" class="btn-secondary-modern">
                                <i class="fas fa-times me-2"></i>
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panel de Ayuda -->
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
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                        <div class="preview-info">
                            <div class="preview-item">
                                <span class="preview-label">Nombre:</span>
                                <span class="preview-value" id="previewNombre">-</span>
                            </div>
                            <div class="preview-item">
                                <span class="preview-label">Usuario:</span>
                                <span class="preview-value" id="previewUsuario">-</span>
                            </div>
                            <div class="preview-item">
                                <span class="preview-label">Correo:</span>
                                <span class="preview-value" id="previewCorreo">-</span>
                            </div>
                            <div class="preview-item">
                                <span class="preview-label">Teléfono:</span>
                                <span class="preview-value" id="previewTelefono">-</span>
                            </div>
                            <div class="preview-item">
                                <span class="preview-label">Rol:</span>
                                <span class="preview-badge" id="previewRol">-</span>
                            </div>
                            <div class="preview-item">
                                <span class="preview-label">Estado:</span>
                                <span class="preview-status active" id="previewEstado">
                                    <i class="fas fa-check-circle"></i> Activo
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
                            <span>El nombre de usuario debe ser único</span>
                        </div>
                        <div class="help-item">
                            <i class="fas fa-check text-success"></i>
                            <span>La contraseña debe tener al menos 8 caracteres</span>
                        </div>
                        <div class="help-item">
                            <i class="fas fa-check text-success"></i>
                            <span>El rol determina los permisos del usuario</span>
                        </div>
                        <div class="help-item">
                            <i class="fas fa-check text-success"></i>
                            <span>Los usuarios inactivos no pueden acceder</span>
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
        document.getElementById('previewAvatar').innerHTML = inicial ? inicial : '<i class="fas fa-user"></i>';
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
        document.getElementById('previewRol').textContent = selectedOption.text !== 'Seleccionar rol...' ? selectedOption.text : '-';
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
        
        let strength = 0;
        let text = '';
        let color = '';

        if (password.length >= 8) strength += 25;
        if (password.match(/[a-z]+/)) strength += 25;
        if (password.match(/[A-Z]+/)) strength += 25;
        if (password.match(/[0-9]+/)) strength += 25;

        indicator.style.width = strength + '%';

        if (strength === 0) {
            text = '';
            color = '#e2e8f0';
        } else if (strength <= 25) {
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
    /* Variables de Color - Tema Cyan/Azul */
    :root {
        --usuarios-gradient: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);
    }

    /* Layout Principal */
    .usuarios-create-container {
        padding-right: 40px;
        min-height: 100vh;
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 50%, #f0f9ff 100%);
        animation: fadeIn 0.6s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .content-wrapper {
        max-width: 1000px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    /* Header */
    .page-header {
        background: var(--usuarios-gradient);
        padding: 40px;
        border-radius: 24px;
        margin-bottom: 32px;
        box-shadow: 0 8px 32px rgba(6, 182, 212, 0.2);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        border-radius: 50%;
    }

    .page-header-content {
        position: relative;
        z-index: 1;
    }

    .page-header h1 {
        color: white;
        font-size: 32px;
        font-weight: 800;
        margin: 0 0 8px 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .page-header h1 i {
        font-size: 28px;
    }

    .page-subtitle {
        color: rgba(255,255,255,0.95);
        font-size: 15px;
        margin: 0;
    }

    /* Formulario */
    .form-card {
        background: white;
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 8px 32px rgba(6, 182, 212, 0.1);
        position: relative;
        overflow: hidden;
    }

    .form-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: var(--usuarios-gradient);
    }

    .form-section {
        margin-bottom: 32px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid #f0f9ff;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        color: #06b6d4;
        font-size: 20px;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #475569;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-group label i {
        color: #06b6d4;
        margin-right: 6px;
        width: 16px;
    }

    .form-control,
    .form-select {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #e0f2fe;
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s ease;
        background: #f8fafc;
    }

    .form-control:focus,
    .form-select:focus {
        outline: none;
        border-color: #06b6d4;
        box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.1);
        background: white;
    }

    .form-control::placeholder {
        color: #94a3b8;
    }

    /* Input con Icono */
    .input-group {
        position: relative;
    }

    .input-icon {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .input-icon:hover {
        color: #06b6d4;
    }

    /* Checkbox Moderno */
    .form-check-modern {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px;
        background: #f0f9ff;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .form-check-modern:hover {
        background: #e0f2fe;
    }

    .form-check-modern input[type="checkbox"] {
        width: 24px;
        height: 24px;
        cursor: pointer;
        accent-color: #06b6d4;
    }

    .form-check-modern label {
        margin: 0;
        cursor: pointer;
        font-weight: 600;
        color: #0891b2;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Select Personalizado */
    .form-select {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2306b6d4' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 16px center;
        padding-right: 48px;
    }

    /* Botones de Acción */
    .form-actions {
        display: flex;
        gap: 12px;
        padding-top: 32px;
        border-top: 2px solid #f1f5f9;
        margin-top: 32px;
    }

    .btn-submit {
        flex: 1;
        padding: 16px 32px;
        background: var(--usuarios-gradient);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 16px rgba(6, 182, 212, 0.3);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(6, 182, 212, 0.4);
    }

    .btn-cancel {
        flex: 1;
        padding: 16px 32px;
        background: #f1f5f9;
        color: #64748b;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-cancel:hover {
        background: #e2e8f0;
        color: #475569;
        transform: translateY(-2px);
    }

    /* Grid de Dos Columnas */
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    /* Panel de Ayuda */
    .help-panel {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border-left: 4px solid #06b6d4;
        padding: 20px;
        border-radius: 12px;
        margin-top: 24px;
    }

    .help-panel h4 {
        color: #0891b2;
        font-size: 14px;
        font-weight: 700;
        margin: 0 0 12px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .help-panel ul {
        margin: 0;
        padding-left: 20px;
        color: #475569;
        font-size: 13px;
    }

    .help-panel li {
        margin-bottom: 6px;
    }

    /* Alertas */
    .alert-modern {
        padding: 16px 20px;
        border-radius: 12px;
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

    .alert-modern.alert-danger {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
        border-left: 4px solid #dc2626;
    }

    .alert-modern i {
        font-size: 20px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .usuarios-create-container {
            padding-left: 0;
            padding-right: 0;
        }

        .content-wrapper {
            padding: 20px 16px;
        }

        .form-card {
            padding: 24px 20px;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }
    }

    /* Password Toggle */
    .password-strength {
        height: 4px;
        background: #e2e8f0;
        border-radius: 2px;
        margin-top: 8px;
        overflow: hidden;
    }

    .password-strength-bar {
        height: 100%;
        background: var(--usuarios-gradient);
        width: 0%;
        transition: width 0.3s ease;
    }
</style>

<div class="usuarios-create-container">
    <div class="content-wrapper">
        <!-- Header -->
        <div class="page-header">
            <div class="page-header-content">
                <h1>
                    <i class="fas fa-user-plus"></i>
                    Nuevo Usuario
                </h1>
                <p class="page-subtitle">Completa el formulario para crear un nuevo usuario en el sistema</p>
            </div>
        </div>

        <!-- Errores -->
        @if ($errors->any())
        <div class="alert-modern alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                <strong>Error al crear el usuario:</strong>
                <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <!-- Formulario -->
        <div class="form-card">
            <form action="{{ route('usuarios.store') }}" method="POST" id="createUserForm">
                @csrf

                <!-- Información Básica -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-user"></i>
                        Información Básica
                    </h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="nombre">
                                <i class="fas fa-id-card"></i>
                                Nombre Completo *
                            </label>
                            <input 
                                type="text" 
                                name="nombre" 
                                id="nombre" 
                                class="form-control" 
                                placeholder="Ej: Juan Pérez García"
                                value="{{ old('nombre') }}"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="usuario">
                                <i class="fas fa-user-circle"></i>
                                Nombre de Usuario *
                            </label>
                            <input 
                                type="text" 
                                name="usuario" 
                                id="usuario" 
                                class="form-control" 
                                placeholder="usuario123"
                                value="{{ old('usuario') }}"
                                required
                            >
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="correo">
                                <i class="fas fa-envelope"></i>
                                Correo Electrónico
                            </label>
                            <input 
                                type="email" 
                                name="correo" 
                                id="correo" 
                                class="form-control" 
                                placeholder="usuario@ejemplo.com"
                                value="{{ old('correo') }}"
                            >
                        </div>

                        <div class="form-group">
                            <label for="telefono">
                                <i class="fas fa-phone"></i>
                                Teléfono
                            </label>
                            <input 
                                type="text" 
                                name="telefono" 
                                id="telefono" 
                                class="form-control" 
                                placeholder="987654321"
                                value="{{ old('telefono') }}"
                            >
                        </div>
                    </div>
                </div>

                <!-- Seguridad y Permisos -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-shield-alt"></i>
                        Seguridad y Permisos
                    </h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="contrasena">
                                <i class="fas fa-lock"></i>
                                Contraseña *
                            </label>
                            <div class="input-group">
                                <input 
                                    type="password" 
                                    name="contrasena" 
                                    id="contrasena" 
                                    class="form-control" 
                                    placeholder="Mínimo 8 caracteres"
                                    required
                                    onkeyup="checkPasswordStrength()"
                                >
                                <i class="fas fa-eye input-icon" id="togglePassword" onclick="togglePasswordVisibility()"></i>
                            </div>
                            <div class="password-strength">
                                <div class="password-strength-bar" id="strengthBar"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="id_rol">
                                <i class="fas fa-user-tag"></i>
                                Rol del Usuario *
                            </label>
                            <select name="id_rol" id="id_rol" class="form-select" required>
                                <option value="">Seleccionar rol...</option>
                                @foreach ($roles as $rol)
                                    <option value="{{ $rol->id_rol }}" {{ old('id_rol') == $rol->id_rol ? 'selected' : '' }}>
                                        {{ $rol->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Estado del Usuario -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-toggle-on"></i>
                        Estado del Usuario
                    </h3>

                    <div class="form-check-modern">
                        <input 
                            type="checkbox" 
                            name="activo" 
                            id="activo" 
                            value="1"
                            {{ old('activo', '1') ? 'checked' : '' }}
                        >
                        <label for="activo">
                            <i class="fas fa-check-circle"></i>
                            Usuario Activo (puede acceder al sistema)
                        </label>
                    </div>
                </div>

                <!-- Panel de Ayuda -->
                <div class="help-panel">
                    <h4>
                        <i class="fas fa-info-circle"></i>
                        Información Importante
                    </h4>
                    <ul>
                        <li>Los campos marcados con (*) son obligatorios</li>
                        <li>El nombre de usuario debe ser único en el sistema</li>
                        <li>La contraseña debe tener al menos 8 caracteres</li>
                        <li>El rol determina los permisos del usuario</li>
                        <li>Los usuarios inactivos no pueden acceder al sistema</li>
                    </ul>
                </div>

                <!-- Botones de Acción -->
                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i>
                        Crear Usuario
                    </button>
                    <a href="{{ route('usuarios.index') }}" class="btn-cancel">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Toggle password visibility
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('contrasena');
        const toggleIcon = document.getElementById('togglePassword');
        
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

    // Check password strength
    function checkPasswordStrength() {
        const password = document.getElementById('contrasena').value;
        const strengthBar = document.getElementById('strengthBar');
        let strength = 0;

        if (password.length >= 8) strength += 25;
        if (password.match(/[a-z]+/)) strength += 25;
        if (password.match(/[A-Z]+/)) strength += 25;
        if (password.match(/[0-9]+/)) strength += 25;

        strengthBar.style.width = strength + '%';
        
        if (strength < 50) {
            strengthBar.style.background = 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)';
        } else if (strength < 75) {
            strengthBar.style.background = 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)';
        } else {
            strengthBar.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
        }
    }
</script>

@endsection