@extends('layouts.dashboard')

@section('title', 'Editar Cliente')

@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --glass-bg: rgba(255, 255, 255, 0.25);
        --glass-border: rgba(255, 255, 255, 0.18);
        --text-primary: #2d3748;
        --text-secondary: #4a5568;
        --shadow-elegant: 0 8px 32px rgba(31, 38, 135, 0.37);
        --border-radius: 16px;
        --transition-smooth: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    body {
        background: var(--primary-gradient);
        min-height: 100vh;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    .edit-container {
        max-width: 900px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .edit-header {
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border);
        border-radius: var(--border-radius);
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-elegant);
        display: flex;
        align-items: center;
        justify-content: space-between;
        animation: slideInDown 0.6s ease-out;
    }

    .header-content h1 {
        color: white;
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .header-content h1 i {
        font-size: 2rem;
        opacity: 0.9;
    }

    .header-content p {
        color: rgba(255, 255, 255, 0.9);
        margin: 0.5rem 0 0 0;
        font-size: 1.1rem;
        font-weight: 300;
    }

    .btn-back {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 1rem 2rem;
        text-decoration: none;
        border-radius: 12px;
        font-weight: 600;
        transition: var(--transition-smooth);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
        text-decoration: none;
        transform: translateY(-2px);
    }

    .form-card {
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border);
        border-radius: var(--border-radius);
        padding: 3rem;
        box-shadow: var(--shadow-elegant);
        animation: slideInUp 0.6s ease-out 0.2s both;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }

    .form-section {
        margin-bottom: 2.5rem;
    }

    .section-title {
        color: white;
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 2rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid rgba(255, 255, 255, 0.2);
    }

    .section-title i {
        font-size: 1.6rem;
        opacity: 0.9;
    }

    .form-group {
        position: relative;
        margin-bottom: 2rem;
    }

    .form-label {
        display: block;
        color: white;
        font-weight: 600;
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .form-control, .form-select {
        width: 100%;
        padding: 1.25rem 1.25rem 1.25rem 3.5rem;
        background: rgba(255, 255, 255, 0.9);
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 500;
        color: var(--text-primary);
        transition: var(--transition-smooth);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
    }

    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: rgba(255, 255, 255, 0.6);
        background: rgba(255, 255, 255, 0.95);
        box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.1);
        transform: translateY(-2px);
    }

    .field-icon {
        position: absolute;
        top: 50%;
        left: 1.25rem;
        transform: translateY(-50%);
        color: rgba(102, 126, 234, 0.7);
        font-size: 1.2rem;
        z-index: 2;
        margin-top: 12px;
        transition: var(--transition-smooth);
    }

    .form-control:focus + .field-icon,
    .form-select:focus + .field-icon {
        color: #667eea;
        transform: translateY(-50%) scale(1.1);
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
    }

    .btn-primary {
        background: var(--success-gradient);
        color: white;
        padding: 1.25rem 3rem;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: var(--transition-smooth);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        box-shadow: 0 4px 20px rgba(79, 172, 254, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(79, 172, 254, 0.4);
    }

    .btn-secondary {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 1.25rem 2rem;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: var(--transition-smooth);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
        text-decoration: none;
        transform: translateY(-2px);
    }

    .status-toggle {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-top: 0.5rem;
    }

    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.3);
        transition: var(--transition-smooth);
        border-radius: 34px;
        border: 2px solid rgba(255, 255, 255, 0.2);
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 24px;
        width: 24px;
        left: 3px;
        bottom: 3px;
        background: white;
        transition: var(--transition-smooth);
        border-radius: 50%;
    }

    input:checked + .slider {
        background: var(--success-gradient);
        border-color: rgba(79, 172, 254, 0.5);
    }

    input:checked + .slider:before {
        transform: translateX(24px);
    }

    .status-label {
        color: white;
        font-weight: 600;
        font-size: 0.95rem;
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {
        .edit-header {
            flex-direction: column;
            text-align: center;
            gap: 1rem;
        }

        .header-content h1 {
            font-size: 2rem;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }
    }
</style>

<div class="edit-container">
    <!-- Header Elegante -->
    <div class="edit-header">
        <div class="header-content">
            <h1>
                <i class="fas fa-user-edit"></i>
                Editar Cliente
            </h1>
            <p>Actualiza la información del cliente {{ $cliente->nombre }}</p>
        </div>
        <a href="{{ route('clientes.show', $cliente->id_cliente) }}" class="btn-back">
            <i class="fas fa-eye"></i>
            Ver Cliente
        </a>
    </div>

    <!-- Formulario Principal -->
    <div class="form-card">
        <form action="{{ route('clientes.update', $cliente->id_cliente) }}" method="POST" id="editForm">
            @csrf
            @method('PUT')

            <!-- Información Personal -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-user"></i>
                    Información Personal
                </h3>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="tipo_documento" class="form-label">Tipo de Documento</label>
                        <select name="tipo_documento" id="tipo_documento" class="form-select" required>
                            <option value="">Seleccionar tipo</option>
                            <option value="DNI" {{ $cliente->tipo_documento == 'DNI' ? 'selected' : '' }}>DNI</option>
                            <option value="RUC" {{ $cliente->tipo_documento == 'RUC' ? 'selected' : '' }}>RUC</option>
                            <option value="PASAPORTE" {{ $cliente->tipo_documento == 'PASAPORTE' ? 'selected' : '' }}>PASAPORTE</option>
                        </select>
                        <i class="fas fa-id-card field-icon"></i>
                    </div>

                    <div class="form-group">
                        <label for="numero_documento" class="form-label">Número de Documento</label>
                        <input type="text" name="numero_documento" id="numero_documento" class="form-control" 
                               value="{{ $cliente->numero_documento }}" placeholder="Ingrese número de documento" required>
                        <i class="fas fa-hashtag field-icon"></i>
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label for="nombre" class="form-label">Nombre Completo</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" 
                               value="{{ $cliente->nombre }}" placeholder="Ingrese nombre completo" required>
                        <i class="fas fa-user field-icon"></i>
                    </div>
                </div>
            </div>

            <!-- Información de Contacto -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-address-book"></i>
                    Información de Contacto
                </h3>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="tel" name="telefono" id="telefono" class="form-control" 
                               value="{{ $cliente->telefono }}" placeholder="Ingrese teléfono">
                        <i class="fas fa-phone field-icon"></i>
                    </div>

                    <div class="form-group">
                        <label for="correo" class="form-label">Correo Electrónico</label>
                        <input type="email" name="correo" id="correo" class="form-control" 
                               value="{{ $cliente->correo }}" placeholder="Ingrese email">
                        <i class="fas fa-envelope field-icon"></i>
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" name="direccion" id="direccion" class="form-control" 
                               value="{{ $cliente->direccion }}" placeholder="Ingrese dirección completa">
                        <i class="fas fa-map-marker-alt field-icon"></i>
                    </div>

                    <div class="form-group">
                        <label for="id_ubigeo" class="form-label">Ubicación (Ubigeo)</label>
                        <select name="id_ubigeo" id="id_ubigeo" class="form-select">
                            <option value="">Seleccione ubicación</option>
                            @foreach ($ubigeos as $ubigeo)
                                <option value="{{ $ubigeo->id_ubigeo }}" {{ $cliente->id_ubigeo == $ubigeo->id_ubigeo ? 'selected' : '' }}>
                                    {{ $ubigeo->departamento }} - {{ $ubigeo->provincia }} - {{ $ubigeo->distrito }}
                                </option>
                            @endforeach
                        </select>
                        <i class="fas fa-map field-icon"></i>
                    </div>

                    <div class="form-group">
                        <label for="activo" class="form-label">Estado del Cliente</label>
                        <div class="status-toggle">
                            <label class="toggle-switch">
                                <input type="hidden" name="activo" value="0">
                                <input type="checkbox" name="activo" value="1" {{ $cliente->activo ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                            <span class="status-label">Cliente Activo</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="form-actions">
                <a href="{{ route('clientes.index') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Cancelar
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i>
                    Actualizar Cliente
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animaciones de entrada
    const formGroups = document.querySelectorAll('.form-group');
    formGroups.forEach((group, index) => {
        group.style.opacity = '0';
        group.style.transform = 'translateY(20px)';
        group.style.transition = 'all 0.6s ease';
        
        setTimeout(() => {
            group.style.opacity = '1';
            group.style.transform = 'translateY(0)';
        }, index * 100 + 300);
    });

    // Validación en tiempo real
    const form = document.getElementById('editForm');
    const inputs = form.querySelectorAll('.form-control, .form-select');

    inputs.forEach(input => {
        input.addEventListener('input', function() {
            validateField(this);
        });

        input.addEventListener('blur', function() {
            validateField(this);
        });
    });

    function validateField(field) {
        const value = field.value.trim();
        const isRequired = field.hasAttribute('required');
        
        // Reset styles
        field.style.borderColor = '';
        field.style.boxShadow = '';
        
        if (isRequired && !value) {
            field.style.borderColor = '#f56565';
            field.style.boxShadow = '0 0 0 4px rgba(245, 101, 101, 0.1)';
        } else if (value) {
            field.style.borderColor = '#48bb78';
            field.style.boxShadow = '0 0 0 4px rgba(72, 187, 120, 0.1)';
        }
    }

    // Efecto de envío del formulario
    form.addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('.btn-primary');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Actualizando...';
        submitBtn.disabled = true;
        
        // Si hay errores de validación, restaurar el botón
        setTimeout(() => {
            if (!form.checkValidity()) {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        }, 100);
    });

    // Efecto hover para los campos
    inputs.forEach(input => {
        input.addEventListener('mouseenter', function() {
            if (!this.matches(':focus')) {
                this.style.transform = 'translateY(-1px)';
            }
        });

        input.addEventListener('mouseleave', function() {
            if (!this.matches(':focus')) {
                this.style.transform = 'translateY(0)';
            }
        });
    });
});
</script>
@endsection