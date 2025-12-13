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
                        <i class="fas fa-edit me-3"></i>
                        Editar Moneda
                    </h1>
                    <p class="header-subtitle">Actualiza la información de: {{ $moneda->nombre }}</p>
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
                    <i class="fas fa-coins"></i>
                    <span>Información de la Moneda</span>
                </div>
                <div class="card-body-modern">
                    <form action="{{ route('monedas.update', $moneda->id_moneda) }}" method="POST" id="monedaForm">
                        @csrf
                        @method('PUT')
                        
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
                                                   value="{{ old('nombre', $moneda->nombre) }}"
                                                   placeholder="Ej: Dólar Americano"
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
                                                   value="{{ old('simbolo', $moneda->simbolo) }}"
                                                   placeholder="Ej: $"
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
                                                   value="{{ old('codigo_iso', $moneda->codigo_iso) }}"
                                                   placeholder="Ej: USD"
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
                                Actualizar Moneda
                            </button>
                            <a href="{{ route('monedas.index') }}" class="btn-cancel">
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
                            <h6 class="danger-title">Eliminar Moneda</h6>
                            <p class="danger-text">Una vez eliminada, esta moneda no podrá ser recuperada. Esta acción es permanente.</p>
                        </div>
                        @if(auth()->check() && auth()->user()->id_rol === 1)
                        <form action="{{ route('monedas.destroy', $moneda->id_moneda) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta moneda? Esta acción no se puede deshacer.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger-zone">
                                <i class="fas fa-trash-alt me-2"></i>
                                Eliminar Moneda
                            </button>
                        </form>
                        @endif
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
                    <div class="preview-card">
                        <div class="preview-icon">
                            <i class="fas fa-coins"></i>
                        </div>
                        <div class="preview-content">
                            <div class="preview-name" id="preview-nombre">{{ $moneda->nombre }}</div>
                            <div class="preview-details">
                                <span class="preview-symbol" id="preview-simbolo">{{ $moneda->simbolo }}</span>
                                <span class="preview-iso" id="preview-codigo">{{ $moneda->codigo_iso }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="preview-note">
                        <i class="fas fa-info-circle"></i>
                        <span>Así se verá la moneda en el sistema</span>
                    </div>
                </div>
            </div>

            <!-- Auditoría -->
            <div class="card-modern">
                <div class="card-header-gradient">
                    <i class="fas fa-history"></i>
                    <span>Auditoría</span>
                </div>
                <div class="card-body-modern">
                    <div class="audit-timeline">
                        @if($moneda->created_at)
                        <div class="audit-item">
                            <div class="audit-icon audit-icon-success">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="audit-content">
                                <div class="audit-label">Fecha de Creación</div>
                                <div class="audit-value">{{ $moneda->created_at->format('d/m/Y H:i') }}</div>
                                <div class="audit-time">{{ $moneda->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        @endif

                        @if($moneda->updated_at)
                        <div class="audit-item">
                            <div class="audit-icon audit-icon-info">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div class="audit-content">
                                <div class="audit-label">Última Modificación</div>
                                <div class="audit-value">{{ $moneda->updated_at->format('d/m/Y H:i') }}</div>
                                <div class="audit-time">{{ $moneda->updated_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Panel de Ayuda -->
            <div class="card-modern help-card">
                <div class="card-header-gradient">
                    <i class="fas fa-question-circle"></i>
                    <span>Información</span>
                </div>
                <div class="card-body-modern">
                    <div class="help-section">
                        <h6 class="help-title">
                            <i class="fas fa-lightbulb text-warning"></i>
                            Consejos
                        </h6>
                        <ul class="help-list">
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
                        </div>
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

        .preview-details {
            flex-direction: column;
        }
    }
</style>

<script src="{{ asset('js/monedas_edit.js') }}" defer></script>
@endsection