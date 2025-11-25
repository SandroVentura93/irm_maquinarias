@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header con Gradiente -->
    <div class="page-header-modern">
        <div class="header-content">
            <div class="header-left">
                <a href="{{ route('monedas.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="header-info">
                    <h1 class="header-title">
                        <i class="fas fa-coins me-3"></i>
                        Nueva Moneda
                    </h1>
                    <p class="header-subtitle">Registra una nueva moneda en el sistema</p>
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
                    <span>Información de la Moneda</span>
                </div>
                <div class="card-body-modern">
                    <form action="{{ route('monedas.store') }}" method="POST" id="monedaForm">
                        @csrf
                        
                        <div class="form-section">
                            <h6 class="section-title">
                                <i class="fas fa-money-bill-wave text-purple"></i>
                                Datos de la Moneda
                            </h6>
                            
                            <div class="row g-4">
                                <div class="col-md-12">
                                    <div class="form-group-modern">
                                        <label for="nombre" class="form-label-modern">
                                            Nombre de la Moneda <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-icon-wrapper">
                                            <i class="fas fa-tag input-icon"></i>
                                            <input type="text" 
                                                   name="nombre" 
                                                   id="nombre" 
                                                   class="form-control-modern" 
                                                   value="{{ old('nombre') }}"
                                                   placeholder="Ej: Sol Peruano"
                                                   required>
                                        </div>
                                        <small class="form-help">Nombre completo de la moneda</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="simbolo" class="form-label-modern">
                                            Símbolo <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-icon-wrapper">
                                            <i class="fas fa-dollar-sign input-icon"></i>
                                            <input type="text" 
                                                   name="simbolo" 
                                                   id="simbolo" 
                                                   class="form-control-modern" 
                                                   value="{{ old('simbolo') }}"
                                                   placeholder="Ej: S/"
                                                   maxlength="5"
                                                   required>
                                        </div>
                                        <small class="form-help">Símbolo monetario (máx. 5 caracteres)</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="codigo_iso" class="form-label-modern">
                                            Código ISO <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-icon-wrapper">
                                            <i class="fas fa-code input-icon"></i>
                                            <input type="text" 
                                                   name="codigo_iso" 
                                                   id="codigo_iso" 
                                                   class="form-control-modern" 
                                                   value="{{ old('codigo_iso') }}"
                                                   placeholder="Ej: PEN"
                                                   maxlength="3"
                                                   style="text-transform: uppercase;"
                                                   required>
                                        </div>
                                        <small class="form-help">Código ISO 4217 (3 letras)</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-submit">
                                <i class="fas fa-save me-2"></i>
                                Guardar Moneda
                            </button>
                            <a href="{{ route('monedas.index') }}" class="btn-cancel">
                                <i class="fas fa-times me-2"></i>
                                Cancelar
                            </a>
                        </div>
                    </form>
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
                    <div class="preview-card">
                        <div class="preview-icon">
                            <i class="fas fa-coins"></i>
                        </div>
                        <div class="preview-content">
                            <div class="preview-name" id="preview-nombre">Nombre de la Moneda</div>
                            <div class="preview-details">
                                <span class="preview-symbol" id="preview-simbolo">S/</span>
                                <span class="preview-iso" id="preview-codigo">PEN</span>
                            </div>
                        </div>
                    </div>
                    <div class="preview-note">
                        <i class="fas fa-info-circle"></i>
                        <span>Así se verá la moneda en el sistema</span>
                    </div>
                </div>
            </div>

            <!-- Panel de Ayuda -->
            <div class="card-modern help-card">
                <div class="card-header-gradient">
                    <i class="fas fa-question-circle"></i>
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
                            <li>El código ISO debe tener exactamente 3 letras</li>
                            <li>El símbolo puede ser hasta 5 caracteres</li>
                            <li>Usa nombres estándar internacionales</li>
                        </ul>
                    </div>

                    <div class="help-section">
                        <h6 class="help-title">
                            <i class="fas fa-globe text-info"></i>
                            Ejemplos de Códigos ISO
                        </h6>
                        <div class="iso-examples">
                            <div class="iso-example">
                                <strong>PEN:</strong> Sol Peruano (S/)
                            </div>
                            <div class="iso-example">
                                <strong>USD:</strong> Dólar Americano ($)
                            </div>
                            <div class="iso-example">
                                <strong>EUR:</strong> Euro (€)
                            </div>
                            <div class="iso-example">
                                <strong>GBP:</strong> Libra Esterlina (£)
                            </div>
                            <div class="iso-example">
                                <strong>JPY:</strong> Yen Japonés (¥)
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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

    .text-purple {
        color: #667eea;
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
        color: #667eea;
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
        border-color: #667eea;
        background: white;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    .form-control-modern::placeholder {
        color: #a0aec0;
    }

    .form-help {
        display: block;
        margin-top: 6px;
        font-size: 12px;
        color: #718096;
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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 14px 32px;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        display: inline-flex;
        align-items: center;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
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

    /* Vista Previa */
    .preview-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 30px;
        border-radius: 16px;
        text-align: center;
        margin-bottom: 20px;
    }

    .preview-icon {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 36px;
        color: white;
        backdrop-filter: blur(10px);
    }

    .preview-content {
        color: white;
    }

    .preview-name {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 15px;
        min-height: 32px;
    }

    .preview-details {
        display: flex;
        gap: 15px;
        justify-content: center;
        align-items: center;
    }

    .preview-symbol {
        background: rgba(255, 255, 255, 0.9);
        color: #667eea;
        padding: 10px 20px;
        border-radius: 12px;
        font-size: 24px;
        font-weight: 700;
        min-width: 60px;
    }

    .preview-iso {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 10px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        backdrop-filter: blur(10px);
        min-width: 70px;
    }

    .preview-note {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px;
        background: #f0f3ff;
        border-radius: 8px;
        color: #667eea;
        font-size: 13px;
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

    .iso-examples {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .iso-example {
        background: #f7fafc;
        padding: 12px;
        border-radius: 8px;
        font-size: 13px;
        color: #4a5568;
        border-left: 3px solid #667eea;
    }

    .iso-example strong {
        color: #667eea;
        font-size: 14px;
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

        .preview-details {
            flex-direction: column;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nombreInput = document.getElementById('nombre');
        const simboloInput = document.getElementById('simbolo');
        const codigoInput = document.getElementById('codigo_iso');
        
        const previewNombre = document.getElementById('preview-nombre');
        const previewSimbolo = document.getElementById('preview-simbolo');
        const previewCodigo = document.getElementById('preview-codigo');

        // Actualizar vista previa en tiempo real
        nombreInput.addEventListener('input', function() {
            previewNombre.textContent = this.value || 'Nombre de la Moneda';
        });

        simboloInput.addEventListener('input', function() {
            previewSimbolo.textContent = this.value || 'S/';
        });

        codigoInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
            previewCodigo.textContent = this.value || 'PEN';
        });

        // Validar longitud del código ISO
        codigoInput.addEventListener('input', function() {
            if (this.value.length > 3) {
                this.value = this.value.substring(0, 3);
            }
        });
    });
</script>
@endsection