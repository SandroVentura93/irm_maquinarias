@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header con Gradiente y Badge de Estado -->
    <div class="page-header-modern">
        <div class="header-content">
            <div class="header-left">
                <a href="{{ route('proveedores.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="header-info">
                    <div class="d-flex align-items-center gap-3">
                        <h1 class="header-title">
                            <i class="fas fa-edit me-3"></i>
                            Editar Proveedor
                        </h1>
                        @if($proveedor->activo ?? true)
                            <span class="status-badge status-active">
                                <i class="fas fa-check-circle"></i> Activo
                            </span>
                        @else
                            <span class="status-badge status-inactive">
                                <i class="fas fa-times-circle"></i> Inactivo
                            </span>
                        @endif
                    </div>
                    <p class="header-subtitle">Actualiza la información del proveedor: {{ $proveedor->razon_social }}</p>
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
                    <i class="fas fa-file-edit"></i>
                    <span>Información del Proveedor</span>
                </div>
                <div class="card-body-modern">
                    <form action="{{ route('proveedores.update', $proveedor->id_proveedor) }}" method="POST" id="proveedorForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-section">
                            <h6 class="section-title">
                                <i class="fas fa-building text-pink"></i>
                                Datos de la Empresa
                            </h6>
                            
                            <div class="row g-4">
                                <div class="col-md-12">
                                    <div class="form-group-modern">
                                        <label for="razon_social" class="form-label-modern">
                                            Razón Social <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-icon-wrapper">
                                            <i class="fas fa-building input-icon"></i>
                                            <input type="text" 
                                                   name="razon_social" 
                                                   id="razon_social" 
                                                   class="form-control-modern" 
                                                   value="{{ old('razon_social', $proveedor->razon_social) }}"
                                                   placeholder="Ingrese la razón social"
                                                   required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="tipo_documento" class="form-label-modern">
                                            Tipo de Documento <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-icon-wrapper">
                                            <i class="fas fa-id-card input-icon"></i>
                                            <select name="tipo_documento" id="tipo_documento" class="form-control-modern" required>
                                                <option value="DNI" {{ $proveedor->tipo_documento == 'DNI' ? 'selected' : '' }}>DNI</option>
                                                <option value="RUC" {{ $proveedor->tipo_documento == 'RUC' ? 'selected' : '' }}>RUC</option>
                                                <option value="PASAPORTE" {{ $proveedor->tipo_documento == 'PASAPORTE' ? 'selected' : '' }}>Pasaporte</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="numero_documento" class="form-label-modern">
                                            Número de Documento <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-icon-wrapper">
                                            <i class="fas fa-hashtag input-icon"></i>
                                            <input type="text" 
                                                   name="numero_documento" 
                                                   id="numero_documento" 
                                                   class="form-control-modern" 
                                                   value="{{ old('numero_documento', $proveedor->numero_documento) }}"
                                                   placeholder="Ej: 20123456789"
                                                   required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h6 class="section-title">
                                <i class="fas fa-address-book text-pink"></i>
                                Datos de Contacto
                            </h6>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="contacto" class="form-label-modern">Persona de Contacto</label>
                                        <div class="input-icon-wrapper">
                                            <i class="fas fa-user input-icon"></i>
                                            <input type="text" 
                                                   name="contacto" 
                                                   id="contacto" 
                                                   class="form-control-modern" 
                                                   value="{{ old('contacto', $proveedor->contacto) }}"
                                                   placeholder="Nombre del contacto">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="telefono" class="form-label-modern">Teléfono</label>
                                        <div class="input-icon-wrapper">
                                            <i class="fas fa-phone input-icon"></i>
                                            <input type="text" 
                                                   name="telefono" 
                                                   id="telefono" 
                                                   class="form-control-modern" 
                                                   value="{{ old('telefono', $proveedor->telefono) }}"
                                                   placeholder="Ej: 987654321">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group-modern">
                                        <label for="correo" class="form-label-modern">Correo Electrónico</label>
                                        <div class="input-icon-wrapper">
                                            <i class="fas fa-envelope input-icon"></i>
                                            <input type="email" 
                                                   name="correo" 
                                                   id="correo" 
                                                   class="form-control-modern" 
                                                   value="{{ old('correo', $proveedor->correo) }}"
                                                   placeholder="ejemplo@empresa.com">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h6 class="section-title">
                                <i class="fas fa-map-marker-alt text-pink"></i>
                                Ubicación
                            </h6>
                            
                            <div class="row g-4">
                                <div class="col-md-12">
                                    <div class="form-group-modern">
                                        <label for="direccion" class="form-label-modern">Dirección</label>
                                        <div class="input-icon-wrapper">
                                            <i class="fas fa-map-marked-alt input-icon"></i>
                                            <input type="text" 
                                                   name="direccion" 
                                                   id="direccion" 
                                                   class="form-control-modern" 
                                                   value="{{ old('direccion', $proveedor->direccion) }}"
                                                   placeholder="Ingrese la dirección completa">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group-modern">
                                        <label for="id_ubigeo" class="form-label-modern">Ubigeo</label>
                                        <div class="input-icon-wrapper">
                                            <i class="fas fa-map-pin input-icon"></i>
                                            <select name="id_ubigeo" id="id_ubigeo" class="form-control-modern">
                                                <option value="">Seleccione un Ubigeo</option>
                                                @foreach ($ubigeos as $ubigeo)
                                                    <option value="{{ $ubigeo->id_ubigeo }}" {{ $proveedor->id_ubigeo == $ubigeo->id_ubigeo ? 'selected' : '' }}>
                                                        {{ $ubigeo->departamento }} - {{ $ubigeo->provincia }} - {{ $ubigeo->distrito }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h6 class="section-title">
                                <i class="fas fa-toggle-on text-pink"></i>
                                Estado
                            </h6>
                            
                            <div class="row g-4">
                                <div class="col-md-12">
                                    <div class="form-group-modern">
                                        <label for="activo" class="form-label-modern">Estado del Proveedor</label>
                                        <div class="input-icon-wrapper">
                                            <i class="fas fa-power-off input-icon"></i>
                                            <select name="activo" id="activo" class="form-control-modern">
                                                <option value="1" {{ ($proveedor->activo ?? true) ? 'selected' : '' }}>Activo</option>
                                                <option value="0" {{ !($proveedor->activo ?? true) ? 'selected' : '' }}>Inactivo</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-submit">
                                <i class="fas fa-save me-2"></i>
                                Actualizar Proveedor
                            </button>
                            <a href="{{ route('proveedores.index') }}" class="btn-cancel">
                                <i class="fas fa-times me-2"></i>
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Zona de Peligro -->
            <div class="card-modern danger-zone">
                <div class="card-header-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Zona de Peligro</span>
                </div>
                <div class="card-body-modern">
                    <div class="danger-content">
                        <div class="danger-info">
                            <h6 class="danger-title">Eliminar Proveedor</h6>
                            <p class="danger-text">Una vez eliminado, este proveedor no podrá ser recuperado. Esta acción es permanente.</p>
                        </div>
                        @if(auth()->check() && auth()->user()->id_rol === 1)
                        <form action="{{ route('proveedores.destroy', $proveedor->id_proveedor) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este proveedor? Esta acción no se puede deshacer.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger-zone">
                                <i class="fas fa-trash-alt me-2"></i>
                                Eliminar Proveedor
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Lateral -->
        <div class="col-lg-4">
            <!-- Información de Auditoría -->
            <div class="card-modern">
                <div class="card-header-gradient">
                    <i class="fas fa-history"></i>
                    <span>Auditoría</span>
                </div>
                <div class="card-body-modern">
                    <div class="audit-timeline">
                        @if($proveedor->created_at)
                        <div class="audit-item">
                            <div class="audit-icon audit-icon-success">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="audit-content">
                                <div class="audit-label">Fecha de Creación</div>
                                <div class="audit-value">{{ $proveedor->created_at->format('d/m/Y H:i') }}</div>
                                <div class="audit-time">{{ $proveedor->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        @endif

                        @if($proveedor->updated_at)
                        <div class="audit-item">
                            <div class="audit-icon audit-icon-info">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div class="audit-content">
                                <div class="audit-label">Última Modificación</div>
                                <div class="audit-value">{{ $proveedor->updated_at->format('d/m/Y H:i') }}</div>
                                <div class="audit-time">{{ $proveedor->updated_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="card-modern">
                <div class="card-header-gradient">
                    <i class="fas fa-chart-bar"></i>
                    <span>Estadísticas</span>
                </div>
                <div class="card-body-modern">
                    @php
                        $totalCompras = \App\Models\Compra::where('id_proveedor', $proveedor->id_proveedor)->count();
                        $montoTotal = \App\Models\Compra::where('id_proveedor', $proveedor->id_proveedor)->sum('total');
                    @endphp
                    
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-icon stat-icon-primary">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-value">{{ $totalCompras }}</div>
                                <div class="stat-label">Total Compras</div>
                            </div>
                        </div>

                        <div class="stat-item">
                            <div class="stat-icon stat-icon-success">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-value">S/ {{ number_format($montoTotal, 2) }}</div>
                                <div class="stat-label">Monto Total</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel de Ayuda -->
            <div class="card-modern help-card">
                <div class="card-header-gradient">
                    <i class="fas fa-info-circle"></i>
                    <span>Información</span>
                </div>
                <div class="card-body-modern">
                    <div class="help-section">
                        <h6 class="help-title">
                            <i class="fas fa-lightbulb text-warning"></i>
                            Consejos
                        </h6>
                        <ul class="help-list">
                            <li>Verifica que los datos sean correctos antes de guardar</li>
                            <li>Los campos con (*) son obligatorios</li>
                            <li>Puedes cambiar el estado del proveedor</li>
                        </ul>
                    </div>

                    <div class="help-section">
                        <h6 class="help-title">
                            <i class="fas fa-shield-alt text-success"></i>
                            Seguridad
                        </h6>
                        <p class="help-text">
                            Todos los cambios quedan registrados en el historial de auditoría del sistema.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Header Moderno con Gradiente */
    .page-header-modern {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(240, 147, 251, 0.3);
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 20px;
        width: 100%;
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
        text-decoration: none;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        font-size: 18px;
        flex-shrink: 0;
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateX(-5px);
        color: white;
    }

    .header-info {
        color: white;
        flex: 1;
    }

    .header-title {
        font-size: 28px;
        font-weight: 700;
        margin: 0;
        color: white;
        display: flex;
        align-items: center;
    }

    .header-subtitle {
        margin: 5px 0 0 0;
        font-size: 14px;
        opacity: 0.9;
    }

    .status-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        backdrop-filter: blur(10px);
    }

    .status-active {
        background: rgba(40, 167, 69, 0.9);
        color: white;
    }

    .status-inactive {
        background: rgba(220, 53, 69, 0.9);
        color: white;
    }

    /* Alertas Modernas */
    .alert-modern {
        border: none;
        border-radius: 16px;
        padding: 20px;
        display: flex;
        align-items: flex-start;
        gap: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .alert-icon {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .alert-content {
        flex: 1;
    }

    .alert-title {
        font-weight: 700;
        margin-bottom: 10px;
        font-size: 16px;
    }

    .error-list {
        margin: 0;
        padding-left: 20px;
    }

    .error-list li {
        margin-bottom: 5px;
    }

    /* Tarjetas Modernas */
    .card-modern {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        margin-bottom: 30px;
    }

    .card-header-gradient {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        padding: 20px 25px;
        color: white;
        font-weight: 600;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-header-danger {
        background: linear-gradient(135deg, #fc8181 0%, #f56565 100%);
        padding: 20px 25px;
        color: white;
        font-weight: 600;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-body-modern {
        padding: 30px;
    }

    /* Secciones del Formulario */
    .form-section {
        margin-bottom: 35px;
        padding-bottom: 30px;
        border-bottom: 2px solid #f0f0f0;
    }

    .form-section:last-of-type {
        border-bottom: none;
    }

    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .text-pink {
        color: #f5576c;
    }

    /* Grupos de Formulario Modernos */
    .form-group-modern {
        margin-bottom: 0;
    }

    .form-label-modern {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 10px;
        font-size: 14px;
        display: block;
    }

    .input-icon-wrapper {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #f5576c;
        font-size: 16px;
        z-index: 1;
    }

    .form-control-modern {
        width: 100%;
        padding: 14px 16px 14px 45px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }

    .form-control-modern:focus {
        outline: none;
        border-color: #f5576c;
        background: white;
        box-shadow: 0 0 0 4px rgba(245, 87, 108, 0.1);
    }

    /* Botones de Acción */
    .form-actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
        padding-top: 30px;
        border-top: 2px solid #f0f0f0;
    }

    .btn-submit {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        padding: 14px 32px;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(245, 87, 108, 0.3);
        display: inline-flex;
        align-items: center;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(245, 87, 108, 0.4);
    }

    .btn-cancel {
        background: #e2e8f0;
        color: #4a5568;
        padding: 14px 32px;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    .btn-cancel:hover {
        background: #cbd5e0;
        color: #2d3748;
    }

    /* Zona de Peligro */
    .danger-zone {
        border: 2px solid #fc8181;
    }

    .danger-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
    }

    .danger-info {
        flex: 1;
    }

    .danger-title {
        font-size: 16px;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 8px;
    }

    .danger-text {
        font-size: 13px;
        color: #718096;
        margin: 0;
    }

    .btn-danger-zone {
        background: #fc8181;
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
    }

    .btn-danger-zone:hover {
        background: #f56565;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(245, 101, 101, 0.3);
    }

    /* Timeline de Auditoría */
    .audit-timeline {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .audit-item {
        display: flex;
        gap: 15px;
        align-items: flex-start;
    }

    .audit-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 16px;
    }

    .audit-icon-success {
        background: rgba(72, 187, 120, 0.1);
        color: #48bb78;
    }

    .audit-icon-info {
        background: rgba(66, 153, 225, 0.1);
        color: #4299e1;
    }

    .audit-content {
        flex: 1;
    }

    .audit-label {
        font-size: 12px;
        color: #a0aec0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .audit-value {
        font-size: 14px;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 4px;
    }

    .audit-time {
        font-size: 12px;
        color: #718096;
    }

    /* Estadísticas */
    .stats-grid {
        display: grid;
        gap: 15px;
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
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .stat-icon-primary {
        background: rgba(245, 87, 108, 0.1);
        color: #f5576c;
    }

    .stat-icon-success {
        background: rgba(72, 187, 120, 0.1);
        color: #48bb78;
    }

    .stat-info {
        flex: 1;
    }

    .stat-value {
        font-size: 20px;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 12px;
        color: #718096;
    }

    /* Panel de Ayuda */
    .help-section {
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e2e8f0;
    }

    .help-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .help-title {
        font-size: 14px;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .help-list {
        margin: 0;
        padding-left: 20px;
        color: #4a5568;
        font-size: 13px;
        line-height: 1.8;
    }

    .help-list li {
        margin-bottom: 8px;
    }

    .help-text {
        color: #4a5568;
        font-size: 13px;
        line-height: 1.6;
        margin: 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header-modern {
            padding: 20px;
        }

        .header-title {
            font-size: 22px;
        }

        .card-body-modern {
            padding: 20px;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-submit,
        .btn-cancel {
            width: 100%;
            justify-content: center;
        }

        .danger-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .btn-danger-zone {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Validación del tipo de documento según longitud
        const tipoDocumento = document.getElementById('tipo_documento');
        const numeroDocumento = document.getElementById('numero_documento');

        tipoDocumento.addEventListener('change', function() {
            const tipo = this.value;
            
            if (tipo === 'RUC') {
                numeroDocumento.setAttribute('maxlength', '11');
            } else if (tipo === 'DNI') {
                numeroDocumento.setAttribute('maxlength', '8');
            } else {
                numeroDocumento.removeAttribute('maxlength');
            }
        });

        // Aplicar validación inicial
        tipoDocumento.dispatchEvent(new Event('change'));

        // Solo permitir números en el campo de documento
        numeroDocumento.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Solo permitir números en el campo de teléfono
        const telefono = document.getElementById('telefono');
        telefono.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9+\-\s]/g, '');
        });
    });
</script>
@endsection