@extends('layouts.dashboard')

@section('content')
<div class="container-fluid modern-container">
  <!-- Modern Header -->
  <div class="page-header mb-4">
    <div class="d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center">
        <div class="page-icon me-3">
          <i class="fas fa-plus-circle"></i>
        </div>
        <div>
          <h2 class="page-title mb-0">Nueva Venta</h2>
          <p class="page-subtitle mb-0">Crea y gestiona comprobantes electrónicos</p>
        </div>
      </div>
      <div class="header-actions">
        <button type="button" class="btn btn-outline-secondary btn-modern me-2" onclick="window.history.back()">
          <i class="fas fa-arrow-left me-2"></i>Regresar
        </button>
        <button id="btnGuardar" type="button" class="btn btn-success btn-modern">
          <i class="fas fa-save me-2"></i>Registrar Venta
        </button>
      </div>
    </div>
  </div>

  <form id="formVenta">
    <!-- Información General -->
    <div class="card modern-card mb-4">
      <div class="card-header modern-header">
        <h5 class="card-title mb-0">
          <i class="fas fa-info-circle me-2 text-primary"></i>
          Información General
        </h5>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-3">
            <label class="modern-label">
              <i class="fas fa-calendar-alt me-1"></i>
              Fecha y Hora
            </label>
            <input type="datetime-local" id="fecha" class="form-control modern-input" value="{{ now()->format('Y-m-d\TH:i') }}">
          </div>
          <div class="col-md-3">
            <label class="modern-label">
              <i class="fas fa-file-invoice me-1"></i>
              Tipo Comprobante
            </label>
            <select id="tipo_comprobante" class="form-select modern-select" onchange="actualizarSerie(); validarCambioTipoComprobante();">
              <option value="">Seleccione tipo</option>
              <option value="Cotización">📝 Cotización</option>
              <option value="Factura">🧾 Factura</option>
              <option value="Boleta de Venta">🧾 Boleta de Venta</option>
              <option value="Ticket de Máquina Registradora">🎫 Ticket de Máquina Registradora</option>
            </select>
          </div>
          <div class="col-md-2">
            <label class="modern-label">
              <i class="fas fa-coins me-1"></i>
              Moneda
            </label>
            <select id="moneda" class="form-select modern-select" style="font-weight: 600;" onchange="calcularTotales();">
              <option value="USD" selected style="background-color: #d4edda; font-weight: bold; color: #155724;">$ Dólar (USD)</option>
              <option value="PEN" style="background-color: #fff3cd; font-weight: bold; color: #856404;">S/ Sol (PEN)</option>
            </select>
            <input type="hidden" id="monedaHidden" value="USD">
          </div>
          <div class="col-md-2">
            <label class="modern-label">
              <i class="fas fa-hashtag me-1"></i>
              Serie
            </label>
            <input id="serie" class="form-control modern-input" value="B001">
          </div>
          <div class="col-md-2">
            <label class="modern-label">
              <i class="fas fa-sort-numeric-up me-1"></i>
              Número
            </label>
            <input id="numero" class="form-control modern-input auto-number" value="Auto-generado" readonly>
          </div>
        </div>
      </div>
      <div class="card-footer modern-footer">
        <div class="tipo-cambio-info">
          <div class="d-flex align-items-center justify-content-between">
            <div class="tipo-cambio-display">
              <i class="fas fa-exchange-alt text-warning me-2"></i>
              <strong class="text-dark">Tipo de Cambio Manual:</strong>
              <input type="number" step="0.01" class="form-control d-inline-block w-auto" id="tipoCambioManual" name="tipoCambioManual" placeholder="Ingrese el tipo de cambio">
              <span class="text-muted ms-1">por USD</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Información Cliente -->
    <div class="card modern-card mb-4">
      <div class="card-header modern-header">
        <h5 class="card-title mb-0">
          <i class="fas fa-user-tie me-2 text-info"></i>
          Información del Cliente
        </h5>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-3">
            <label class="modern-label">
              <i class="fas fa-id-card me-1"></i>
              RUC/DNI Cliente
            </label>
            <div class="input-group modern-input-group">
              <input id="docCliente" class="form-control modern-input" placeholder="Ingrese RUC o DNI">
              <button class="btn btn-primary modern-btn" type="button" id="btnBuscarCliente">
                <i class="fas fa-search me-1"></i> Buscar
              </button>
            </div>
          </div>
          <div class="col-md-5">
            <label class="modern-label">
              <i class="fas fa-building me-1"></i>
              Nombre / Razón Social
            </label>
            <input id="nombreCliente" class="form-control modern-input readonly-input" readonly>
          </div>
          <div class="col-md-4">
            <label class="modern-label">
              <i class="fas fa-map-marker-alt me-1"></i>
              Dirección
            </label>
            <input id="direccionCliente" class="form-control modern-input readonly-input" readonly>
          </div>
        </div>
      </div>
    </div>

    <!-- Agregar Productos -->
    <div class="card modern-card mb-4">
      <div class="card-header modern-header">
        <h5 class="card-title mb-0">
          <i class="fas fa-box-open me-2 text-success"></i>
          Agregar Productos
        </h5>
      </div>
      <div class="card-body">
        <div class="row g-3 align-items-end">
          <div class="col-md-4">
            <label class="modern-label">
              <i class="fas fa-search me-1"></i>
              Producto
            </label>
            <div class="product-search-container">
              <input id="buscaProducto" class="form-control modern-input product-search" placeholder="🔍 Buscar producto...">
              <div id="listaProductos" class="product-dropdown" style="display:none;"></div>
            </div>
          </div>
          <div class="col-md-2">
            <label class="modern-label">
              <i class="fas fa-sort-numeric-up me-1"></i>
              Cantidad
            </label>
            <input id="cantidad" class="form-control modern-input text-center" type="number" min="0.01" value="1">
          </div>
          <div class="col-md-2">
            <label class="modern-label">
              <i class="fas fa-tag me-1"></i>
              Precio Unit.
            </label>
            <div class="input-group">
              <span class="input-group-text" id="precioSimbolo">S/</span>
              <input id="precio" class="form-control modern-input" type="number" step="0.01" placeholder="Ingrese el precio">
            </div>
          </div>
          <div class="col-md-2">
            <label class="modern-label">
              <i class="fas fa-percent me-1"></i>
              Descuento %
            </label>
            <input id="descuento" class="form-control modern-input text-center" type="number" min="0" value="0">
          </div>
          <div class="col-md-2">
            <button id="agregar" class="btn btn-success modern-btn w-100" type="button">
              <i class="fas fa-plus me-1"></i> Agregar
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Detalle de Venta -->
    <div class="card modern-card mb-4">
      <div class="card-header modern-header">
        <h5 class="card-title mb-0">
          <i class="fas fa-list-alt me-2 text-primary"></i>
          Detalle de la Venta
        </h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table modern-table" id="tablaDetalle">
            <thead class="table-header">
              <tr>
                <th style="width: 40%;">
                  <i class="fas fa-box me-1"></i> Producto
                </th>
                <th style="width: 10%;" class="text-center">
                  <i class="fas fa-sort-numeric-up me-1"></i> Cantidad
                </th>
                <th style="width: 25%;" class="text-center">
                  <i class="fas fa-tag me-1"></i> <span id="colPrecioLabel">Precio Unitario (S/)</span>
                </th>
                <th style="width: 10%;" class="text-center">
                  <i class="fas fa-percent me-1"></i> Desc%
                </th>
                <th style="width: 15%;" class="text-end">
                  <i class="fas fa-calculator me-1"></i> Subtotal
                </th>
                <th style="width: 5%;" class="text-center">
                  <i class="fas fa-edit me-1"></i> Editar
                </th>
              </tr>
            </thead>
          </table>
          
          <!-- Empty State -->
          <div id="emptyTableState" class="empty-table-state text-center py-5">
            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No hay productos agregados</h5>
            <p class="text-muted mb-0">Busca y agrega productos para continuar con la venta</p>
          </div>
        </div>
        
        <!-- Summary Section -->
        <div class="row mt-4">
          <div class="col-md-6">
            <div class="summary-info p-3 bg-light rounded">
              <i class="fas fa-info-circle text-info me-2"></i>
              <span class="text-muted" id="mensajeMoneda">Los precios se muestran en la moneda seleccionada</span>
            </div>
          </div>
          <div class="col-md-6">
            <div class="summary-totals p-3 bg-gradient-light rounded">
              <div class="summary-row">
                <span class="summary-label">
                  <i class="fas fa-calculator me-1 text-info"></i> Subtotal:
                </span>
                <div class="summary-amounts">
                  <span class="amount-display" id="subtotalDisplay" style="font-size: 1.2em; font-weight: 600; color: #333;"></span>
                </div>
              </div>
              
              <div class="summary-row">
                <span class="summary-label">
                  <i class="fas fa-percent me-1 text-warning"></i> IGV (18%):
                  <div class="form-check form-switch d-inline-block ms-2" style="vertical-align: middle;">
                    <input class="form-check-input" type="checkbox" id="incluirIGV" checked onchange="calcularTotales()">
                    <label class="form-check-label small" for="incluirIGV" style="font-size: 0.85em;">Incluir</label>
                  </div>
                </span>
                <div class="summary-amounts">
                  <span class="amount-display" id="igvDisplay" style="font-size: 1.2em; font-weight: 600; color: #333;"></span>
                </div>
              </div>
              
              <hr class="my-2">
              
              <div class="summary-row total-row">
                <span class="summary-label fw-bold" style="font-size: 1.1em;">
                  <i class="fas fa-money-bill-wave me-1 text-success"></i> TOTAL:
                </span>
                <div class="summary-amounts">
                  <span class="amount-display" id="totalDisplay" style="font-size: 1.8em; font-weight: bold; color: #28a745; text-shadow: 1px 1px 2px rgba(0,0,0,0.1);"></span>
                </div>
              </div>
              
              <!-- Hidden fields for calculations -->
              <div style="display: none;">
                <span id="subTotal">0.00</span>
                <span id="subTotalUSD">0.00</span>
                <span id="igv">0.00</span>
                <span id="igvUSD">0.00</span>
                <span id="total">0.00</span>
                <span id="totalUSD">0.00</span>
              </div>
              <!-- Opción para mostrar código / número de parte en el PDF de cotización -->
              <div id="mostrarCodigoParteContainer" style="display:none; margin-top:10px;">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="mostrarCodigoParteCheckbox" checked>
                  <label class="form-check-label" for="mostrarCodigoParteCheckbox" style="font-size:0.95em;">Mostrar <strong>número de parte</strong> y <strong>código</strong> de producto en la cotización</label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>

<!-- Modal para registrar nuevo cliente con todos los campos habilitados -->
<div class="modal fade" id="modalRegistrarCliente" tabindex="-1" aria-labelledby="modalRegistrarClienteLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalRegistrarClienteLabel">Registrar Nuevo Cliente</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formRegistrarCliente">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="nuevoTipoDocumento" class="form-label">Tipo de Documento</label>
                <select class="form-select" id="nuevoTipoDocumento" required>
                  <option value="DNI">DNI</option>
                  <option value="RUC">RUC</option>
                  <option value="PASAPORTE">Pasaporte</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="nuevoNumeroDocumento" class="form-label">Número de Documento</label>
                <input type="text" class="form-control" id="nuevoNumeroDocumento" required>
              </div>
              <div class="mb-3">
                <label for="nuevoNombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nuevoNombre">
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="nuevoDireccion" class="form-label">Dirección</label>
                <input type="text" class="form-control" id="nuevoDireccion">
              </div>
              <div class="mb-3">
                <label for="nuevoUbigeo" class="form-label">Ubigeo</label>
                <select class="form-select" id="nuevoUbigeo">
                  <option value="">Seleccione un Ubigeo</option>
                  @foreach($ubigeos as $ubigeo)
                    <option value="{{ $ubigeo->id_ubigeo }}">📍 {{ $ubigeo->departamento }} - {{ $ubigeo->provincia }} - {{ $ubigeo->distrito }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label for="nuevoTelefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="nuevoTelefono">
              </div>
              <div class="mb-3">
                <label for="nuevoCorreo" class="form-label">Correo</label>
                <input type="email" class="form-control" id="nuevoCorreo">
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="nuevoActivo" checked>
                <label class="form-check-label" for="nuevoActivo">
                  Activo
                </label>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnGuardarCliente">Guardar Cliente</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal para edición de cotización -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar Cotización</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="editProducto" class="form-label">Producto</label>
                        <input type="text" class="form-control" id="editProducto" readonly>
                      </div>
                      <div class="mb-3">
                        <label for="editCantidad" class="form-label">Cantidad</label>
                        <input type="number" class="form-control" id="editCantidad">
                      </div>
                      <div class="mb-3">
                        <label for="editPrecioUnitario" class="form-label">Precio Unitario</label>
                        <input type="number" class="form-control" id="editPrecioUnitario">
                      </div>
                      <div class="mb-3">
                        <label for="editDescuento" class="form-label">Descuento</label>
                        <input type="number" class="form-control" id="editDescuento">
                      </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveEdit">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

<!-- Ajustar estilo del ícono de edición para asegurar visibilidad -->
<style>
  .edit-icon {
    cursor: pointer;
    color: #007bff;
    font-size: 1.2em;
  }
</style>

<script>
  // Inicializaciones seguras
  window.detalle = Array.isArray(window.detalle) ? window.detalle : [];
  window.productoSeleccionado = window.productoSeleccionado || null;
// === VARIABLES ===
const IGV = 0.18;
let TIPO_CAMBIO = 3.80;
let detalle = [];
let productoSeleccionado = null;
let clienteSeleccionado = null;

// === VALIDACIÓN DE COMPATIBILIDAD COMPROBANTE/CLIENTE (GLOBAL) ===
// Evita ReferenceError y agrega reglas básicas según documento y tipo
function validarCompatibilidadComprobanteCliente() {
  const tipo = document.getElementById('tipo_comprobante')?.value || '';
  const cliente = clienteSeleccionado;
  if (!tipo) {
    return { valido: false, advertencia: false, mensaje: 'Seleccione el tipo de comprobante.' };
  }
  if (!cliente) {
    return { valido: false, advertencia: false, mensaje: 'Debe seleccionar un cliente antes de registrar.' };
  }
  // Reglas comunes en Perú: Factura requiere RUC (11 dígitos), Boleta permite DNI
  const doc = (cliente.numero_documento || cliente.documento || '').toString();
  const esRUC = /^\d{11}$/.test(doc);
  const esDNI = /^\d{8}$/.test(doc);
  if (tipo === 'Factura' && !esRUC) {
    return { valido: false, advertencia: false, mensaje: 'Para Factura, el cliente debe tener RUC (11 dígitos).' };
  }
  if (tipo === 'Boleta de Venta' && !esDNI && !esRUC) {
    return { valido: true, advertencia: true, mensaje: 'Boleta sin DNI/RUC detectado. Se continuará, pero verifique el documento.' };
  }
  // Cotización y Ticket, no restricción estricta
  return { valido: true, advertencia: false, mensaje: '' };
}

// === FUNCIONES DE CONVERSIÓN DE MONEDA ===
function formatearPrecio(precio) {
  const precioSoles = parseFloat(precio);
  const monedaSeleccionada = document.getElementById('moneda')?.value || 'USD';
  if (monedaSeleccionada === 'USD') {
    const precioDolares = precioSoles / TIPO_CAMBIO;
    return `$ ${precioDolares.toFixed(2)}`;
  }
  return `S/ ${precioSoles.toFixed(2)}`;
}

function convertirSolesADolares(soles) {
  return parseFloat(soles) / TIPO_CAMBIO;
}

function convertirDolaresASoles(dolares) {
  return parseFloat(dolares) * TIPO_CAMBIO;
}


// Función auxiliar para mostrar alertas
function mostrarAlerta(tipo, mensaje) {
  const alertClass = tipo === 'success' ? 'alert-success' : 'alert-danger';
  const icon = tipo === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
  
  const alertHtml = `
    <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
      <i class="${icon}"></i> ${mensaje}
      <button type="button" class="close" data-dismiss="alert">
        <span>&times;</span>
      </button>
    </div>`;
  
  const container = document.querySelector('.container');
  container.insertAdjacentHTML('afterbegin', alertHtml);
  
  // Auto-remove after 5 seconds
  setTimeout(() => {
    const alert = container.querySelector('.alert');
    if (alert) alert.remove();
  }, 5000);
}

// === CONFIGURACIÓN DE SERIES POR TIPO DE COMPROBANTE ===
// Configuración completa de todos los tipos de comprobantes registrados en la base de datos
// Cada tipo tiene su serie y prefijo correspondiente según SUNAT
const configSeries = {
  // Documentos principales de venta
  'Cotización': { 
    serie: 'COT', 
    prefijo: 'COT-', 
    codigo_sunat: 'CT',
    descripcion: 'Documento pre-venta sin valor tributario'
  },
  'Factura': { 
    serie: 'F001', 
    prefijo: 'F001-', 
    codigo_sunat: '01',
    descripcion: 'Comprobante de pago para empresas con RUC'
  },
  'Boleta de Venta': { 
    serie: 'B001', 
    prefijo: 'B001-', 
    codigo_sunat: '03',
    descripcion: 'Comprobante de pago para personas naturales'
  },
  
  // Documentos de ajuste
  'Nota de Crédito': { 
    serie: 'NC01', 
    prefijo: 'NC01-', 
    codigo_sunat: '07',
    descripcion: 'Documento para anular o reducir el valor de un comprobante'
  },
  'Nota de Débito': { 
    serie: 'ND01', 
    prefijo: 'ND01-', 
    codigo_sunat: '08',
    descripcion: 'Documento para aumentar el valor de un comprobante'
  },
  
  // Documentos auxiliares
  'Guía de Remisión': { 
    serie: 'T001', 
    prefijo: 'T001-', 
    codigo_sunat: '09',
    descripcion: 'Documento para sustentar el traslado de bienes'
  },
  'Ticket de Máquina Registradora': { 
    serie: 'TK01', 
    prefijo: 'TK01-', 
    codigo_sunat: '12',
    descripcion: 'Comprobante emitido por máquina registradora'
  },
  'Recibo por Honorarios': { 
    serie: 'H001', 
    prefijo: 'H001-', 
    codigo_sunat: '14',
    descripcion: 'Comprobante por servicios profesionales independientes'
  }
};

// === NO HAY SUGERENCIAS AUTOMÁTICAS - SOLO LO QUE EL USUARIO ELIJE ===

// === TIPO DE CAMBIO MANUAL ===
function aplicarTipoCambioManual() {
  const input = document.getElementById('tipoCambioManual');
  const valor = parseFloat(input?.value);
  if (!isNaN(valor) && valor > 0) {
    TIPO_CAMBIO = valor;
    mostrarAlerta('success', `Tipo de cambio aplicado manualmente: S/ ${valor.toFixed(4)} por USD`);
    // Recalcular precios mostrados en la lista de productos
    const productosItems = document.querySelectorAll('.prod-item');
    productosItems.forEach(item => {
      const precio = parseFloat(item.dataset.precio);
      const precioContainer = item.querySelector('.fs-6');
      if (precioContainer) {
        precioContainer.innerHTML = formatearPrecio(precio);
      }
    });
    // Recalcular totales si hay detalles
    if (detalle.length > 0) {
      actualizarTabla();
    }
  }
}

document.addEventListener('DOMContentLoaded', () => {
  const inputTC = document.getElementById('tipoCambioManual');
  if (inputTC) {
    inputTC.addEventListener('input', aplicarTipoCambioManual);
    if (!inputTC.value) {
      inputTC.value = (typeof TIPO_CAMBIO !== 'undefined' && TIPO_CAMBIO > 0) ? TIPO_CAMBIO : 3.80;
    }
  }
});
// Función de compatibilidad llamada desde el atributo onchange del select
function validarCambioTipoComprobante() {
  if (typeof actualizarSerie === 'function') {
    actualizarSerie();
  }
}

// === BUSCAR CLIENTE (MEJORADO) ===
document.getElementById('btnBuscarCliente').addEventListener('click', async () => {
  const doc = document.getElementById('docCliente').value.trim();
  
  if (!doc) {
    alert('Por favor, ingrese RUC o DNI');
    return;
  }

  // Validar longitud mínima
  if (doc.length < 8) {
    alert('El número de documento debe tener al menos 8 dígitos');
    return;
  }

  try {
    const btn = document.getElementById('btnBuscarCliente');
    const originalText = btn.innerHTML;
    
    // Mostrar indicador de carga
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Buscando...';
    btn.disabled = true;

    // Usar la nueva ruta pública
    const res = await fetch(`/api/clientes/search?doc=${encodeURIComponent(doc)}`);
    
    if (!res.ok) {
      throw new Error(`HTTP ${res.status}: ${res.statusText}`);
    }
    
    const data = await res.json();
    console.log('Respuesta búsqueda cliente:', data);
    
    if (data.found) {
      const c = data.cliente;
      clienteSeleccionado = c;
      
      // Llenar campos con información del cliente
      document.getElementById('nombreCliente').value = c.nombre || 'Sin nombre';
      document.getElementById('direccionCliente').value = c.direccion || 'Sin dirección';
      
      // === NO HAY SUGERENCIAS AUTOMÁTICAS ===
      
      // Mostrar mensaje de éxito
      const alert = document.createElement('div');
      alert.className = 'alert alert-success mt-2';
      alert.innerHTML = `<i class="fas fa-check"></i> Cliente encontrado: ${c.nombre}`;
      document.getElementById('docCliente').parentNode.appendChild(alert);
      
      // Quitar el mensaje después de 3 segundos
      setTimeout(() => alert.remove(), 3000);
      
    } else {
      // Cliente no encontrado, abrir modal
      mostrarModalRegistrarCliente();
      clienteSeleccionado = null;
      
      // Limpiar campos
      document.getElementById('nombreCliente').value = '';
      document.getElementById('direccionCliente').value = '';
    }
    
  } catch (error) {
    console.error('Error en búsqueda de cliente:', error);
    alert(`Error al buscar cliente: ${error.message}`);
    clienteSeleccionado = null;
  } finally {
    // Restaurar botón
    const btn = document.getElementById('btnBuscarCliente');
    btn.innerHTML = 'Buscar';
    btn.disabled = false;
  }
});

// === BUSCAR PRODUCTO (OPTIMIZADO) ===
const inputProd = document.getElementById('buscaProducto');
const lista = document.getElementById('listaProductos');
let timeoutId = null;

inputProd.addEventListener('input', async () => {
  const q = inputProd.value.trim();
  
  // Limpiar timeout anterior
  if (timeoutId) clearTimeout(timeoutId);
  
  if (q.length < 2) { 
    lista.style.display = 'none'; 
    return; 
  }
  
  // Debounce para evitar muchas requests
  timeoutId = setTimeout(async () => {
    try {
      // Mostrar indicador de carga
      lista.innerHTML = '<div class="p-2 text-center text-muted"><i class="fas fa-spinner fa-spin"></i> Buscando...</div>';
      lista.style.display = 'block';
      
      // Nueva ruta pública sin autenticación
      const res = await fetch(`/api/productos/search?q=${encodeURIComponent(q)}`);
      
      if (!res.ok) {
        throw new Error(`HTTP ${res.status}: ${res.statusText}`);
      }
      
      const items = await res.json();
      console.log('Productos encontrados:', items);
      
      if (items.length === 0) {
        lista.innerHTML = '<div class="p-2 text-center text-muted">No se encontraron productos</div>';
        return;
      }
      
      // Mostrar productos con información completa
      lista.innerHTML = items.map(it => 
        `<div class='p-3 border-bottom prod-item' 
              data-id='${it.id_producto}' 
              data-codigo='${it.codigo || ''}'
              data-numero-parte='${it.numero_parte || ''}'
              data-desc='${it.descripcion || ''}' 
              data-precio='${it.precio_venta || it.precio_compra || 0}'
              data-stock='${it.stock_actual || 0}'
              data-modelo='${it.modelo || ''}'
              data-ubicacion='${it.ubicacion || ''}'
              data-peso='${it.peso || 0}'
              data-minimo='${it.stock_minimo || 0}'
              data-importado='${it.importado || ''}'
              data-activo='${it.activo || ''}'
              style="cursor: pointer; transition: background-color 0.2s;">
          <div class="row">
            <div class="col-8">
              <div class="fw-bold text-primary">
                ${it.codigo || 'SIN CÓDIGO'} 
                ${it.numero_parte ? '<span class="text-muted">| ' + it.numero_parte + '</span>' : ''}
              </div>
              <div class="text-dark">${it.descripcion || ''}</div>
              ${it.modelo ? '<small class="text-muted">Modelo: ' + it.modelo + '</small><br>' : ''}
              <small class="text-info">
                <i class="fas fa-tags"></i> ${it.categoria || 'Sin categoría'} | 
                <i class="fas fa-copyright"></i> ${it.marca || 'Sin marca'} |
                <i class="fas fa-truck"></i> ${it.proveedor || 'Sin proveedor'}
              </small>
              <br>
              <small class="text-secondary">Peso: ${it.peso || 0} kg</small>
            </div>
            <div class="col-4 text-end">
              <div class="fw-bold text-success fs-6">${formatearPrecio(it.precio_venta || it.precio_compra || 0)}</div>
              <small class="text-muted d-block">T.C: S/ ${TIPO_CAMBIO.toFixed(2)}</small>
              <small class="d-block ${it.stock_status === 'Bajo' ? 'text-danger' : 'text-success'}">
                <i class="fas fa-boxes"></i> Stock: ${it.stock_actual || 0}
                ${it.stock_status === 'Bajo' ? '<i class="fas fa-exclamation-triangle text-warning"></i>' : ''}
              </small>
              <small class="text-muted">Mínimo: ${it.stock_minimo || 0}</small>
              ${it.ubicacion !== 'Sin ubicación' ? '<small class="text-muted"><i class="fas fa-map-marker-alt"></i> ' + it.ubicacion + '</small>' : ''}
              ${it.importado === 'Sí' ? '<br><span class="badge badge-info">Importado</span>' : ''}
              <br>
              <small class="text-muted">Estado: ${it.activo || ''}</small>
            </div>
          </div>
        </div>`
      ).join('');
      
      // Agregar eventos a cada producto
      document.querySelectorAll('.prod-item').forEach(p => {
        // Click para seleccionar
        p.addEventListener('click', () => {
          productoSeleccionado = {
            id: p.dataset.id, 
            codigo: p.dataset.codigo,
            numero_parte: p.dataset.numeroParte,
            desc: p.dataset.desc, 
            precio: parseFloat(p.dataset.precio),
            stock: parseInt(p.dataset.stock),
            modelo: p.dataset.modelo,
            ubicacion: p.dataset.ubicacion
          };
          
          // Mostrar información completa en el campo
          const textoCompleto = [
            productoSeleccionado.codigo,
            productoSeleccionado.numero_parte,
            productoSeleccionado.desc,
            productoSeleccionado.modelo
          ].filter(Boolean).join(' - ');
          
          inputProd.value = textoCompleto;
          // Ajustar el precio al valor visible según la moneda seleccionada
          (function(){
            const monedaUI = document.getElementById('moneda')?.value || 'USD';
            const valor = parseFloat(productoSeleccionado.precio) || 0;
            const valorUI = (monedaUI === 'USD') ? (valor / TIPO_CAMBIO) : valor;
            document.getElementById('precio').value = Number(valorUI).toFixed(2);
          })();
          lista.style.display = 'none';
          
          // Focus en cantidad
          document.getElementById('cantidad').focus();
          
          // Log para debug
          console.log('Producto seleccionado:', productoSeleccionado);
        });
        
        // Hover effects
        p.addEventListener('mouseenter', () => p.style.backgroundColor = '#f8f9fa');
        p.addEventListener('mouseleave', () => p.style.backgroundColor = '');
      });
      
    } catch (error) {
      console.error('Error en búsqueda:', error);
      lista.innerHTML = `<div class="p-2 text-danger text-center">Error: ${error.message}</div>`;
    }
  }, 300); // Esperar 300ms antes de buscar
});

// === AGREGAR PRODUCTO ===
document.getElementById('agregar').addEventListener('click',()=>{
  const cant = parseFloat(document.getElementById('cantidad').value);
  const desc = parseFloat(document.getElementById('descuento').value);
  const precioIngresado = parseFloat(document.getElementById('precio').value);
  const textoProducto = document.getElementById('buscaProducto').value.trim();

  console.log('[AGREGAR] Datos capturados', { textoProducto, productoSeleccionado, cant, desc, precioIngresado });

  if (!textoProducto && !productoSeleccionado) {
    alert('Ingrese o seleccione un producto');
    return;
  }

  // Determinar datos del producto: seleccionado desde la lista o ingresado manualmente
  const idProd = productoSeleccionado ? productoSeleccionado.id : null;
  const descProd = productoSeleccionado ? productoSeleccionado.desc : textoProducto;
  const precioBase = productoSeleccionado ? productoSeleccionado.precio : 0; // Precio base asumido en PEN (catálogo)
  // Detectar moneda del precio mostrado en la UI: según símbolo de la columna o selección global
  const simboloActual = document.getElementById('precioSimbolo')?.textContent?.trim() || 'S/';
  const monedaPrecio = simboloActual.includes('$') ? 'USD' : 'PEN';
  // Si no se ingresó un precio manual, convertir el precio base del catálogo a la moneda visible
  let precio = !isNaN(precioIngresado) ? precioIngresado : precioBase;
  if (isNaN(precioIngresado)) {
    precio = (monedaPrecio === 'USD') ? (precioBase / TIPO_CAMBIO) : precioBase;
  }
  console.log('[AGREGAR] Producto resuelto', { idProd, descProd, precioBase, precio });

  if (isNaN(cant) || cant <= 0) {
    alert('Ingrese una cantidad válida');
    return;
  }
  if (isNaN(precio) || precio < 0) {
    alert('Ingrese un precio válido');
    return;
  }

  // Calcular equivalentes en PEN para mantener un único origen de verdad
  const descuento = isNaN(desc) ? 0 : desc;
  const precioUnitPen = (monedaPrecio === 'USD') ? (precio * TIPO_CAMBIO) : precio;
  const subtotalPen = precioUnitPen * (1 - (descuento / 100)) * cant;

  detalle.push({
    id_producto: idProd,
    descripcion: descProd,
    cantidad: cant,
    // Precio ingresado y su moneda original (para el backend)
    precio_unitario: precio,
    moneda_precio: monedaPrecio,
    descuento_porcentaje: descuento,
    // Campos calculados en PEN para totales/visualización coherente
    precio_unitario_pen: precioUnitPen,
    subtotal_pen: subtotalPen
  });
  console.log('[AGREGAR] Detalle actualizado', detalle);

  renderTabla();
  productoSeleccionado=null;
  inputProd.value='';
  document.getElementById('precio').value='';
  document.getElementById('cantidad').value='1';
  document.getElementById('descuento').value='0';
});

// === RENDERIZAR DETALLE ===
// Agregar ícono de edición en cada fila al renderizar la tabla
function renderTabla(){
  const table = document.getElementById('tablaDetalle');
  if (!table) {
    console.error('No se encontró la tabla con id "tablaDetalle"');
    return;
  }
  let tbody = table.querySelector('tbody');
  if (!tbody) {
    console.warn('No había tbody; creando uno nuevo');
    tbody = document.createElement('tbody');
    tbody.className = 'table-body';
    table.appendChild(tbody);
  }
  tbody.innerHTML='';
  let subtotalPen=0;
  const monedaSeleccionada = document.getElementById('moneda')?.value || 'USD';
  detalle.forEach((d,i)=>{
    const precioPen = (typeof d.precio_unitario_pen === 'number') ? d.precio_unitario_pen : ((d.moneda_precio === 'USD') ? d.precio_unitario * TIPO_CAMBIO : d.precio_unitario);
    const subPen = (typeof d.subtotal_pen === 'number') ? d.subtotal_pen : (precioPen * (1 - (Number(d.descuento_porcentaje||0)/100)) * d.cantidad);
    subtotalPen += subPen;
    const tr=document.createElement('tr');
    const precioUSD = (precioPen / TIPO_CAMBIO).toFixed(2);
    const subtotalUSD = (subPen / TIPO_CAMBIO).toFixed(2);
    const precioDisplay = monedaSeleccionada === 'USD' ? `$ ${precioUSD}` : `S/ ${precioPen.toFixed(2)}`;
    const subtotalDisplay = monedaSeleccionada === 'USD' ? `$ ${subtotalUSD}` : `S/ ${subPen.toFixed(2)}`;
    tr.innerHTML=`
      <td>${d.descripcion}</td>
      <td>${d.cantidad}</td>
      <td class="precio-unitario" data-pen="${precioPen.toFixed(2)}" data-usd="${precioUSD}">${precioDisplay}</td>
      <td>${d.descuento_porcentaje}%</td>
      <td class="subtotal" data-pen="${subPen.toFixed(2)}" data-usd="${subtotalUSD}">${subtotalDisplay}</td>
      <td class="text-center">
        <i class="fas fa-edit edit-icon" style="cursor: pointer; color: #007bff;"></i>
      </td>`;
    tbody.appendChild(tr);
  });
  
  calcularTotales();

  // Ocultar/mostrar estado vacío
  const emptyState = document.getElementById('emptyTableState');
  if (emptyState) {
    emptyState.style.display = detalle.length > 0 ? 'none' : '';
  }
}

// === CALCULAR TOTALES (con IGV opcional) ===
function calcularTotales() {
  const tbody = document.querySelector('#tablaDetalle tbody');
  let subtotalPen = 0;
  detalle.forEach((d) => {
    const precioPen = (typeof d.precio_unitario_pen === 'number') ? d.precio_unitario_pen : ((d.moneda_precio === 'USD') ? d.precio_unitario * TIPO_CAMBIO : d.precio_unitario);
    const subPen = (typeof d.subtotal_pen === 'number') ? d.subtotal_pen : (precioPen * (1 - (Number(d.descuento_porcentaje||0)/100)) * d.cantidad);
    subtotalPen += subPen;
  });
  
  // Verificar si el checkbox de IGV está marcado
  const incluirIGV = document.getElementById('incluirIGV').checked;
  const igv = incluirIGV ? subtotalPen * IGV : 0;
  const total = subtotalPen + igv;
  
  // Actualizar totales en soles (campos ocultos)
  document.getElementById('subTotal').textContent = subtotalPen.toFixed(2);
  document.getElementById('igv').textContent = igv.toFixed(2);
  document.getElementById('total').textContent = total.toFixed(2);
  
  // Actualizar totales en dólares (campos ocultos)
  document.getElementById('subTotalUSD').textContent = (subtotalPen / TIPO_CAMBIO).toFixed(2);
  document.getElementById('igvUSD').textContent = (igv / TIPO_CAMBIO).toFixed(2);
  document.getElementById('totalUSD').textContent = (total / TIPO_CAMBIO).toFixed(2);
  
  // Actualizar displays visibles según la moneda seleccionada
  const monedaSeleccionada = document.getElementById('moneda').value;
  
  if (monedaSeleccionada === 'USD') {
    // Mostrar en dólares
    document.getElementById('subtotalDisplay').textContent = '$ ' + (subtotalPen / TIPO_CAMBIO).toFixed(2);
    document.getElementById('igvDisplay').textContent = '$ ' + (igv / TIPO_CAMBIO).toFixed(2);
    document.getElementById('totalDisplay').textContent = '$ ' + (total / TIPO_CAMBIO).toFixed(2);
  } else {
    // Mostrar en soles
    document.getElementById('subtotalDisplay').textContent = 'S/ ' + subtotalPen.toFixed(2);
    document.getElementById('igvDisplay').textContent = 'S/ ' + igv.toFixed(2);
    document.getElementById('totalDisplay').textContent = 'S/ ' + total.toFixed(2);
  }

}

// === ACTUALIZAR VISUALIZACIÓN SEGÚN MONEDA SELECCIONADA (GLOBAL) ===
function actualizarVisualizacionMoneda() {
  const monedaSeleccionada = document.getElementById('moneda').value;
  document.querySelectorAll('#tablaDetalle tbody tr').forEach(tr => {
    const precioCell = tr.querySelector('.precio-unitario');
    const subtotalCell = tr.querySelector('.subtotal');
    if (!precioCell || !subtotalCell) return;

    const penPrecio = parseFloat(precioCell.getAttribute('data-pen'));
    const usdPrecio = parseFloat(precioCell.getAttribute('data-usd'));
    const penSubtotal = parseFloat(subtotalCell.getAttribute('data-pen'));
    const usdSubtotal = parseFloat(subtotalCell.getAttribute('data-usd'));

    if (monedaSeleccionada === 'USD') {
      precioCell.textContent = `$ ${isNaN(usdPrecio) ? (penPrecio / TIPO_CAMBIO).toFixed(2) : usdPrecio}`;
      subtotalCell.textContent = `$ ${isNaN(usdSubtotal) ? (penSubtotal / TIPO_CAMBIO).toFixed(2) : usdSubtotal}`;
    } else {
      precioCell.textContent = `S/ ${isNaN(penPrecio) ? (usdPrecio * TIPO_CAMBIO).toFixed(2) : penPrecio}`;
      subtotalCell.textContent = `S/ ${isNaN(penSubtotal) ? (usdSubtotal * TIPO_CAMBIO).toFixed(2) : penSubtotal}`;
    }
  });

  // Recalcular totales para sincronizar displays
  calcularTotales();
}

// === GUARDAR VENTA ===
document.getElementById('btnGuardar').addEventListener('click', async ()=>{
  const btn = document.getElementById('btnGuardar');
  if (!btn) return;
  // evitar múltiples envíos
  if (btn.dataset.loading === 'true') return;
  btn.dataset.loading = 'true';
  const original = btn.innerHTML;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Registrando...';
  btn.disabled = true;

  console.log('[VENTA] Click en Registrar Venta');
  if(!clienteSeleccionado) return alert('Debe buscar y seleccionar un cliente');
  if(detalle.length === 0) return alert('Debe agregar al menos un producto');
  
  // === VALIDACIÓN DE COMPATIBILIDAD FACTURA/BOLETA ===
  const validacion = validarCompatibilidadComprobanteCliente();
  
  if (!validacion.valido) {
    alert(validacion.mensaje);
    return;
  }
  
  // Si hay advertencia pero es válido, preguntar al usuario
  if (validacion.advertencia) {
    const confirmar = confirm(validacion.mensaje + '\n\n¿Desea continuar?');
    if (!confirmar) return;
  }
  
  const payload = {
    id_cliente: clienteSeleccionado.id_cliente,
    tipo_comprobante: document.getElementById('tipo_comprobante').value,
    moneda: document.getElementById('moneda').value,
    serie: document.getElementById('serie').value,
    incluir_igv: document.getElementById('incluirIGV').checked, // Agregar estado del checkbox
    // Enviar el tipo de cambio que se está usando en la UI para sincronizar
    tipo_cambio: Number(TIPO_CAMBIO),
    // numero se auto-genera en el servidor
    detalle: detalle,
    // Enviar el TOTAL visible según moneda y estado de IGV
    total: (()=>{
      const monedaSeleccionada = document.getElementById('moneda').value;
      const incluirIGV = document.getElementById('incluirIGV').checked;
      // Usar totales ya calculados
      const subtotal = parseFloat(document.getElementById('subTotal').textContent) || 0;
      const igv = incluirIGV ? (subtotal * IGV) : 0;
      const totalCalc = subtotal + igv;
      if (monedaSeleccionada === 'USD') {
        return Number((totalCalc / TIPO_CAMBIO).toFixed(2));
      }
      return Number(totalCalc.toFixed(2));
    })()
  };

  let data;
  try {
    const res = await fetch('/api/ventas/guardar',{ 
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      credentials: 'same-origin',
      body: JSON.stringify(payload)
    });
    console.log('[VENTA] Respuesta HTTP:', res.status, res.statusText);
    if (!res.ok) {
      const txt = await res.text().catch(() => 'No response body');
      console.error('[VENTA] Error servidor:', res.status, txt);
      data = { ok: false, message: `Error en el servidor: ${res.status} ${res.statusText}. Respuesta: ${txt}` };
    } else {
      data = await res.json().catch(() => ({ ok:false, message:'Respuesta no válida del servidor'}));
    }
  } catch (e) {
    console.error('[VENTA] Error de red:', e);
    data = { ok:false, message:`No se pudo conectar al servidor: ${e.message}` };
  }
  if(data.ok){
    const simbolo = (data.moneda && data.moneda.simbolo) ? data.moneda.simbolo : ('$');
    const iso = (data.moneda && data.moneda.codigo_iso) ? data.moneda.codigo_iso : ('USD');
    const numeroCompleto = (data.numero_comprobante || '').toString(); // ya viene con SERIE-########
    const totalStr = (typeof data.total === 'number') ? data.total.toFixed(2) : Number(data.total || 0).toFixed(2);
    const lineaTC = (typeof data.tipo_cambio === 'number' && iso === 'USD') ? `\nTipo de cambio: ${Number(data.tipo_cambio).toFixed(2)}` : '';
    alert(`Venta registrada correctamente!\nComprobante: ${numeroCompleto}\nTotal: ${simbolo} ${totalStr} ${iso}${lineaTC}`);
    // Si es una cotización, abrir el PDF con el parámetro mostrar_codigo_parte según el checkbox
    try {
      const tipo = document.getElementById('tipo_comprobante') ? document.getElementById('tipo_comprobante').value : '';
      if (tipo === 'Cotización') {
        const checkbox = document.getElementById('mostrarCodigoParteCheckbox');
        const mostrar = (checkbox && checkbox.checked) ? '1' : '0';
        const url = `/ventas/${data.id_venta}/pdf?mostrar_codigo_parte=${mostrar}`;
        // Intentar abrir en nueva pestaña; algunas veces los navegadores bloquean popups si no es directamente desde el click, pero esto suele funcionar.
        window.open(url, '_blank');
        // Recargar la página después de un corto delay para permitir que la descarga/visualización inicie
        setTimeout(() => { location.reload(); }, 700);
      } else {
        location.reload();
      }
    } catch (e) {
      // Fallback: recargar si algo falla
      location.reload();
    }
  } else {
    // Mostrar error amigable en la parte superior del formulario
    const container = document.querySelector('.modern-container') || document.body;
    const err = typeof data.error === 'string' ? data.error : (data.message || 'Ocurrió un error al registrar la venta');
    const alertHtml = `
      <div class="alert alert-danger alert-dismissible fade show" role="alert" style="position:sticky; top:0; z-index:9999;">
        <i class="fas fa-exclamation-circle me-2"></i>
        <strong>Error al registrar venta:</strong> ${err}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>`;
    container.insertAdjacentHTML('afterbegin', alertHtml);

    // Limpiar estados previos
    ['tipo_comprobante','docCliente','buscaProducto','cantidad','precio','descuento','serie','numero'].forEach(id => {
      const el = document.getElementById(id);
      if (el) el.classList.remove('is-invalid');
    });

    // Mostrar errores de validación por campo si existen
    if (data.errors && typeof data.errors === 'object') {
      const fieldMap = {
        'tipo_comprobante': 'tipo_comprobante',
        'serie': 'serie',
        'detalle.0.cantidad': 'cantidad',
        'detalle.0.precio_unitario': 'precio',
        'detalle.0.descuento_porcentaje': 'descuento'
      };
      // Marcar campos conocidos
      Object.keys(data.errors).forEach(key => {
        const id = fieldMap[key];
        if (id) {
          const el = document.getElementById(id);
          if (el) {
            el.classList.add('is-invalid');
            // Insertar feedback si no existe
            if (!el.nextElementSibling || !el.nextElementSibling.classList.contains('invalid-feedback')) {
              const fb = document.createElement('div');
              fb.className = 'invalid-feedback';
              fb.textContent = Array.isArray(data.errors[key]) ? data.errors[key][0] : String(data.errors[key]);
              el.parentNode.appendChild(fb);
            } else {
              el.nextElementSibling.textContent = Array.isArray(data.errors[key]) ? data.errors[key][0] : String(data.errors[key]);
            }
          }
        }
      });

      // Lista consolidada de errores
      const list = document.createElement('div');
      list.className = 'alert alert-warning mt-2';
      list.innerHTML = '<strong>Detalles de validación:</strong><ul class="mb-0"></ul>';
      const ul = list.querySelector('ul');
      Object.values(data.errors).forEach(msgs => {
        const msg = Array.isArray(msgs) ? msgs[0] : String(msgs);
        const li = document.createElement('li');
        li.textContent = msg;
        ul.appendChild(li);
      });
      container.insertAdjacentElement('afterbegin', list);
    }
  }
  // restaurar botón
  btn.dataset.loading = 'false';
  btn.innerHTML = original;
  btn.disabled = false;
});

  // === Mostrar / ocultar opción de imprimir código en cotización ===
  function actualizarMostrarCodigoParteOpt() {
    try {
      const tipo = document.getElementById('tipo_comprobante') ? document.getElementById('tipo_comprobante').value : '';
      const cont = document.getElementById('mostrarCodigoParteContainer');
      if (!cont) return;
      if (tipo === 'Cotización') {
        cont.style.display = 'block';
      } else {
        cont.style.display = 'none';
      }
    } catch (e) {
      console.warn('Error actualizando mostrarCodigoParte:', e);
    }
  }

  // Inicializar al cargar la página y suscribir al cambio de tipo de comprobante
  document.addEventListener('DOMContentLoaded', function() {
    actualizarMostrarCodigoParteOpt();
    const sel = document.getElementById('tipo_comprobante');
    if (sel) sel.addEventListener('change', actualizarMostrarCodigoParteOpt);
  });

// Mostrar modal para registrar cliente si no se encuentra
function mostrarModalRegistrarCliente() {
  const modal = new bootstrap.Modal(document.getElementById('modalRegistrarCliente'));
  modal.show();
}

// === FUNCIÓN PARA LIMPIAR EL FORMULARIO DE REGISTRO DE CLIENTE ===
function limpiarFormularioCliente() {
  document.getElementById('nuevoTipoDocumento').value = 'DNI';
  document.getElementById('nuevoNumeroDocumento').value = '';
  document.getElementById('nuevoNombre').value = '';
  document.getElementById('nuevoDireccion').value = '';
  document.getElementById('nuevoUbigeo').value = '';
  document.getElementById('nuevoTelefono').value = '';
  document.getElementById('nuevoCorreo').value = '';
  document.getElementById('nuevoActivo').checked = true;
}

// Guardar nuevo cliente
const btnGuardarCliente = document.getElementById('btnGuardarCliente');
btnGuardarCliente.addEventListener('click', async () => {
  const nuevoCliente = {
    tipo_documento: document.getElementById('nuevoTipoDocumento').value,
    numero_documento: document.getElementById('nuevoNumeroDocumento').value.trim(),
    nombre: document.getElementById('nuevoNombre').value.trim(),
    direccion: document.getElementById('nuevoDireccion').value.trim(),
    id_ubigeo: document.getElementById('nuevoUbigeo').value.trim(),
    telefono: document.getElementById('nuevoTelefono').value.trim(),
    correo: document.getElementById('nuevoCorreo').value.trim(),
    activo: true
  };

  try {
    const res = await fetch('/api/clientes', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify(nuevoCliente)
    });

    const result = await res.json();

    if (result.success) {
      alert('Cliente guardado satisfactoriamente.');

      // Llenar los campos correspondientes en la venta
      document.getElementById('docCliente').value = result.cliente.documento || '';
      document.getElementById('nombreCliente').value = result.cliente.nombres || result.cliente.nombre || '';
      document.getElementById('direccionCliente').value = result.cliente.direccion || '';

      // Limpiar el formulario del modal
      limpiarFormularioCliente();
      // Cerrar el modal correctamente (Bootstrap 5)
      const modal = bootstrap.Modal.getInstance(document.getElementById('modalRegistrarCliente'));
      if (modal) modal.hide();
    } else {
      alert('Error al registrar cliente: ' + (result.message || 'Error desconocido'));
    }
  } catch (error) {
    console.error('Error al registrar cliente:', error);
    alert('Error al registrar cliente: Ocurrió un error inesperado.');
  }
});

// Ocultar lista de productos cuando se hace click fuera
document.addEventListener('click', function(e) {
  if (!e.target.closest('#buscaProducto') && !e.target.closest('#listaProductos')) {
    lista.style.display = 'none';
  }
});

// === ACTUALIZAR TIPO DE CAMBIO MANUAL ===
document.getElementById('tipoCambioManual').addEventListener('input', function() {
  const tipoCambio = parseFloat(this.value);
  if (!isNaN(tipoCambio) && tipoCambio > 0) {
    TIPO_CAMBIO = tipoCambio;
    // Recalcular representación de totales y filas con el nuevo TC
    if (typeof actualizarVisualizacionMoneda === 'function') {
      actualizarVisualizacionMoneda();
    } else {
      calcularTotales();
    }
  }
});

// Eliminados handlers obsoletos de precio manual para evitar errores de referencia

  // (Eliminado) Segundo handler de "agregar" con fila fija; usamos el handler principal arriba

  // === AUTOINCREMENTO DE SERIE Y NÚMERO ===
  async function actualizarSerie() {
    const tipoSelect = document.getElementById('tipo_comprobante');
    const serieInput = document.getElementById('serie');
    const numeroInput = document.getElementById('numero');
    if (!tipoSelect || !serieInput || !numeroInput) return;

    const tipo = tipoSelect.value;
    const config = configSeries[tipo];
    if (!config) {
      numeroInput.value = 'Seleccione tipo';
      return;
    }
    // Setear serie sugerida por tipo
    serieInput.value = config.serie;

    try {
      const numeroFormateado = await obtenerSiguienteNumero(tipo, serieInput.value, config.prefijo);
      numeroInput.value = numeroFormateado;
      console.log(`Serie ${config.serie} → Próximo ${numeroFormateado}`);
    } catch (err) {
      console.error('Error al obtener siguiente número:', err);
      // Fallback local si la API falla
      numeroInput.value = `${config.prefijo}${String(1).padStart(8, '0')}`;
    }
  }

  // Obtener siguiente número via API dado tipo y serie
  async function obtenerSiguienteNumero(tipo, serie, prefijo) {
    const url = `/api/ventas/siguiente-numero?tipo=${encodeURIComponent(tipo)}&serie=${encodeURIComponent(serie)}`;
    const res = await fetch(url);
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const data = await res.json();
    const next = parseInt(
      data.siguiente_numero ?? data.siguiente ?? data.next ?? data.numero ?? data.correlativo ?? 1,
      10
    );
    const padded = String(isNaN(next) ? 1 : next).padStart(8, '0');
    return `${prefijo}${padded}`;
  }

  // Recalcular número cuando el usuario edita la serie manualmente
  const serieInputEl = document.getElementById('serie');
  const tipoSelectEl = document.getElementById('tipo_comprobante');
  const numeroInputEl = document.getElementById('numero');
  if (serieInputEl && tipoSelectEl && numeroInputEl) {
    serieInputEl.addEventListener('input', async () => {
      const tipo = tipoSelectEl.value;
      const cfg = configSeries[tipo];
      if (!cfg) return;
      try {
        const numeroFormateado = await obtenerSiguienteNumero(tipo, serieInputEl.value, cfg.prefijo);
        numeroInputEl.value = numeroFormateado;
      } catch (e) {
        console.warn('Fallo al recalcular correlativo para serie manual:', e);
      }
    });
  }

  // Función para habilitar o deshabilitar el tipo de cambio manual según la moneda seleccionada
  document.getElementById('moneda').addEventListener('change', function() {
    const tipoCambioInput = document.getElementById('tipoCambioManual');
    const simboloSpan = document.getElementById('precioSimbolo');
    const colPrecioLabel = document.getElementById('colPrecioLabel');
    if (this.value === 'PEN') {
      tipoCambioInput.disabled = true; // Bloquear tipo de cambio manual
      tipoCambioInput.value = ''; // Limpiar el valor del tipo de cambio
      if (simboloSpan) simboloSpan.textContent = 'S/';
      if (colPrecioLabel) colPrecioLabel.textContent = 'Precio Unitario (S/)';
      const hidden = document.getElementById('monedaHidden');
      if (hidden) hidden.value = 'PEN';
    } else {
      tipoCambioInput.disabled = false; // Habilitar tipo de cambio manual
      if (simboloSpan) simboloSpan.textContent = '$';
      if (colPrecioLabel) colPrecioLabel.textContent = 'Precio Unitario ($)';
      const hidden = document.getElementById('monedaHidden');
      if (hidden) hidden.value = 'USD';
    }

    // Actualizar detalle visual a la moneda elegida
    if (typeof actualizarVisualizacionMoneda === 'function') {
      actualizarVisualizacionMoneda();
    }
  });

  // Inicializar estado del tipo de cambio manual
  const monedaInicial = document.getElementById('moneda').value;
  const tipoCambioInput = document.getElementById('tipoCambioManual');
  if (monedaInicial === 'PEN') {
    tipoCambioInput.disabled = true;
    const simboloSpan = document.getElementById('precioSimbolo');
    if (simboloSpan) simboloSpan.textContent = 'S/';
    const colPrecioLabel = document.getElementById('colPrecioLabel');
    if (colPrecioLabel) colPrecioLabel.textContent = 'Precio Unitario (S/)';
  } else {
    const simboloSpan = document.getElementById('precioSimbolo');
    if (simboloSpan) simboloSpan.textContent = '$';
    const colPrecioLabel = document.getElementById('colPrecioLabel');
    if (colPrecioLabel) colPrecioLabel.textContent = 'Precio Unitario ($)';
  }
  // Inicializar serie y número al cargar
  if (typeof actualizarSerie === 'function') {
    try { actualizarSerie(); } catch (e) { console.warn('No se pudo inicializar serie/numero:', e); }
  }
  // Asegurar que el cambio de tipo dispare el autoincremento incluso si falla el atributo inline
  const tipoSelectHook = document.getElementById('tipo_comprobante');
  if (tipoSelectHook) {
    tipoSelectHook.addEventListener('change', () => {
      if (typeof actualizarSerie === 'function') {
        actualizarSerie();
      }
    });
  }
</script>

<style>
/* Variables CSS para diseño moderno */
:root {
    --primary-color: #667eea;
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-color: #28a745;
    --success-gradient: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    --info-color: #17a2b8;
    --info-gradient: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    --warning-color: #ffc107;
    --warning-gradient: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    --border-radius: 12px;
    --box-shadow: 0 8px 25px -8px rgba(0, 0, 0, 0.1);
    --hover-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.15);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Container principal */
.modern-container {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: 100vh;
    padding: 2rem 0;
}

/* Formulario principal - importante para z-index */
#formVenta {
    position: relative;
    z-index: 1;
}

/* Header moderno */
.page-header {
    background: white;
    border-radius: var(--border-radius);
    padding: 2rem;
    box-shadow: var(--box-shadow);
    margin-bottom: 2rem;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.page-icon {
    width: 60px;
    height: 60px;
    background: var(--success-gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: white;
    box-shadow: var(--box-shadow);
}

.page-title {
    font-size: 2.2rem;
    font-weight: 700;
    color: #2d3748;
    margin: 0;
}

.page-subtitle {
    font-size: 1rem;
    color: #718096;
    margin: 0;
}

/* Cards modernas */
.modern-card {
    border-radius: var(--border-radius);
    border: none;
    box-shadow: var(--box-shadow);
    overflow: visible;
    background: white;
    transition: var(--transition);
    position: relative;
    z-index: 1;
}

/* Card específica para productos - z-index menor para que dropdown aparezca encima */
.modern-card:has(.product-search-container) {
    z-index: 0;
}

.modern-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--hover-shadow);
}

.modern-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    padding: 1.5rem;
}

.modern-footer {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-top: 1px solid rgba(0, 0, 0, 0.05);
    padding: 1.25rem 1.5rem;
}

/* Card body específico para productos - overflow visible */
.modern-card .card-body {
    overflow: visible !important;
    position: relative;
    z-index: 1;
}

/* Labels modernos */
.modern-label {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
    display: block;
}

/* Inputs modernos */
.modern-input, .modern-select {
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    transition: var(--transition);
    font-size: 0.95rem;
}

.modern-input:focus, .modern-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    outline: none;
}

.readonly-input {
    background-color: #f7fafc !important;
    color: #4a5568;
    cursor: not-allowed;
}

.auto-number {
    background: linear-gradient(135deg, #f0f4f8 0%, #d6e8f5 100%);
    color: #4a5568;
    font-style: italic;
}

/* Input groups */
.modern-input-group .modern-input {
    border-radius: 8px 0 0 8px;
    border-right: none;
}

.modern-input-group .modern-btn {
    border-radius: 0 8px 8px 0;
    border-left: none;
}

/* Tipo de cambio */
.tipo-cambio-info {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    border-radius: 8px;
    padding: 1rem;
    border: 1px solid #f0d000;
}

.tipo-cambio-value {
    font-weight: 700;
    color: #e67e22;
    font-size: 1.1rem;
}

/* Búsqueda de productos */
.product-search-container {
    position: relative !important;
    z-index: 1000 !important;
}

.product-search {
    background: linear-gradient(135deg, #ffffff 0%, #f0f9ff 100%);
    border-color: var(--info-color);
    position: relative;
    z-index: 1001;
}

.product-dropdown {
    position: absolute !important;
    top: 100% !important;
    left: 0 !important;
    right: 0 !important;
    background: white !important;
    border: 2px solid var(--info-color);
    border-top: none;
    border-radius: 0 0 8px 8px;
    max-height: 300px;
    overflow-y: auto;
    z-index: 999999 !important;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.35) !important;
}

.product-dropdown .dropdown-item {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #f1f5f9;
    cursor: pointer;
    transition: background-color 0.2s ease;
    display: block;
    width: 100%;
    background: transparent;
    border: none;
    text-align: left;
}

.product-dropdown .dropdown-item:hover {
    background: linear-gradient(135deg, #e6f7ff 0%, #bae7ff 100%);
}

/* Botones modernos */
.modern-btn, .btn-modern {
    border-radius: 8px;
    font-weight: 600;
    padding: 0.75rem 1.5rem;
    transition: var(--transition);
    border: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.modern-btn:hover, .btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: var(--hover-shadow);
}

.modern-btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    border-radius: 6px;
}

.btn-success.modern-btn {
    background: var(--success-gradient);
    color: white;
}

.btn-primary.modern-btn {
    background: var(--primary-gradient);
    color: white;
}

/* Tabla moderna */
.modern-table {
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    position: relative;
    z-index: 1;
}

.table-responsive {
    position: relative;
    z-index: 1;
}

.table-header th {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: none;
    padding: 1rem 0.75rem;
    font-weight: 600;
    color: #2d3748;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    position: relative;
    z-index: 2;
}

.table-body td {
    padding: 0.875rem 0.75rem;
    border: none;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}

.table-body tr:hover {
    background: rgba(102, 126, 234, 0.02);
}

/* Empty state */
.empty-table-state {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-radius: 8px;
    margin: 2rem 1rem;
    border: 2px dashed #dee2e6;
}

/* Summary section */
.summary-totals {
    background: linear-gradient(135deg, #e8f5e8 0%, #d4f4dd 100%) !important;
    border: 2px solid #c3e6cb;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
}

.summary-row.total-row {
    border-top: 2px solid #28a745;
    padding-top: 1rem;
    margin-top: 1rem;
    margin-bottom: 0;
    font-size: 1.1rem;
}

.summary-label {
    font-weight: 600;
    color: #2d3748;
}

.summary-amounts {
    text-align: right;
}

.amount-pen {
    font-weight: 600;
    color: #2d3748;
    display: block;
    font-size: 1rem;
}

.amount-pen-total {
    font-weight: 700;
    color: var(--success-color);
    font-size: 1.2rem;
    display: block;
}

.amount-usd {
    font-size: 0.8rem;
    color: #718096;
    display: block;
}

/* Header actions */
.header-actions {
    display: flex;
    gap: 0.75rem;
    align-items: center;
}

/* Summary info */
.summary-info {
    background: linear-gradient(135deg, #e1f5fe 0%, #b3e5fc 100%) !important;
    border: 1px solid #4fc3f7;
}

/* Animaciones */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modern-card {
    animation: slideInUp 0.5s ease-out;
}

.modern-card:nth-child(1) { animation-delay: 0.1s; }
.modern-card:nth-child(2) { animation-delay: 0.2s; }
.modern-card:nth-child(3) { animation-delay: 0.3s; }

/* Responsive */
@media (max-width: 768px) {
    .page-title {
        font-size: 1.8rem;
    }
    
    .page-icon {
        width: 50px;
        height: 50px;
        font-size: 1.4rem;
    }
    
    .header-actions {
        flex-direction: column;
        gap: 0.5rem;
        width: 100%;
    }
    
    .header-actions .btn {
        width: 100%;
    }
}

/* Loading states */
.loading {
    opacity: 0.7;
    pointer-events: none;
}

.btn-modern:disabled {
    opacity: 0.6;
    transform: none !important;
    cursor: not-allowed;
}

/* Z-Index fixes para dropdown */
.modern-container .card,
.modern-container .card-body,
.modern-container .row,
.modern-container .col-md-4 {
    position: relative;
    z-index: 1;
}

/* Evitar que otros elementos interfieran con el dropdown */
.modern-card:not(:has(.product-search-container)) {
    z-index: 1;
}

/* Card de detalle debe tener z-index menor */
.modern-card:has(.modern-table),
.modern-card:has(.table-responsive) {
    z-index: 0 !important;
}

/* Asegurar que el dropdown siempre esté encima */
.product-dropdown {
    position: fixed !important;
}

/* Ajustar position del dropdown usando JavaScript se necesario */
#listaProductos.product-dropdown {
    position: absolute !important;
    z-index: 9999999 !important;
    box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4) !important;
    border: 3px solid var(--info-color) !important;
}

/* Asegurar que la tabla no interfiera */
.table-responsive,
.modern-table,
.table-header,
.table-body {
    position: relative;
    z-index: 1 !important;
}

/* Container de búsqueda debe tener máxima prioridad */
.product-search-container {
    position: relative !important;
    z-index: 10000 !important;
}
</style>

@endsection