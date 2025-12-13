@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header Moderno -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <a href="{{ route('marcas.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="header-info">
                    <h1 class="header-title">Editar Marca</h1>
                    <p class="header-subtitle">Modifica la información de la marca: <strong>{{ $marca->nombre }}</strong></p>
                </div>
            </div>
            <div class="header-badge">
                @if($marca->activo)
                    <span class="status-badge active">
                        <i class="fas fa-check-circle"></i> Activa
                    </span>
                @else
                    <span class="status-badge inactive">
                        <i class="fas fa-times-circle"></i> Inactiva
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Mensajes de Error -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show modern-alert-error" role="alert">
            <div class="alert-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="alert-content">
                <strong>Por favor corrige los siguientes errores:</strong>
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Formulario Moderno -->
    <div class="form-container">
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-icon">
                    <i class="fas fa-edit"></i>
                </div>
                <div>
                    <h2 class="form-title">Información de la Marca</h2>
                    <p class="form-subtitle">ID: #{{ $marca->id_marca }}</p>
                </div>
            </div>

            <form action="{{ route('marcas.update', $marca) }}" method="POST" class="modern-form">
                @csrf
                @method('PUT')
                
                <div class="form-grid">
                    <!-- Nombre -->
                    <div class="form-group-modern">
                        <label for="nombre" class="form-label">
                            <i class="fas fa-tag"></i> Nombre de la Marca
                            <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="nombre" 
                            id="nombre" 
                            class="form-control-modern @error('nombre') is-invalid @enderror" 
                            placeholder="Ej: Toyota, Samsung, Nike..."
                            value="{{ old('nombre', $marca->nombre) }}"
                            required
                        >
                        @error('nombre')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Estado -->
                    <div class="form-group-modern">
                        <label for="activo" class="form-label">
                            <i class="fas fa-toggle-on"></i> Estado
                        </label>
                        <select name="activo" id="activo" class="form-control-modern">
                            <option value="1" {{ old('activo', $marca->activo) == 1 ? 'selected' : '' }}>Activa</option>
                            <option value="0" {{ old('activo', $marca->activo) == 0 ? 'selected' : '' }}>Inactiva</option>
                        </select>
                    </div>
                </div>

                <!-- Descripción -->
                <div class="form-group-modern full-width">
                    <label for="descripcion" class="form-label">
                        <i class="fas fa-align-left"></i> Descripción
                    </label>
                    <textarea 
                        name="descripcion" 
                        id="descripcion" 
                        class="form-control-modern" 
                        rows="4"
                        placeholder="Agrega una descripción opcional de la marca..."
                    >{{ old('descripcion', $marca->descripcion) }}</textarea>
                </div>

                <!-- Información de Auditoría -->
                <div class="audit-info">
                    <div class="audit-item">
                        <i class="fas fa-calendar-plus"></i>
                        <div>
                            <span class="audit-label">Creado:</span>
                            <span class="audit-value">{{ $marca->created_at ? $marca->created_at->format('d/m/Y H:i') : 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="audit-item">
                        <i class="fas fa-calendar-check"></i>
                        <div>
                            <span class="audit-label">Última actualización:</span>
                            <span class="audit-value">{{ $marca->updated_at ? $marca->updated_at->format('d/m/Y H:i') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="form-actions">
                    <a href="{{ route('marcas.index') }}" class="btn-cancel">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Actualizar Marca
                    </button>
                </div>
            </form>
        </div>

        <!-- Panel Lateral -->
        <div class="side-panel">
            <!-- Tarjeta de Estadísticas -->
            <div class="info-card">
                <div class="info-card-header">
                    <div class="info-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3 class="info-title">Estadísticas</h3>
                </div>
                <div class="info-body">
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-label">Productos con esta marca</span>
                            <span class="stat-value">
                                @php
                                    $productosCont = \App\Models\Producto::where('id_marca', $marca->id_marca)->count();
                                @endphp
                                {{ $productosCont }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta de Ayuda -->
            <div class="help-card">
                <div class="help-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <h3 class="help-title">Información</h3>
                <ul class="help-list">
                    <li><i class="fas fa-check-circle"></i> Los cambios se aplicarán inmediatamente</li>
                    <li><i class="fas fa-check-circle"></i> El nombre debe ser único</li>
                    <li><i class="fas fa-check-circle"></i> Al desactivar, no se eliminarán productos</li>
                    <li><i class="fas fa-check-circle"></i> Puedes volver a activar cuando quieras</li>
                </ul>
            </div>

            <!-- Botón de Eliminación -->
            <div class="danger-zone">
                <h4 class="danger-title">
                    <i class="fas fa-exclamation-triangle"></i> Zona de Peligro
                </h4>
                <p class="danger-text">Esta acción es irreversible</p>
                @if(auth()->check() && auth()->user()->id_rol === 1)
                <form action="{{ route('marcas.destroy', $marca->id_marca) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta marca? Esta acción no se puede deshacer.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger-full">
                        <i class="fas fa-trash-alt"></i> Eliminar Marca
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    /* Header de Página */
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 20px;
        color: white;
    }

    .btn-back {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        text-decoration: none;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateX(-5px);
        color: white;
    }

    .header-info {
        color: white;
    }

    .header-title {
        font-size: 28px;
        font-weight: 700;
        margin: 0;
        color: white;
    }

    .header-subtitle {
        margin: 5px 0 0 0;
        font-size: 14px;
        opacity: 0.9;
    }

    .header-badge {
        display: flex;
        gap: 10px;
    }

    .status-badge {
        padding: 10px 20px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        backdrop-filter: blur(10px);
    }

    .status-badge.active {
        background: rgba(72, 187, 120, 0.9);
        color: white;
    }

    .status-badge.inactive {
        background: rgba(252, 129, 129, 0.9);
        color: white;
    }

    /* Alert Moderno de Error */
    .modern-alert-error {
        background: white;
        border: none;
        border-left: 4px solid #f56565;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 15px rgba(245, 101, 101, 0.2);
        display: flex;
        gap: 15px;
        margin-bottom: 30px;
    }

    .alert-icon {
        width: 40px;
        height: 40px;
        background: rgba(245, 101, 101, 0.1);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #f56565;
        font-size: 20px;
        flex-shrink: 0;
    }

    .alert-content {
        flex: 1;
    }

    .alert-content strong {
        color: #2d3748;
        display: block;
        margin-bottom: 10px;
    }

    .error-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .error-list li {
        color: #718096;
        padding: 5px 0;
        font-size: 14px;
    }

    /* Contenedor del Formulario */
    .form-container {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 30px;
    }

    /* Tarjeta del Formulario */
    .form-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .form-card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 30px;
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .form-icon {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
        backdrop-filter: blur(10px);
        flex-shrink: 0;
    }

    .form-title {
        color: white;
        font-size: 22px;
        font-weight: 600;
        margin: 0;
    }

    .form-subtitle {
        color: rgba(255, 255, 255, 0.9);
        font-size: 13px;
        margin: 5px 0 0 0;
    }

    /* Formulario Moderno */
    .modern-form {
        padding: 40px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
        margin-bottom: 25px;
    }

    .form-group-modern {
        display: flex;
        flex-direction: column;
    }

    .form-group-modern.full-width {
        grid-column: 1 / -1;
    }

    .form-label {
        font-size: 14px;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-label i {
        color: #667eea;
    }

    .required {
        color: #f56565;
        margin-left: 4px;
    }

    .form-control-modern {
        padding: 14px 18px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 15px;
        color: #2d3748;
        transition: all 0.3s ease;
        background: #f7fafc;
    }

    .form-control-modern:focus {
        outline: none;
        border-color: #667eea;
        background: white;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    .form-control-modern::placeholder {
        color: #a0aec0;
    }

    textarea.form-control-modern {
        resize: vertical;
        min-height: 100px;
        font-family: inherit;
    }

    select.form-control-modern {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23667eea' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 18px center;
        padding-right: 45px;
    }

    .error-message {
        color: #f56565;
        font-size: 13px;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .form-control-modern.is-invalid {
        border-color: #f56565;
        background: #fff5f5;
    }

    /* Información de Auditoría */
    .audit-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        padding: 20px;
        background: #f7fafc;
        border-radius: 12px;
        margin-bottom: 25px;
    }

    .audit-item {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .audit-item i {
        color: #667eea;
        font-size: 18px;
    }

    .audit-label {
        display: block;
        font-size: 12px;
        color: #718096;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .audit-value {
        display: block;
        font-size: 14px;
        color: #2d3748;
        font-weight: 600;
    }

    /* Botones de Acción */
    .form-actions {
        display: flex;
        gap: 15px;
        justify-content: flex-end;
        margin-top: 35px;
        padding-top: 30px;
        border-top: 2px solid #e2e8f0;
    }

    .btn-cancel {
        padding: 14px 28px;
        border: 2px solid #e2e8f0;
        background: white;
        color: #718096;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        background: #f7fafc;
        border-color: #cbd5e0;
        color: #4a5568;
        transform: translateY(-2px);
    }

    .btn-submit {
        padding: 14px 32px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
    }

    /* Panel Lateral */
    .side-panel {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    /* Tarjeta de Información */
    .info-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .info-card-header {
        background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .info-icon {
        width: 45px;
        height: 45px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: white;
        backdrop-filter: blur(10px);
    }

    .info-title {
        color: white;
        font-size: 18px;
        font-weight: 600;
        margin: 0;
    }

    .info-body {
        padding: 25px;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        background: #f7fafc;
        border-radius: 12px;
    }

    .stat-icon {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
    }

    .stat-info {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .stat-label {
        font-size: 13px;
        color: #718096;
        font-weight: 600;
    }

    .stat-value {
        font-size: 24px;
        color: #2d3748;
        font-weight: 700;
    }

    /* Tarjeta de Ayuda */
    .help-card {
        background: white;
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .help-icon {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: white;
        margin-bottom: 15px;
    }

    .help-title {
        font-size: 16px;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 15px;
    }

    .help-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .help-list li {
        padding: 10px 0;
        color: #4a5568;
        font-size: 13px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        border-bottom: 1px solid #e2e8f0;
    }

    .help-list li:last-child {
        border-bottom: none;
    }

    .help-list li i {
        color: #48bb78;
        margin-top: 2px;
        flex-shrink: 0;
    }

    /* Zona de Peligro */
    .danger-zone {
        background: white;
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 2px solid #fed7d7;
    }

    .danger-title {
        font-size: 16px;
        font-weight: 700;
        color: #c53030;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .danger-text {
        font-size: 13px;
        color: #718096;
        margin-bottom: 15px;
    }

    .btn-danger-full {
        width: 100%;
        padding: 12px;
        background: #fc8181;
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-danger-full:hover {
        background: #f56565;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(245, 101, 101, 0.4);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .form-container {
            grid-template-columns: 1fr;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .audit-info {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 20px;
        }

        .header-title {
            font-size: 22px;
        }

        .modern-form {
            padding: 25px;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .btn-cancel,
        .btn-submit {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endsection