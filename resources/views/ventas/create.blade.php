@extends('layouts.dashboard')

@section('content')
<div class="container mt-3">
  <h4>Nueva Venta</h4>
  <form id="formVenta">
    <!-- Información General -->
    <div class="card mb-3">
      <div class="card-header bg-light">Información General</div>
      <div class="card-body row g-3">
        <div class="col-md-3">
          <label>Fecha y Hora</label>
          <input type="datetime-local" id="fecha" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}">
        </div>
        <div class="col-md-3">
          <label>Tipo Comprobante</label>
          <select id="tipo_comprobante" class="form-select" onchange="actualizarSerie()">
            <option value="">Seleccione tipo</option>
            <option value="Cotizacion">Cotización</option>
            <option value="Factura">Factura</option>
            <option value="Boleta">Boleta</option>
            <option value="Nota de Crédito">Nota de Crédito</option>
          </select>
        </div>
        <div class="col-md-2">
          <label>Moneda</label>
          <select id="moneda" class="form-select">
            <option value="PEN">Sol Peruano</option>
            <option value="USD">Dólar</option>
          </select>
        </div>
        <div class="col-md-2">
          <label>Serie</label>
          <input id="serie" class="form-control" value="B001">
        </div>
        <div class="col-md-2">
          <label>Número</label>
          <input id="numero" class="form-control" value="Auto-generado" readonly style="background-color: #f8f9fa;">
        </div>
      </div>
      <div class="card-footer bg-light">
        <small class="text-info">
          <i class="fas fa-exchange-alt"></i> 
          <strong>Tipo de Cambio Actual:</strong> S/ <span id="tipoCambioDisplay">{{ number_format($tipoCambio, 2) }}</span> por USD
          <span class="text-muted">| Los precios se muestran en ambas monedas</span>
          <div class="mt-1">
            <button type="button" class="btn btn-sm btn-outline-info" onclick="actualizarTipoCambio()" id="btnActualizarTC">
              <i class="fas fa-sync-alt"></i> Actualizar
            </button>
            <button type="button" class="btn btn-sm btn-outline-warning" onclick="forzarActualizarTipoCambio()" id="btnForzarTC">
              <i class="fas fa-bolt"></i> Forzar
            </button>
            <small id="infoTipoCambio" class="text-muted ms-2">
              <span id="fuenteTC"></span> | Última actualización: <span id="fechaTC">{{ now()->format('d/m/Y H:i:s') }}</span>
            </small>
          </div>
        </small>
      </div>
    </div>

    <!-- Información Cliente -->
    <div class="card mb-3">
      <div class="card-header bg-light">Información del Cliente</div>
      <div class="card-body row g-3">
        <div class="col-md-3">
          <label>RUC/DNI Cliente</label>
          <div class="input-group">
            <input id="docCliente" class="form-control" placeholder="Ingrese RUC o DNI">
            <button class="btn btn-primary" type="button" id="btnBuscarCliente">Buscar</button>
          </div>
        </div>
        <div class="col-md-5">
          <label>Nombre / Razón Social</label>
          <input id="nombreCliente" class="form-control" readonly>
        </div>
        <div class="col-md-4">
          <label>Dirección</label>
          <input id="direccionCliente" class="form-control" readonly>
        </div>
      </div>
    </div>

    <!-- Agregar Productos -->
    <div class="card mb-3">
      <div class="card-header bg-light">Agregar Productos</div>
      <div class="card-body row g-3 align-items-end">
        <div class="col-md-4">
          <label>Producto</label>
          <input id="buscaProducto" class="form-control" placeholder="Buscar producto...">
          <div id="listaProductos" class="border mt-1" style="max-height:200px; overflow:auto; display:none;"></div>
        </div>
        <div class="col-md-2">
          <label>Cantidad</label>
          <input id="cantidad" class="form-control" type="number" min="0.01" value="1">
        </div>
        <div class="col-md-2">
          <label>Precio Unit.</label>
          <input id="precio" class="form-control" readonly>
        </div>
        <div class="col-md-2">
          <label>Descuento %</label>
          <input id="descuento" class="form-control" type="number" min="0" value="0">
        </div>
        <div class="col-md-2">
          <button id="agregar" class="btn btn-success w-100" type="button">Agregar</button>
        </div>
      </div>
    </div>

    <!-- Detalle de Venta -->
    <div class="card mb-3">
      <div class="card-header bg-light">Detalle de la Venta</div>
      <div class="card-body">
        <table class="table table-bordered" id="tablaDetalle">
          <thead>
            <tr>
              <th style="width: 40%;">Producto</th>
              <th style="width: 10%;">Cantidad</th>
              <th style="width: 25%;">Precio Unitario (PEN/USD)</th>
              <th style="width: 10%;">Desc%</th>
              <th style="width: 15%;">Subtotal</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <div class="text-end">
          <p>Subtotal: S/ <span id="subTotal">0.00</span> <small class="text-muted">($<span id="subTotalUSD">0.00</span>)</small></p>
          <p>IGV (18%): S/ <span id="igv">0.00</span> <small class="text-muted">($<span id="igvUSD">0.00</span>)</small></p>
          <h5>Total: S/ <span id="total">0.00</span> <small class="text-muted">($<span id="totalUSD">0.00</span>)</small></h5>
        </div>
      </div>
    </div>

    <div class="text-end">
      <button id="btnGuardar" type="button" class="btn btn-primary">Registrar Venta</button>
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
@endsection