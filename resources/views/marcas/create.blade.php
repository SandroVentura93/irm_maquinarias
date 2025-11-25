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
                    <h1 class="header-title">Crear Nueva Marca</h1>
                    <p class="header-subtitle">Completa la información para registrar una nueva marca</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario Moderno -->
    <div class="form-container">
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-icon">
                    <i class="fas fa-tag"></i>
                </div>
                <h2 class="form-title">Información de la Marca</h2>
            </div>

            <form action="{{ route('marcas.store') }}" method="POST" class="modern-form">
                @csrf
                
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
                            value="{{ old('nombre') }}"
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
                            <option value="1" selected>Activa</option>
                            <option value="0">Inactiva</option>
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
                    >{{ old('descripcion') }}</textarea>
                </div>

                <!-- Botones de Acción -->
                <div class="form-actions">
                    <a href="{{ route('marcas.index') }}" class="btn-cancel">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Guardar Marca
                    </button>
                </div>
            </form>
        </div>

        <!-- Panel de Ayuda -->
        <div class="help-panel">
            <div class="help-card">
                <div class="help-icon">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <h3 class="help-title">Consejos</h3>
                <ul class="help-list">
                    <li><i class="fas fa-check-circle"></i> El nombre de la marca debe ser único</li>
                    <li><i class="fas fa-check-circle"></i> Usa nombres cortos y descriptivos</li>
                    <li><i class="fas fa-check-circle"></i> La descripción es opcional pero recomendada</li>
                    <li><i class="fas fa-check-circle"></i> Puedes desactivar marcas que ya no uses</li>
                </ul>
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

    /* Contenedor del Formulario */
    .form-container {
        display: grid;
        grid-template-columns: 1fr 350px;
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
    }

    .form-title {
        color: white;
        font-size: 22px;
        font-weight: 600;
        margin: 0;
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

    /* Panel de Ayuda */
    .help-panel {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .help-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .help-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
        margin-bottom: 20px;
    }

    .help-title {
        font-size: 18px;
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
        padding: 12px 0;
        color: #4a5568;
        font-size: 14px;
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

    /* Responsive */
    @media (max-width: 992px) {
        .form-container {
            grid-template-columns: 1fr;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .help-panel {
            order: -1;
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