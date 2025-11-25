@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header con Gradiente -->
    <div class="page-header-modern">
        <div class="header-content">
            <div class="header-left">
                <a href="{{ route('proveedores.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="header-info">
                    <h1 class="header-title">
                        <i class="fas fa-truck me-3"></i>
                        Nuevo Proveedor
                    </h1>
                    <p class="header-subtitle">Registra un nuevo proveedor en el sistema</p>
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
                    <span>Información del Proveedor</span>
                </div>
                <div class="card-body-modern">
                    <form action="{{ route('proveedores.store') }}" method="POST" id="proveedorForm">
                        @csrf
                        
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
                                                   value="{{ old('razon_social') }}"
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
                                                <option value="">Seleccione tipo</option>
                                                <option value="DNI" {{ old('tipo_documento') == 'DNI' ? 'selected' : '' }}>DNI</option>
                                                <option value="RUC" {{ old('tipo_documento') == 'RUC' ? 'selected' : '' }}>RUC</option>
                                                <option value="PASAPORTE" {{ old('tipo_documento') == 'PASAPORTE' ? 'selected' : '' }}>Pasaporte</option>
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
                                                   value="{{ old('numero_documento') }}"
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
                                                   value="{{ old('contacto') }}"
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
                                                   value="{{ old('telefono') }}"
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
                                                   value="{{ old('correo') }}"
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
                                                   value="{{ old('direccion') }}"
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
                                                    <option value="{{ $ubigeo->id_ubigeo }}" {{ old('id_ubigeo') == $ubigeo->id_ubigeo ? 'selected' : '' }}>
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
                                                <option value="1" selected>Activo</option>
                                                <option value="0">Inactivo</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-submit">
                                <i class="fas fa-save me-2"></i>
                                Guardar Proveedor
                            </button>
                            <a href="{{ route('proveedores.index') }}" class="btn-cancel">
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
            <div class="card-modern help-card">
                <div class="card-header-gradient">
                    <i class="fas fa-info-circle"></i>
                    <span>Información de Ayuda</span>
                </div>
                <div class="card-body-modern">
                    <div class="help-section">
                        <h6 class="help-title">
                            <i class="fas fa-lightbulb text-warning"></i>
                            Consejos
                        </h6>
                        <ul class="help-list">
                            <li>Completa todos los campos obligatorios (*)</li>
                            <li>Verifica que el RUC tenga 11 dígitos</li>
                            <li>El DNI debe tener 8 dígitos</li>
                            <li>Proporciona un correo válido para notificaciones</li>
                        </ul>
                    </div>

                    <div class="help-section">
                        <h6 class="help-title">
                            <i class="fas fa-file-alt text-info"></i>
                            Tipos de Documento
                        </h6>
                        <div class="document-types">
                            <div class="doc-type-item">
                                <strong>RUC:</strong> Para empresas (11 dígitos)
                            </div>
                            <div class="doc-type-item">
                                <strong>DNI:</strong> Para personas naturales (8 dígitos)
                            </div>
                            <div class="doc-type-item">
                                <strong>Pasaporte:</strong> Para extranjeros
                            </div>
                        </div>
                    </div>

                    <div class="help-section">
                        <h6 class="help-title">
                            <i class="fas fa-shield-alt text-success"></i>
                            Validaciones
                        </h6>
                        <p class="help-text">
                            El sistema validará automáticamente los datos ingresados para garantizar la integridad de la información.
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
        display: flex;
        align-items: center;
    }

    .header-subtitle {
        margin: 5px 0 0 0;
        font-size: 14px;
        opacity: 0.9;
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

    .form-control-modern::placeholder {
        color: #a0aec0;
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

    /* Panel de Ayuda */
    .help-card {
        position: sticky;
        top: 20px;
    }

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

    .document-types {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .doc-type-item {
        background: #f7fafc;
        padding: 12px;
        border-radius: 8px;
        font-size: 13px;
        color: #4a5568;
        border-left: 3px solid #f5576c;
    }

    .doc-type-item strong {
        color: #2d3748;
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
                numeroDocumento.setAttribute('placeholder', 'Ingrese 11 dígitos');
            } else if (tipo === 'DNI') {
                numeroDocumento.setAttribute('maxlength', '8');
                numeroDocumento.setAttribute('placeholder', 'Ingrese 8 dígitos');
            } else {
                numeroDocumento.removeAttribute('maxlength');
                numeroDocumento.setAttribute('placeholder', 'Ingrese el número de documento');
            }
        });

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