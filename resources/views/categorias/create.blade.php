@extends('layouts.dashboard')

@section('content')
<div class="container-fluid modern-container">
    <!-- Hero Header -->
    <div class="hero-header mb-4">
        <div class="hero-background"></div>
        <div class="hero-content">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="hero-icon me-4">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <div class="hero-text">
                        <h1 class="hero-title">Crear Nueva Categoría</h1>
                        <p class="hero-subtitle">
                            <i class="fas fa-info-circle me-2"></i>
                            Organiza tus productos creando una nueva categoría
                        </p>
                    </div>
                </div>
                <div class="hero-actions">
                    <a href="{{ route('categorias.index') }}" class="btn btn-secondary btn-hero">
                        <i class="fas fa-arrow-left me-2"></i>Volver a Lista
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger modern-alert alert-dismissible fade show mb-4">
            <div class="alert-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="alert-content">
                <strong>¡Atención!</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form action="{{ route('categorias.store') }}" method="POST" class="modern-form" id="categoryForm">
                @csrf
                
                <!-- Card Principal del Formulario -->
                <div class="card form-card mb-4">
                    <div class="card-header form-header">
                        <div class="d-flex align-items-center">
                            <div class="header-icon me-3">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0">Información de la Categoría</h5>
                                <small class="text-muted">Completa los datos para crear la nueva categoría</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Nombre de la Categoría -->
                            <div class="col-md-8">
                                <div class="form-section">
                                    <label class="modern-label required">
                                        <i class="fas fa-tag text-primary me-2"></i>
                                        Nombre de la Categoría
                                    </label>
                                    <div class="input-group modern-input-group">
                                        <span class="input-group-text modern-input-addon">
                                            <i class="fas fa-tag"></i>
                                        </span>
                                        <input type="text" 
                                               name="nombre" 
                                               id="nombre" 
                                               class="form-control modern-input @error('nombre') is-invalid @enderror" 
                                               value="{{ old('nombre') }}"
                                               placeholder="Ej: Maquinaria Pesada, Herramientas, Repuestos..."
                                               required
                                               maxlength="100">
                                        <div class="input-feedback"></div>
                                    </div>
                                    @error('nombre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-hint">
                                        <i class="fas fa-lightbulb me-1"></i>
                                        Ingresa un nombre descriptivo y único para la categoría
                                    </small>
                                </div>
                            </div>

                            <!-- Estado -->
                            <div class="col-md-4">
                                <div class="form-section">
                                    <label class="modern-label">
                                        <i class="fas fa-toggle-on text-success me-2"></i>
                                        Estado
                                    </label>
                                    <div class="modern-select-wrapper">
                                        <select name="activo" 
                                                id="activo" 
                                                class="form-select modern-select @error('activo') is-invalid @enderror">
                                            <option value="1" {{ old('activo', '1') == '1' ? 'selected' : '' }}>
                                                <i class="fas fa-check-circle"></i> Activo
                                            </option>
                                            <option value="0" {{ old('activo') == '0' ? 'selected' : '' }}>
                                                <i class="fas fa-times-circle"></i> Inactivo
                                            </option>
                                        </select>
                                        <div class="select-icon">
                                            <i class="fas fa-chevron-down"></i>
                                        </div>
                                    </div>
                                    @error('activo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-hint">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Solo categorías activas aparecerán en los formularios
                                    </small>
                                </div>
                            </div>

                            <!-- Descripción -->
                            <div class="col-12">
                                <div class="form-section">
                                    <label class="modern-label">
                                        <i class="fas fa-align-left text-info me-2"></i>
                                        Descripción
                                    </label>
                                    <div class="modern-textarea-wrapper">
                                        <textarea name="descripcion" 
                                                  id="descripcion" 
                                                  class="form-control modern-textarea @error('descripcion') is-invalid @enderror"
                                                  rows="4"
                                                  placeholder="Describe las características y productos que incluye esta categoría..."
                                                  maxlength="500">{{ old('descripcion') }}</textarea>
                                        <div class="textarea-counter">
                                            <span id="charCount">0</span>/500 caracteres
                                        </div>
                                    </div>
                                    @error('descripcion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-hint">
                                        <i class="fas fa-pencil-alt me-1"></i>
                                        Proporciona una descripción detallada para facilitar la organización
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preview de la Categoría -->
                <div class="card preview-card mb-4">
                    <div class="card-header preview-header">
                        <div class="d-flex align-items-center">
                            <div class="header-icon me-3">
                                <i class="fas fa-eye"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0">Vista Previa</h5>
                                <small class="text-muted">Así se verá tu nueva categoría</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="category-preview" id="categoryPreview">
                            <div class="preview-item">
                                <div class="preview-icon">
                                    <i class="fas fa-tag"></i>
                                </div>
                                <div class="preview-content">
                                    <div class="preview-name" id="previewName">Nueva Categoría</div>
                                    <div class="preview-description" id="previewDescription">Sin descripción</div>
                                    <div class="preview-status" id="previewStatus">
                                        <span class="status-badge status-active">
                                            <i class="fas fa-check-circle me-1"></i>Activo
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="card actions-card">
                    <div class="card-body text-center">
                        <div class="d-flex justify-content-center gap-3">
                            <button type="submit" class="btn btn-success btn-action" id="submitBtn">
                                <i class="fas fa-save me-2"></i>
                                <span class="btn-text">Crear Categoría</span>
                                <div class="btn-loader d-none">
                                    <i class="fas fa-spinner fa-spin me-2"></i>Guardando...
                                </div>
                            </button>
                            <button type="button" class="btn btn-warning btn-action" onclick="resetForm()">
                                <i class="fas fa-undo me-2"></i>
                                Limpiar Formulario
                            </button>
                            <a href="{{ route('categorias.index') }}" class="btn btn-secondary btn-action">
                                <i class="fas fa-times me-2"></i>
                                Cancelar
                            </a>
                        </div>
                        <div class="form-footer-info mt-3">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt me-1"></i>
                                Los datos son validados automáticamente antes de guardar
                            </small>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Panel de Ayuda -->
        <div class="col-lg-4">
            <div class="card help-card sticky-top">
                <div class="card-header help-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-question-circle me-2"></i>
                        Ayuda y Consejos
                    </h6>
                </div>
                <div class="card-body">
                    <div class="help-section">
                        <h6 class="help-title">
                            <i class="fas fa-lightbulb text-warning me-2"></i>
                            Consejos para Nombres
                        </h6>
                        <ul class="help-list">
                            <li>Usa nombres descriptivos y claros</li>
                            <li>Evita caracteres especiales</li>
                            <li>Mantén la consistencia en el naming</li>
                        </ul>
                    </div>
                    
                    <div class="help-section">
                        <h6 class="help-title">
                            <i class="fas fa-info-circle text-info me-2"></i>
                            Sobre las Descripciones
                        </h6>
                        <ul class="help-list">
                            <li>Detalla qué productos incluye</li>
                            <li>Menciona características especiales</li>
                            <li>Ayuda a otros usuarios a clasificar</li>
                        </ul>
                    </div>

                    <div class="help-section">
                        <h6 class="help-title">
                            <i class="fas fa-cog text-secondary me-2"></i>
                            Estados de Categoría
                        </h6>
                        <div class="status-explanation">
                            <div class="status-item">
                                <span class="status-badge status-active">Activo</span>
                                <small>Visible en formularios de productos</small>
                            </div>
                            <div class="status-item">
                                <span class="status-badge status-inactive">Inactivo</span>
                                <small>Oculto temporalmente del sistema</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Estilos modernos para el formulario de creación de categorías */
.modern-container {
    padding: 20px;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
}

/* Hero Header */
.hero-header {
    position: relative;
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    opacity: 0.05;
}

.hero-content {
    position: relative;
    padding: 30px;
    z-index: 1;
}

.hero-icon {
    width: 70px;
    height: 70px;
    border-radius: 18px;
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: white;
    box-shadow: 0 8px 25px rgba(72, 187, 120, 0.3);
}

.hero-title {
    font-size: 32px;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 5px;
}

.hero-subtitle {
    font-size: 16px;
    color: #718096;
    margin-bottom: 0;
}

.btn-hero {
    padding: 12px 24px;
    border-radius: 12px;
    font-weight: 600;
    border: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.btn-hero:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}

/* Cards del formulario */
.form-card, .preview-card, .actions-card, .help-card {
    background: white;
    border: none;
    border-radius: 15px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.form-card:hover, .preview-card:hover {
    transform: translateY(-3px);
}

.form-header, .preview-header, .help-header {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border-bottom: 1px solid #e2e8f0;
    padding: 20px 25px;
    border-radius: 15px 15px 0 0;
}

.header-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
}

/* Secciones del formulario */
.form-section {
    margin-bottom: 30px;
}

.modern-label {
    display: flex;
    align-items: center;
    font-size: 14px;
    font-weight: 600;
    color: #4a5568;
    margin-bottom: 8px;
}

.modern-label.required::after {
    content: ' *';
    color: #e53e3e;
    font-weight: bold;
}

/* Inputs modernos */
.modern-input-group {
    position: relative;
    margin-bottom: 8px;
}

.modern-input-addon {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border: 2px solid #e2e8f0;
    border-right: none;
    color: #718096;
}

.modern-input {
    border: 2px solid #e2e8f0;
    border-left: none;
    padding: 12px 16px;
    font-size: 15px;
    transition: all 0.3s ease;
    background: #f8fafc;
}

.modern-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    background: white;
    outline: none;
}

.modern-input:focus + .input-feedback {
    opacity: 1;
}

.input-feedback {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

/* Select moderno */
.modern-select-wrapper {
    position: relative;
}

.modern-select {
    border: 2px solid #e2e8f0;
    padding: 12px 40px 12px 16px;
    font-size: 15px;
    background: #f8fafc;
    appearance: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.modern-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    background: white;
    outline: none;
}

.select-icon {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #718096;
    pointer-events: none;
}

/* Textarea moderno */
.modern-textarea-wrapper {
    position: relative;
}

.modern-textarea {
    border: 2px solid #e2e8f0;
    padding: 12px 16px;
    font-size: 15px;
    background: #f8fafc;
    resize: vertical;
    min-height: 120px;
    transition: all 0.3s ease;
}

.modern-textarea:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    background: white;
    outline: none;
}

.textarea-counter {
    position: absolute;
    bottom: 8px;
    right: 12px;
    font-size: 12px;
    color: #a0aec0;
    background: rgba(255,255,255,0.9);
    padding: 2px 6px;
    border-radius: 4px;
}

/* Hints del formulario */
.form-hint {
    color: #718096;
    font-size: 12px;
    margin-top: 5px;
    display: flex;
    align-items: center;
}

/* Vista previa */
.category-preview {
    background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 100%);
    padding: 20px;
    border-radius: 12px;
    border-left: 4px solid #667eea;
}

.preview-item {
    display: flex;
    align-items: flex-start;
}

.preview-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    margin-right: 15px;
    flex-shrink: 0;
}

.preview-content {
    flex: 1;
}

.preview-name {
    font-size: 18px;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 5px;
}

.preview-description {
    color: #4a5568;
    margin-bottom: 10px;
    font-size: 14px;
    line-height: 1.5;
}

.preview-status {
    margin-top: 10px;
}

.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
}

.status-active {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    color: white;
}

.status-inactive {
    background: linear-gradient(135deg, #a0aec0 0%, #718096 100%);
    color: white;
}

/* Botones de acción */
.btn-action {
    padding: 14px 28px;
    border-radius: 12px;
    font-weight: 600;
    border: none;
    font-size: 15px;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    min-width: 160px;
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.btn-action .btn-loader {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

/* Panel de ayuda */
.help-card {
    top: 20px;
}

.help-section {
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 1px solid #f1f5f9;
}

.help-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.help-title {
    font-size: 14px;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
}

.help-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.help-list li {
    padding: 6px 0;
    color: #4a5568;
    font-size: 13px;
    position: relative;
    padding-left: 20px;
}

.help-list li::before {
    content: '•';
    color: #667eea;
    position: absolute;
    left: 0;
    font-weight: bold;
}

.status-explanation .status-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
    padding: 8px;
    background: #f8fafc;
    border-radius: 6px;
}

.status-explanation small {
    color: #718096;
    font-size: 11px;
}

/* Alertas modernas */
.modern-alert {
    border: none;
    border-radius: 12px;
    padding: 20px;
    border-left: 4px solid;
    display: flex;
    align-items: flex-start;
}

.alert-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    font-size: 18px;
    flex-shrink: 0;
}

.alert-content {
    flex: 1;
}

/* Animaciones */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.form-card, .preview-card, .actions-card, .help-card {
    animation: fadeInUp 0.6s ease-out;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-content {
        padding: 20px;
    }
    
    .hero-title {
        font-size: 24px;
    }
    
    .hero-actions {
        margin-top: 15px;
    }
    
    .btn-action {
        width: 100%;
        margin-bottom: 10px;
    }
    
    .help-card {
        position: static;
        margin-top: 20px;
    }
}
</style>

<script>
// JavaScript para el formulario de creación de categorías
document.addEventListener('DOMContentLoaded', function() {
    // Referencias a elementos
    const nombreInput = document.getElementById('nombre');
    const descripcionInput = document.getElementById('descripcion');
    const activoSelect = document.getElementById('activo');
    const charCount = document.getElementById('charCount');
    const form = document.getElementById('categoryForm');
    const submitBtn = document.getElementById('submitBtn');
    
    // Vista previa en tiempo real
    function updatePreview() {
        const nombre = nombreInput.value || 'Nueva Categoría';
        const descripcion = descripcionInput.value || 'Sin descripción';
        const activo = activoSelect.value;
        
        document.getElementById('previewName').textContent = nombre;
        document.getElementById('previewDescription').textContent = descripcion;
        
        const statusElement = document.getElementById('previewStatus');
        if (activo === '1') {
            statusElement.innerHTML = '<span class="status-badge status-active"><i class="fas fa-check-circle me-1"></i>Activo</span>';
        } else {
            statusElement.innerHTML = '<span class="status-badge status-inactive"><i class="fas fa-times-circle me-1"></i>Inactivo</span>';
        }
    }
    
    // Contador de caracteres
    function updateCharCount() {
        const count = descripcionInput.value.length;
        charCount.textContent = count;
        
        if (count > 400) {
            charCount.style.color = '#e53e3e';
        } else if (count > 300) {
            charCount.style.color = '#d69e2e';
        } else {
            charCount.style.color = '#a0aec0';
        }
    }
    
    // Validación en tiempo real
    function validateField(field) {
        const value = field.value.trim();
        const inputGroup = field.closest('.form-section');
        
        if (field.hasAttribute('required') && !value) {
            field.classList.add('is-invalid');
            field.classList.remove('is-valid');
        } else if (value) {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
        } else {
            field.classList.remove('is-invalid', 'is-valid');
        }
    }
    
    // Event listeners
    if (nombreInput) {
        nombreInput.addEventListener('input', function() {
            updatePreview();
            validateField(this);
        });
        
        nombreInput.addEventListener('blur', function() {
            validateField(this);
        });
    }
    
    if (descripcionInput) {
        descripcionInput.addEventListener('input', function() {
            updatePreview();
            updateCharCount();
        });
        
        descripcionInput.addEventListener('blur', function() {
            validateField(this);
        });
    }
    
    if (activoSelect) {
        activoSelect.addEventListener('change', updatePreview);
    }
    
    // Envío del formulario
    if (form) {
        form.addEventListener('submit', function(e) {
            // Mostrar loader
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoader = submitBtn.querySelector('.btn-loader');
            
            btnText.classList.add('d-none');
            btnLoader.classList.remove('d-none');
            submitBtn.disabled = true;
            
            // Validar campos requeridos
            let isValid = true;
            const requiredFields = form.querySelectorAll('[required]');
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                btnText.classList.remove('d-none');
                btnLoader.classList.add('d-none');
                submitBtn.disabled = false;
                
                // Mostrar mensaje de error
                alert('Por favor, completa todos los campos requeridos.');
            }
        });
    }
    
    // Inicializar
    updatePreview();
    updateCharCount();
    
    // Animaciones al cargar
    const cards = document.querySelectorAll('.form-card, .preview-card, .actions-card, .help-card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 150);
    });
});

// Función para limpiar el formulario
function resetForm() {
    if (confirm('¿Estás seguro de que quieres limpiar todos los campos?')) {
        document.getElementById('categoryForm').reset();
        
        // Limpiar clases de validación
        const inputs = document.querySelectorAll('.modern-input, .modern-select, .modern-textarea');
        inputs.forEach(input => {
            input.classList.remove('is-valid', 'is-invalid');
        });
        
        // Actualizar vista previa
        document.getElementById('previewName').textContent = 'Nueva Categoría';
        document.getElementById('previewDescription').textContent = 'Sin descripción';
        document.getElementById('previewStatus').innerHTML = '<span class="status-badge status-active"><i class="fas fa-check-circle me-1"></i>Activo</span>';
        
        // Resetear contador
        document.getElementById('charCount').textContent = '0';
        document.getElementById('charCount').style.color = '#a0aec0';
        
        // Enfocar primer campo
        document.getElementById('nombre').focus();
    }
}

// Validación de caracteres especiales en el nombre
document.getElementById('nombre').addEventListener('keypress', function(e) {
    const char = e.key;
    const pattern = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s\-_]+$/;
    
    if (!pattern.test(char) && char !== 'Backspace' && char !== 'Delete') {
        e.preventDefault();
    }
});

// Auto-resize del textarea
document.getElementById('descripcion').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = Math.max(this.scrollHeight, 120) + 'px';
});
</script>
@endsection