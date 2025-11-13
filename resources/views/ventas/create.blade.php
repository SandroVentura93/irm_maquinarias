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
            <select id="tipo_comprobante" class="form-select modern-select" onchange="actualizarSerie()">
              <option value="">Seleccione tipo</option>
              <option value="Cotizacion">📝 Cotización</option>
              <option value="Factura">🧾 Factura</option>
              <option value="Boleta">🧾 Boleta</option>
              <option value="Nota de Crédito">📃 Nota de Crédito</option>
            </select>
          </div>
          <div class="col-md-2">
            <label class="modern-label">
              <i class="fas fa-coins me-1"></i>
              Moneda
            </label>
            <select id="moneda" class="form-select modern-select">
              <option value="PEN">🇵🇪 Sol Peruano</option>
              <option value="USD">🇺🇸 Dólar</option>
            </select>
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
              <strong class="text-dark">Tipo de Cambio:</strong> 
              <span class="tipo-cambio-value">S/ <span id="tipoCambioDisplay">{{ number_format($tipoCambio, 2) }}</span></span>
              <span class="text-muted ms-1">por USD</span>
              <small class="text-info ms-3">Los precios se muestran en ambas monedas</small>
            </div>
            <div class="tipo-cambio-actions">
              <button type="button" class="btn btn-outline-info btn-sm modern-btn-sm" onclick="actualizarTipoCambio()" id="btnActualizarTC">
                <i class="fas fa-sync-alt me-1"></i> Actualizar
              </button>
              <button type="button" class="btn btn-outline-warning btn-sm modern-btn-sm ms-1" onclick="forzarActualizarTipoCambio()" id="btnForzarTC">
              <i class="fas fa-bolt me-1"></i> Forzar
            </button>
            </div>
          </div>
          <small id="infoTipoCambio" class="text-muted mt-2 d-block">
            <span id="fuenteTC"></span> • Última actualización: <span id="fechaTC">{{ now()->format('d/m/Y H:i:s') }}</span>
          </small>
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
            <input id="precio" class="form-control modern-input readonly-input" readonly>
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
                  <i class="fas fa-tag me-1"></i> Precio Unitario (PEN/USD)
                </th>
                <th style="width: 10%;" class="text-center">
                  <i class="fas fa-percent me-1"></i> Desc%
                </th>
                <th style="width: 15%;" class="text-end">
                  <i class="fas fa-calculator me-1"></i> Subtotal
                </th>
              </tr>
            </thead>
            <tbody class="table-body"></tbody>
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
              <span class="text-muted">Los precios se calculan automáticamente en ambas monedas</span>
            </div>
          </div>
          <div class="col-md-6">
            <div class="summary-totals p-3 bg-gradient-light rounded">
              <div class="summary-row">
                <span class="summary-label">
                  <i class="fas fa-calculator me-1 text-info"></i> Subtotal:
                </span>
                <div class="summary-amounts">
                  <span class="amount-pen">S/ <span id="subTotal">0.00</span></span>
                  <small class="amount-usd">$<span id="subTotalUSD">0.00</span></small>
                </div>
              </div>
              
              <div class="summary-row">
                <span class="summary-label">
                  <i class="fas fa-percent me-1 text-warning"></i> IGV (18%):
                </span>
                <div class="summary-amounts">
                  <span class="amount-pen">S/ <span id="igv">0.00</span></span>
                  <small class="amount-usd">$<span id="igvUSD">0.00</span></small>
                </div>
              </div>
              
              <hr class="my-2">
              
              <div class="summary-row total-row">
                <span class="summary-label fw-bold">
                  <i class="fas fa-money-bill-wave me-1 text-success"></i> TOTAL:
                </span>
                <div class="summary-amounts">
                  <span class="amount-pen-total">S/ <span id="total">0.00</span></span>
                  <small class="amount-usd">$<span id="totalUSD">0.00</span></small>
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
                    <option value="{{ $ubigeo->id_ubigeo }}">{{ $ubigeo->descripcion }}</option>
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

<script>
// === VARIABLES ===
const IGV = 0.18;
let TIPO_CAMBIO = {{ $tipoCambio }};
let detalle = [];
let productoSeleccionado = null;
let clienteSeleccionado = null;

// === FUNCIONES DE CONVERSIÓN DE MONEDA ===
function formatearPrecio(precio, mostrarDolares = true) {
  const precioSoles = parseFloat(precio);
  const precioDolares = precioSoles / TIPO_CAMBIO;
  
  if (mostrarDolares) {
    return `S/ ${precioSoles.toFixed(2)} - $${precioDolares.toFixed(2)}`;
  } else {
    return `S/ ${precioSoles.toFixed(2)}`;
  }
}

function convertirSolesADolares(soles) {
  return parseFloat(soles) / TIPO_CAMBIO;
}

function convertirDolaresASoles(dolares) {
  return parseFloat(dolares) * TIPO_CAMBIO;
}

// === FUNCIÓN PARA ACTUALIZAR TIPO DE CAMBIO ===
async function actualizarTipoCambio() {
  const btn = document.getElementById('btnActualizarTC');
  const originalText = btn.innerHTML;
  
  try {
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Actualizando...';
    btn.disabled = true;
    
    const response = await fetch('/ventas/tipo-cambio');
    const data = await response.json();
    
    if (data.success) {
      // Actualizar la variable global
      TIPO_CAMBIO = data.tipo_cambio;
      
      // Actualizar el display
      document.getElementById('tipoCambioDisplay').textContent = data.tipo_cambio.toFixed(2);
      document.getElementById('fechaTC').textContent = data.fecha_actualizacion;
      document.getElementById('fuenteTC').textContent = data.fuente || 'API Externa';
      
      // Actualizar los precios mostrados si hay productos en la lista
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
      
      // Mostrar mensaje de éxito con información adicional
      const cacheInfo = data.cache_hit ? ' (desde caché)' : ' (recién actualizado)';
      mostrarAlerta('success', `Tipo de cambio actualizado: S/ ${data.tipo_cambio.toFixed(2)} - Fuente: ${data.fuente}${cacheInfo}`);
      
    } else {
      mostrarAlerta('error', 'Error al actualizar tipo de cambio: ' + (data.message || 'Error desconocido'));
    }
    
  } catch (error) {
    console.error('Error:', error);
    mostrarAlerta('error', 'Error de conexión al actualizar tipo de cambio');
  } finally {
    btn.innerHTML = originalText;
    btn.disabled = false;
  }
}

// === FUNCIÓN PARA FORZAR ACTUALIZACIÓN DEL TIPO DE CAMBIO ===
async function forzarActualizarTipoCambio() {
  const btn = document.getElementById('btnForzarTC');
  const originalText = btn.innerHTML;
  
  if (!confirm('¿Estás seguro de forzar la actualización del tipo de cambio? Esto puede tomar unos segundos.')) {
    return;
  }
  
  try {
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Forzando...';
    btn.disabled = true;
    
    const response = await fetch('/ventas/tipo-cambio/forzar', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      }
    });
    
    const data = await response.json();
    
    if (data.success) {
      // Actualizar la variable global
      TIPO_CAMBIO = data.tipo_cambio;
      
      // Actualizar el display
      document.getElementById('tipoCambioDisplay').textContent = data.tipo_cambio.toFixed(2);
      document.getElementById('fechaTC').textContent = data.fecha_actualizacion;
      document.getElementById('fuenteTC').textContent = 'Recién actualizado';
      
      // Actualizar los precios mostrados
      const productosItems = document.querySelectorAll('.prod-item');
      productosItems.forEach(item => {
        const precio = parseFloat(item.dataset.precio);
        const precioContainer = item.querySelector('.fs-6');
        if (precioContainer) {
          precioContainer.innerHTML = formatearPrecio(precio);
        }
      });
      
      // Recalcular totales
      if (detalle.length > 0) {
        actualizarTabla();
      }
      
      mostrarAlerta('success', `Tipo de cambio forzado: S/ ${data.tipo_cambio.toFixed(2)} - ${data.mensaje}`);
      
    } else {
      mostrarAlerta('error', 'Error al forzar actualización: ' + (data.message || 'Error desconocido'));
    }
    
  } catch (error) {
    console.error('Error:', error);
    mostrarAlerta('error', 'Error de conexión al forzar actualización del tipo de cambio');
  } finally {
    btn.innerHTML = originalText;
    btn.disabled = false;
  }
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
const configSeries = {
  'Cotizacion': { serie: 'COT', prefijo: 'COT-' },
  'Factura': { serie: 'F001', prefijo: 'F001-' },
  'Boleta': { serie: 'B001', prefijo: 'B001-' },
  'Nota de Crédito': { serie: 'NC01', prefijo: 'NC01-' }
};

// === FUNCIÓN PARA ACTUALIZAR SERIE SEGÚN TIPO DE COMPROBANTE ===
async function actualizarSerie() {
  const tipoComprobante = document.getElementById('tipo_comprobante').value;
  const serieInput = document.getElementById('serie');
  const numeroInput = document.getElementById('numero');
  
  if (!tipoComprobante) {
    serieInput.value = '';
    numeroInput.value = 'Seleccione tipo';
    return;
  }
  
  const config = configSeries[tipoComprobante];
  if (!config) return;
  
  // Actualizar serie
  serieInput.value = config.serie;
  
  try {
    // Mostrar cargando
    numeroInput.value = 'Cargando...';
    
    // Obtener el siguiente número para esta serie y tipo
    const response = await fetch(`/api/ventas/siguiente-numero?tipo=${encodeURIComponent(tipoComprobante)}&serie=${encodeURIComponent(config.serie)}`);
    
    if (!response.ok) {
      throw new Error('Error al obtener siguiente número');
    }
    
    const data = await response.json();
    
    // Mostrar el siguiente número con formato
    const numeroFormateado = String(data.siguiente_numero).padStart(8, '0');
    numeroInput.value = config.prefijo + numeroFormateado;
    
    console.log(`Serie actualizada: ${config.serie}, Próximo número: ${numeroFormateado}`);
    
  } catch (error) {
    console.error('Error al obtener siguiente número:', error);
    numeroInput.value = 'Error al cargar';
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
      document.getElementById('nombreCliente').value = c.razon_social || c.nombre || 'Sin nombre';
      document.getElementById('direccionCliente').value = c.direccion || 'Sin dirección';
      
      // Mostrar mensaje de éxito
      const alert = document.createElement('div');
      alert.className = 'alert alert-success mt-2';
      alert.innerHTML = `<i class="fas fa-check"></i> Cliente encontrado: ${c.razon_social || c.nombre}`;
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
              data-desc='${it.descripcion}' 
              data-precio='${it.precio_venta}'
              data-stock='${it.stock_actual || 0}'
              data-modelo='${it.modelo || ''}'
              data-ubicacion='${it.ubicacion || ''}'
              style="cursor: pointer; transition: background-color 0.2s;">
          
          <div class="row">
            <div class="col-8">
              <div class="fw-bold text-primary">
                ${it.codigo || 'SIN CÓDIGO'} 
                ${it.numero_parte ? '<span class="text-muted">| ' + it.numero_parte + '</span>' : ''}
              </div>
              <div class="text-dark">${it.descripcion}</div>
              ${it.modelo ? '<small class="text-muted">Modelo: ' + it.modelo + '</small><br>' : ''}
              <small class="text-info">
                <i class="fas fa-tags"></i> ${it.categoria} | 
                <i class="fas fa-copyright"></i> ${it.marca} |
                <i class="fas fa-truck"></i> ${it.proveedor}
              </small>
            </div>
            <div class="col-4 text-end">
              <div class="fw-bold text-success fs-6">${formatearPrecio(it.precio_venta)}</div>
              <small class="text-muted d-block">T.C: S/ ${TIPO_CAMBIO.toFixed(2)}</small>
              <small class="d-block ${it.stock_status === 'Bajo' ? 'text-danger' : 'text-success'}">
                <i class="fas fa-boxes"></i> Stock: ${it.stock_actual}
                ${it.stock_status === 'Bajo' ? '<i class="fas fa-exclamation-triangle text-warning"></i>' : ''}
              </small>
              ${it.ubicacion !== 'Sin ubicación' ? '<small class="text-muted"><i class="fas fa-map-marker-alt"></i> ' + it.ubicacion + '</small>' : ''}
              ${it.importado === 'Sí' ? '<br><span class="badge badge-info">Importado</span>' : ''}
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
          document.getElementById('precio').value = productoSeleccionado.precio;
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
  if(!productoSeleccionado) return alert('Seleccione un producto');
  const cant = parseFloat(document.getElementById('cantidad').value);
  const desc = parseFloat(document.getElementById('descuento').value);
  const precio = productoSeleccionado.precio;
  const precioFinal = precio*(1-desc/100);
  const subtotal = precioFinal*cant;

  detalle.push({
    id_producto: productoSeleccionado.id,
    descripcion: productoSeleccionado.desc,
    cantidad: cant,
    precio_unitario: precio,
    descuento_porcentaje: desc,
    subtotal
  });
  renderTabla();
  productoSeleccionado=null;
  inputProd.value='';
  document.getElementById('precio').value='';
  document.getElementById('cantidad').value='1';
  document.getElementById('descuento').value='0';
});

// === RENDERIZAR DETALLE ===
function renderTabla(){
  const tbody = document.querySelector('#tablaDetalle tbody');
  tbody.innerHTML='';
  let subtotal=0;
  detalle.forEach((d,i)=>{
    subtotal+=d.subtotal;
    const tr=document.createElement('tr');
    const precioConMonedas = `S/ ${d.precio_unitario} ($${(d.precio_unitario / TIPO_CAMBIO).toFixed(2)})`;
    tr.innerHTML=`<td>${d.descripcion}</td><td>${d.cantidad}</td><td>${precioConMonedas}</td><td>${d.descuento_porcentaje}%</td><td>S/ ${d.subtotal.toFixed(2)}</td>`;
    tbody.appendChild(tr);
  });
  const igv=subtotal*IGV;
  const total=subtotal+igv;
  
  // Actualizar totales en soles
  document.getElementById('subTotal').textContent=subtotal.toFixed(2);
  document.getElementById('igv').textContent=igv.toFixed(2);
  document.getElementById('total').textContent=total.toFixed(2);
  
  // Actualizar totales en dólares
  document.getElementById('subTotalUSD').textContent=(subtotal / TIPO_CAMBIO).toFixed(2);
  document.getElementById('igvUSD').textContent=(igv / TIPO_CAMBIO).toFixed(2);
  document.getElementById('totalUSD').textContent=(total / TIPO_CAMBIO).toFixed(2);
}

// === GUARDAR VENTA ===
document.getElementById('btnGuardar').addEventListener('click', async ()=>{
  if(!clienteSeleccionado) return alert('Debe buscar y seleccionar un cliente');
  if(detalle.length === 0) return alert('Debe agregar al menos un producto');
  
  const payload = {
    id_cliente: clienteSeleccionado.id_cliente,
    tipo_comprobante: document.getElementById('tipo_comprobante').value,
    moneda: document.getElementById('moneda').value,
    serie: document.getElementById('serie').value,
    // numero se auto-genera en el servidor
    detalle: detalle
  };

  const res = await fetch('/api/ventas/guardar',{
    method:'POST',
    headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
    body: JSON.stringify(payload)
  });
  const data = await res.json();
  if(data.ok){
    alert(`Venta registrada correctamente!\nComprobante: ${data.serie}-${String(data.numero_comprobante).padStart(8, '0')}\nTotal: S/ ${data.total}`);
    location.reload();
  } else {
    alert('Error: '+data.error);
  }
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
      alert('Cliente registrado exitosamente.');

      // Llenar los campos correspondientes en la venta
      document.getElementById('docCliente').value = result.cliente.numero_documento;
      document.getElementById('nombreCliente').value = result.cliente.nombre;
      document.getElementById('direccionCliente').value = result.cliente.direccion;

      // Cerrar el modal
      $('#modalRegistrarCliente').modal('hide');

      // Limpiar el formulario del modal
      limpiarFormularioCliente();
    } else {
      alert('Error al registrar cliente: ' + result.message);
    }
  } catch (error) {
    console.error('Error al registrar cliente:', error);
    alert('Error al registrar cliente: ' + error.message);
  }
});

// Ocultar lista de productos cuando se hace click fuera
document.addEventListener('click', function(e) {
  if (!e.target.closest('#buscaProducto') && !e.target.closest('#listaProductos')) {
    lista.style.display = 'none';
  }
});
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