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
          <select id="tipo_comprobante" class="form-select">
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
            <tr><th>Producto</th><th>Cantidad</th><th>Precio Unitario</th><th>Desc%</th><th>Subtotal</th></tr>
          </thead>
          <tbody></tbody>
        </table>
        <div class="text-end">
          <p>Subtotal: <span id="subTotal">0.00</span></p>
          <p>IGV (18%): <span id="igv">0.00</span></p>
          <h5>Total: <span id="total">0.00</span></h5>
        </div>
      </div>
    </div>

    <div class="text-end">
      <button id="btnGuardar" type="button" class="btn btn-primary">Registrar Venta</button>
    </div>
  </form>
</div>

<script>
// === VARIABLES ===
const IGV = 0.18;
let detalle = [];
let productoSeleccionado = null;
let clienteSeleccionado = null;

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
      // Cliente no encontrado
      alert(`Cliente no encontrado. ${data.message || 'Verifique el número de documento.'}`);
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
              <div class="fw-bold text-success fs-5">S/ ${it.precio_venta}</div>
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
    tr.innerHTML=`<td>${d.descripcion}</td><td>${d.cantidad}</td><td>${d.precio_unitario}</td><td>${d.descuento_porcentaje}</td><td>${d.subtotal.toFixed(2)}</td>`;
    tbody.appendChild(tr);
  });
  const igv=subtotal*IGV;
  const total=subtotal+igv;
  document.getElementById('subTotal').textContent=subtotal.toFixed(2);
  document.getElementById('igv').textContent=igv.toFixed(2);
  document.getElementById('total').textContent=total.toFixed(2);
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

// Ocultar lista de productos cuando se hace click fuera
document.addEventListener('click', function(e) {
  if (!e.target.closest('#buscaProducto') && !e.target.closest('#listaProductos')) {
    lista.style.display = 'none';
  }
});
</script>
@endsection