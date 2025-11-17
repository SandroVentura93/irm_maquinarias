@extends('layouts.dashboard')

@section('content')
<div class="container-fluid modern-container">
  <!-- Modern Header -->
  <div class="page-header mb-4">
    <div class="d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center">
        <div class="page-icon me-3">
          <i class="fas fa-user-plus text-primary"></i>
        </div>
        <div>
          <h2 class="page-title mb-0">Nuevo Cliente</h2>
          <p class="page-subtitle mb-0">Registra un nuevo cliente en el sistema</p>
        </div>
      </div>
      <div class="header-actions">
        <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary btn-modern me-2">
          <i class="fas fa-arrow-left me-2"></i>Regresar
        </a>
        <button type="submit" form="formCliente" class="btn btn-success btn-modern">
          <i class="fas fa-save me-2"></i>Guardar Cliente
        </button>
      </div>
    </div>
  </div>

  <form id="formCliente" action="{{ route('clientes.store') }}" method="POST">
    @csrf
    
    <!-- Información Personal -->
    <div class="card modern-card mb-4">
      <div class="card-header modern-header">
        <h5 class="card-title mb-0">
          <i class="fas fa-id-card me-2 text-primary"></i>
          Información Personal
        </h5>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-3">
            <label class="modern-label">
              <i class="fas fa-id-badge me-1"></i>
              Tipo Documento
            </label>
            <select name="tipo_documento" id="tipo_documento" class="form-select modern-select" onchange="validarTipoDocumento()">
              <option value="">Seleccione tipo</option>
              <option value="DNI">📱 DNI - Documento Nacional</option>
              <option value="RUC">🏢 RUC - Registro Único</option>
              <option value="PASAPORTE">✈️ Pasaporte</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="modern-label">
              <i class="fas fa-hashtag me-1"></i>
              Número Documento
            </label>
            <input type="text" name="numero_documento" id="numero_documento" class="form-control modern-input" 
                   placeholder="Ingrese número de documento" maxlength="20"
                   onblur="validarDocumento()">
            <div id="documento-info" class="form-text"></div>
          </div>
          <div class="col-md-6">
            <label class="modern-label">
              <i class="fas fa-user me-1"></i>
              Nombre / Razón Social
            </label>
            <input type="text" name="nombre" id="nombre" class="form-control modern-input" 
                   placeholder="Ingrese nombre completo o razón social" required>
          </div>
        </div>
      </div>
    </div>

    <!-- Información de Contacto -->
    <div class="card modern-card mb-4">
      <div class="card-header modern-header">
        <h5 class="card-title mb-0">
          <i class="fas fa-address-book me-2 text-success"></i>
          Información de Contacto
        </h5>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="modern-label">
              <i class="fas fa-map-marker-alt me-1"></i>
              Dirección
            </label>
            <textarea name="direccion" id="direccion" class="form-control modern-input" rows="2" 
                      placeholder="Ingrese dirección completa"></textarea>
          </div>
          <div class="col-md-3">
            <label class="modern-label">
              <i class="fas fa-phone me-1"></i>
              Teléfono
            </label>
            <input type="tel" name="telefono" id="telefono" class="form-control modern-input" 
                   placeholder="Ej: 999 888 777">
          </div>
          <div class="col-md-3">
            <label class="modern-label">
              <i class="fas fa-envelope me-1"></i>
              Correo Electrónico
            </label>
            <input type="email" name="correo" id="correo" class="form-control modern-input" 
                   placeholder="ejemplo@correo.com">
          </div>
        </div>
      </div>
    </div>

    <!-- Información Adicional -->
    <div class="card modern-card mb-4">
      <div class="card-header modern-header">
        <h5 class="card-title mb-0">
          <i class="fas fa-cog me-2 text-info"></i>
          Configuración Adicional
        </h5>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="modern-label">
              <i class="fas fa-map me-1"></i>
              Ubigeo (Ubicación Geográfica)
            </label>
            <select name="id_ubigeo" id="id_ubigeo" class="form-select modern-select">
              <option value="">🌍 Seleccione ubicación</option>
              @foreach ($ubigeos as $ubigeo)
                <option value="{{ $ubigeo->id_ubigeo }}">
                  📍 {{ $ubigeo->departamento }} - {{ $ubigeo->provincia }} - {{ $ubigeo->distrito }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <label class="modern-label">
              <i class="fas fa-toggle-on me-1"></i>
              Estado del Cliente
            </label>
            <select name="activo" id="activo" class="form-select modern-select">
              <option value="1">✅ Activo</option>
              <option value="0">❌ Inactivo</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="modern-label">
              <i class="fas fa-calendar me-1"></i>
              Fecha de Registro
            </label>
            <input type="text" class="form-control modern-input" value="{{ now()->format('d/m/Y') }}" readonly>
          </div>
        </div>
      </div>
      
      <!-- Footer con información -->
      <div class="card-footer modern-footer">
        <div class="d-flex align-items-center justify-content-between">
          <small class="text-muted">
            <i class="fas fa-info-circle me-1"></i>
            Los campos marcados son obligatorios para el registro del cliente
          </small>
          <div class="form-actions">
            <button type="reset" class="btn btn-outline-warning btn-sm me-2">
              <i class="fas fa-undo me-1"></i>Limpiar
            </button>
            <button type="submit" class="btn btn-success btn-sm">
              <i class="fas fa-check me-1"></i>Registrar Cliente
            </button>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>

<style>
/* Estilos modernos para el formulario */
.modern-container {
  padding: 20px;
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
  min-height: 100vh;
}

.page-header {
  background: white;
  padding: 25px;
  border-radius: 15px;
  box-shadow: 0 8px 32px rgba(0,0,0,0.1);
  border: 1px solid rgba(255,255,255,0.2);
}

.page-icon {
  width: 60px;
  height: 60px;
  border-radius: 15px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  color: white;
}

.page-title {
  font-size: 28px;
  font-weight: 700;
  color: #2d3748;
  margin: 0;
}

.page-subtitle {
  color: #718096;
  font-size: 16px;
  margin: 0;
}

.btn-modern {
  padding: 12px 24px;
  border-radius: 10px;
  font-weight: 600;
  transition: all 0.3s ease;
  border: none;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.btn-modern:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}

.modern-card {
  background: white;
  border: none;
  border-radius: 15px;
  box-shadow: 0 8px 32px rgba(0,0,0,0.1);
  overflow: hidden;
  transition: transform 0.3s ease;
}

.modern-card:hover {
  transform: translateY(-5px);
}

.modern-header {
  background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
  border-bottom: 1px solid #e2e8f0;
  padding: 20px 25px;
}

.modern-label {
  font-weight: 600;
  color: #4a5568;
  font-size: 14px;
  margin-bottom: 8px;
  display: flex;
  align-items: center;
}

.modern-input, .modern-select {
  border: 2px solid #e2e8f0;
  border-radius: 10px;
  padding: 12px 16px;
  font-size: 14px;
  transition: all 0.3s ease;
  background: #f8fafc;
}

.modern-input:focus, .modern-select:focus {
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
  background: white;
  outline: none;
}

.modern-footer {
  background: #f8fafc;
  border-top: 1px solid #e2e8f0;
  padding: 20px 25px;
}

.form-text {
  font-size: 12px;
  margin-top: 5px;
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

.modern-card {
  animation: fadeInUp 0.6s ease-out;
}

/* Responsive */
@media (max-width: 768px) {
  .page-header {
    padding: 20px;
  }
  
  .page-title {
    font-size: 24px;
  }
  
  .btn-modern {
    padding: 10px 20px;
    font-size: 14px;
  }
}
</style>

<script>
// Validación de tipo de documento
function validarTipoDocumento() {
  const tipo = document.getElementById('tipo_documento').value;
  const numeroInput = document.getElementById('numero_documento');
  const info = document.getElementById('documento-info');
  
  switch(tipo) {
    case 'DNI':
      numeroInput.setAttribute('maxlength', '8');
      numeroInput.setAttribute('placeholder', 'Ej: 12345678 (8 dígitos)');
      info.innerHTML = '<i class="fas fa-info-circle text-info"></i> DNI debe tener 8 dígitos';
      break;
    case 'RUC':
      numeroInput.setAttribute('maxlength', '11');
      numeroInput.setAttribute('placeholder', 'Ej: 20123456789 (11 dígitos)');
      info.innerHTML = '<i class="fas fa-info-circle text-info"></i> RUC debe tener 11 dígitos';
      break;
    case 'PASAPORTE':
      numeroInput.setAttribute('maxlength', '20');
      numeroInput.setAttribute('placeholder', 'Ej: ABC123456');
      info.innerHTML = '<i class="fas fa-info-circle text-info"></i> Formato alfanumérico';
      break;
    default:
      numeroInput.setAttribute('placeholder', 'Seleccione primero el tipo');
      info.innerHTML = '';
  }
}

// Validar documento al perder el foco
function validarDocumento() {
  const tipo = document.getElementById('tipo_documento').value;
  const numero = document.getElementById('numero_documento').value;
  const info = document.getElementById('documento-info');
  
  if (!tipo || !numero) return;
  
  let valido = false;
  let mensaje = '';
  
  switch(tipo) {
    case 'DNI':
      valido = /^\d{8}$/.test(numero);
      mensaje = valido ? 
        '<i class="fas fa-check-circle text-success"></i> DNI válido' : 
        '<i class="fas fa-times-circle text-danger"></i> DNI debe tener exactamente 8 dígitos';
      break;
    case 'RUC':
      valido = /^\d{11}$/.test(numero);
      mensaje = valido ? 
        '<i class="fas fa-check-circle text-success"></i> RUC válido' : 
        '<i class="fas fa-times-circle text-danger"></i> RUC debe tener exactamente 11 dígitos';
      break;
    case 'PASAPORTE':
      valido = numero.length >= 6;
      mensaje = valido ? 
        '<i class="fas fa-check-circle text-success"></i> Pasaporte válido' : 
        '<i class="fas fa-times-circle text-danger"></i> Pasaporte debe tener al menos 6 caracteres';
      break;
  }
  
  info.innerHTML = mensaje;
}

// Formato para teléfono
document.getElementById('telefono').addEventListener('input', function(e) {
  let valor = e.target.value.replace(/\D/g, '');
  if (valor.length <= 9) {
    valor = valor.replace(/(\d{3})(\d{3})(\d{3})/, '$1 $2 $3');
  }
  e.target.value = valor;
});

// Animación al cargar
document.addEventListener('DOMContentLoaded', function() {
  const cards = document.querySelectorAll('.modern-card');
  cards.forEach((card, index) => {
    setTimeout(() => {
      card.style.opacity = '1';
      card.style.transform = 'translateY(0)';
    }, index * 200);
  });
});
</script>
@endsection