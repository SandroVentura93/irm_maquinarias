@extends('layouts.dashboard')

@section('content')
<style>
    /* Reuse styles from create */
    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem; padding-bottom:1rem; border-bottom:3px solid #dc2626; }
    .page-title { font-size:1.75rem; font-weight:700; background:linear-gradient(135deg,#dc2626 0%,#991b1b 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; display:flex; align-items:center; gap:.75rem; margin:0; }
    .breadcrumb-custom { background:transparent; padding:0; margin:0; font-size:.875rem; }
    .breadcrumb-custom a { color:#dc2626; text-decoration:none; transition:.3s; }
    .breadcrumb-custom a:hover { color:#991b1b; }
    .card-modern { border-radius:16px; border:none; box-shadow:0 4px 6px -1px rgba(0,0,0,.1), 0 2px 4px -1px rgba(0,0,0,.06); overflow:hidden; margin-bottom:2rem; }
    .card-header-gradient { background:linear-gradient(135deg,#dc2626 0%,#991b1b 100%); color:#fff; padding:1.25rem 1.5rem; border:none; }
    .card-header-gradient h5 { margin:0; font-weight:600; font-size:1.125rem; display:flex; align-items:center; gap:.5rem; }
    .card-body-custom { padding:2rem; }
    .form-label { font-weight:600; color:#374151; margin-bottom:.5rem; display:flex; align-items:center; gap:.375rem; font-size:.875rem; }
    .form-label i { color:#dc2626; font-size:.875rem; }
    .form-control, .form-select { border:2px solid #e5e7eb; border-radius:10px; padding:.625rem 1rem; transition:.3s; font-size:.9375rem; }
    .form-control:focus, .form-select:focus { border-color:#dc2626; box-shadow:0 0 0 3px rgba(220,38,38,.1); }
    .form-control:read-only { background:#f9fafb; color:#6b7280; font-weight:600; }
    .table-productos { background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.08); }
    .table-productos thead { background:linear-gradient(135deg,#fef2f2 0%,#fee2e2 100%); }
    .table-productos thead th { border:none; padding:1rem; font-weight:600; color:#991b1b; text-transform:uppercase; letter-spacing:.5px; font-size:.8125rem; }
    .table-productos tbody td { padding:.75rem; vertical-align:middle; border-bottom:1px solid #f3f4f6; }
    .table-productos tbody tr:last-child td { border-bottom:none; }
    .table-productos tbody tr { transition:background-color .2s; }
    .table-productos tbody tr:hover { background-color:#fef2f2; }
    .btn-remove-row { background:linear-gradient(135deg,#ef4444 0%,#dc2626 100%); color:#fff; border:none; border-radius:8px; width:36px; height:36px; display:inline-flex; align-items:center; justify-content:center; transition:.3s; cursor:pointer; }
    .btn-remove-row:hover { transform:scale(1.1); box-shadow:0 4px 8px rgba(239,68,68,.3); }
    .btn-calculate { background:linear-gradient(135deg,#3b82f6 0%,#2563eb 100%); color:#fff; border:none; border-radius:10px; padding:.625rem 1.5rem; font-weight:600; transition:.3s; display:inline-flex; align-items:center; gap:.5rem; box-shadow:0 2px 4px rgba(59,130,246,.2); }
    .btn-submit { background:linear-gradient(135deg,#dc2626 0%,#991b1b 100%); color:#fff; border:none; border-radius:10px; padding:.75rem 2.5rem; font-weight:700; transition:.3s; display:inline-flex; align-items:center; gap:.5rem; box-shadow:0 4px 6px rgba(220,38,38,.3); font-size:1rem; }
    .btn-cancel { background:#6b7280; color:#fff; border:none; border-radius:10px; padding:.75rem 2.5rem; font-weight:600; transition:.3s; display:inline-flex; align-items:center; gap:.5rem; }
    .totals-card { background:linear-gradient(135deg,#fef2f2 0%,#fee2e2 100%); border-radius:12px; padding:1.5rem; box-shadow:0 2px 8px rgba(0,0,0,.08); }
    .totals-title { font-size:1rem; font-weight:700; color:#991b1b; margin-bottom:1rem; display:flex; align-items:center; gap:.5rem; }
    .total-item { display:flex; justify-content:space-between; align-items:center; padding:.75rem 0; border-bottom:1px solid #fecaca; }
    .total-item:last-child { border-bottom:none; padding-top:1rem; margin-top:.5rem; border-top:2px solid #dc2626; }
    .total-label { font-weight:600; color:#374151; }
    .total-value { font-weight:700; color:#dc2626; font-size:1.125rem; }
    .actions-bar { background:#fff; border-radius:12px; padding:1.5rem; display:flex; justify-content:space-between; align-items:center; box-shadow:0 2px 8px rgba(0,0,0,.08); margin-top:2rem; }
    .tipo-cambio-info { background:linear-gradient(135deg,#fffbeb 0%,#fef3c7 100%); border-left:4px solid #f59e0b; border-radius:8px; padding:1rem; margin-bottom:1rem; font-size:.875rem; }
    .search-container { position:relative; }
    .suggestions-list { position:absolute; top:100%; left:0; right:0; background:#fff; border:2px solid #dc2626; border-top:none; border-radius:0 0 10px 10px; max-height:300px; overflow-y:auto; z-index:10000; display:none; box-shadow:0 4px 12px rgba(0,0,0,.15); }
    .suggestions-list.show { display:block; }
    .suggestion-item { padding:.75rem 1rem; cursor:pointer; border-bottom:1px solid #f3f4f6; transition:.2s; display:flex; justify-content:space-between; align-items:center; }
    .suggestion-item:hover { background:#fef2f2; padding-left:1.25rem; }
    .suggestion-item.selected { background:#fee2e2; border-left:4px solid #dc2626; }
    .suggestion-codigo { font-weight:600; color:#dc2626; margin-right:.5rem; }
    .suggestion-descripcion { color:#374151; flex:1; }
    .suggestion-proveedor { color:#6b7280; font-size:.75rem; margin-left:.5rem; background:#f3f4f6; padding:2px 6px; border-radius:6px; white-space:nowrap; }
    .item-code-badge { display:inline-block; padding:.25rem .5rem; border-radius:6px; background:#fee2e2; color:#991b1b; font-weight:700; font-size:.8125rem; margin-right:.5rem; }
    .item-desc { color:#111827; font-weight:600; }
    .search-container .form-control, .search-container .input-group-text { position:relative; z-index:2; }
</style>

@php
    $iso = optional($compra->moneda)->codigo_iso ?? 'PEN';
    $simboloISO = $iso === 'USD' ? 'USD' : 'PEN';
    $simboloChar = $iso === 'USD' ? '$' : 'S/';
    $igvChecked = ($compra->igv ?? 0) > 0;
@endphp

<div class="container-fluid px-4 py-4">
    <div class="page-header">
        <div>
            <nav aria-label="breadcrumb" class="breadcrumb-custom mb-2">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('compras.index') }}"><i class="fas fa-shopping-cart me-1"></i>Compras</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </nav>
            <h1 class="page-title">
                <i class="fas fa-edit"></i>
                Editar Compra #{{ $compra->id_compra }}
            </h1>
        </div>
    </div>

    <form action="{{ route('compras.update', $compra->id_compra) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="id_proveedor" id="id_proveedor_hidden" value="{{ $compra->id_proveedor }}">

        <div class="card-modern">
            <div class="card-header-gradient">
                <h5><i class="fas fa-info-circle"></i> Información General</h5>
            </div>
            <div class="card-body-custom">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label for="buscadorProveedor" class="form-label"><i class="fas fa-truck"></i> Proveedor</label>
                        <div class="search-container">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-search"></i></span>
                                <input type="text" id="buscadorProveedor" class="form-control" placeholder="Buscar proveedor por razón social o RUC..." autocomplete="off">
                                <button type="button" class="btn btn-outline-secondary" onclick="limpiarProveedor()" title="Limpiar proveedor"><i class="fas fa-times"></i></button>
                            </div>
                            <div id="proveedorSuggestions" class="suggestions-list"></div>
                            <div id="proveedorSeleccionadoInfo" class="mt-2" style="display: {{ $compra->proveedor ? 'block' : 'none' }}; font-size:.85rem;">
                                <span class="badge bg-secondary"><i class="fas fa-truck me-1"></i><span id="proveedorSeleccionadoTexto">{{ optional($compra->proveedor)->razon_social }} {{ optional($compra->proveedor)->numero_documento ? '• RUC: '.optional($compra->proveedor)->numero_documento : '' }}</span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="id_moneda" class="form-label"><i class="fas fa-coins"></i> Moneda</label>
                        <select name="id_moneda" id="id_moneda" class="form-select" required>
                            <option value="">Seleccione una moneda</option>
                            @foreach($monedas as $moneda)
                                <option value="{{ $moneda->id_moneda }}" {{ $moneda->id_moneda == $compra->id_moneda ? 'selected' : '' }} style="{{ ($moneda->codigo_iso == 'USD') ? 'background-color:#e8f5e9; font-weight:bold;' : '' }}">{{ $moneda->simbolo }} {{ $moneda->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="fecha" class="form-label"><i class="fas fa-calendar-alt"></i> Fecha</label>
                        <input type="date" name="fecha" id="fecha" class="form-control" value="{{ date('Y-m-d', strtotime($compra->fecha)) }}" required>
                    </div>
                </div>
                <div class="tipo-cambio-info mt-3"><i class="fas fa-exchange-alt"></i> <span class="me-2">Tipo de cambio manual:</span> <input type="number" step="0.0001" min="0" id="tipoCambioManualCompras" name="tipo_cambio_manual" class="form-control form-control-sm d-inline-block" style="width:140px;" placeholder="S/ 3.8000"> <span class="ms-2 text-muted">(Manual)</span></div>
            </div>
        </div>

        <div class="card-modern">
            <div class="card-header-gradient">
                <h5><i class="fas fa-boxes"></i> Productos de la Compra</h5>
            </div>
            <div class="card-body-custom">
                <div class="mb-3 search-container">
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-search"></i></span>
                        <input type="text" id="buscadorProducto" class="form-control" placeholder="Buscar producto por código, descripción o proveedor..." autocomplete="off" onkeyup="buscarProducto(event)" onfocus="buscarProducto(event)">
                    </div>
                    <div id="suggestionsList" class="suggestions-list"></div>
                </div>

                <div class="table-responsive">
                    <table class="table table-productos mb-3" id="productos-table">
                        <thead>
                            <tr>
                                <th style="width:40%;">Producto</th>
                                <th style="width:20%;">Cantidad</th>
                                <th style="width:30%;">Precio Unitario <span id="detalleMonedaBadgeHeader" class="badge bg-secondary">{{ $simboloISO }}</span></th>
                                <th style="width:10%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 0; @endphp
                            @forelse($compra->detalles as $det)
                                @php $prod = $det->producto; @endphp
                                <tr>
                                    <td data-label="Producto">
                                        <div>
                                            @if($prod && $prod->codigo)
                                                <span class="item-code-badge">{{ $prod->codigo }}</span>
                                            @endif
                                            <span class="item-desc">{{ $prod->descripcion ?? '—' }}</span>
                                            @if($prod && $prod->proveedor && $prod->proveedor->razon_social)
                                                <span class="suggestion-proveedor ms-1"><i class='fas fa-truck me-1'></i>{{ $prod->proveedor->razon_social }}</span>
                                            @endif
                                        </div>
                                        <input type="hidden" name="detalles[{{ $i }}][id_producto]" value="{{ $det->id_producto }}">
                                    </td>
                                    <td data-label="Cantidad">
                                        <input type="number" name="detalles[{{ $i }}][cantidad]" class="form-control form-control-sm" min="1" value="{{ (int) $det->cantidad }}" required>
                                    </td>
                                    <td data-label="Precio">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text" id="simboloDetalle{{ $i }}">{{ $simboloChar }}</span>
                                            <input type="number" step="0.01" name="detalles[{{ $i }}][precio_unitario]" class="form-control form-control-sm" value="{{ number_format((float) $det->precio_unitario, 2, '.', '') }}" required>
                                        </div>
                                    </td>
                                    <td data-label="Acciones" class="text-center">
                                        <button type="button" class="btn-remove-row" onclick="this.closest('tr').remove(); calcularTotales(); mostrarEstadoVacio();" title="Eliminar"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                @php $i++; @endphp
                            @empty
                                <tr class="empty-state-row">
                                    <td colspan="4">
                                        <div class="empty-state"><i class="fas fa-box-open"></i><span class="ms-2">No hay productos agregados. Usa el buscador superior para añadir ítems.</span></div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex gap-2">
                    <button type="button" class="btn-calculate" onclick="calcularTotales()"><i class="fas fa-calculator"></i> Calcular Totales</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 offset-lg-6">
                <div class="totals-card">
                    <div class="totals-title"><i class="fas fa-receipt"></i> Resumen de Compra <span id="tc-indicador" class="ms-2 text-muted" style="font-weight:600;"></span> <span id="badge-moneda" class="badge bg-primary ms-2">{{ $simboloISO }}</span></div>
                    <div class="total-item"><span class="total-label">Subtotal:</span><span class="total-value"><input type="text" name="subtotal" id="subtotal" class="form-control form-control-sm text-end" readonly style="display:inline-block; width:150px; border:none; background:transparent; font-weight:700; color:#dc2626;" value="{{ number_format((float) $compra->subtotal, 2) }}"></span></div>
                    <div class="total-item"><span class="total-label"><div class="form-check form-switch d-inline-block me-2"><input class="form-check-input" type="checkbox" id="incluirIGV" {{ $igvChecked ? 'checked' : '' }} onchange="calcularTotales()"></div> IGV (18%):</span><span class="total-value"><input type="text" name="igv" id="igv" class="form-control form-control-sm text-end" readonly style="display:inline-block; width:150px; border:none; background:transparent; font-weight:700; color:#dc2626;" value="{{ number_format((float) $compra->igv, 2) }}"></span></div>
                    <div class="total-item"><span class="total-label" style="font-size:1.125rem;">TOTAL:</span><span class="total-value"><input type="text" name="total" id="total" class="form-control text-end" readonly style="display:inline-block; width:180px; border:none; background:transparent; font-weight:700; color:#dc2626; font-size:1.5rem;" value="{{ number_format((float) $compra->total, 2) }}"></span></div>
                </div>
                <input type="hidden" name="moneda_codigo" id="moneda_codigo" value="{{ $simboloISO }}">
            </div>
        </div>

        <input type="hidden" name="incluir_igv" id="incluir_igv_hidden" value="{{ $igvChecked ? '1' : '0' }}">

        <div class="actions-bar">
            <a href="{{ route('compras.index') }}" class="btn-cancel"><i class="fas fa-times"></i> Cancelar</a>
            <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Guardar Cambios</button>
        </div>
    </form>
    </div>

<script>
let TIPO_CAMBIO = 3.80;
let allowGlobalSearch = false;
let productos = [];
let proveedores = [];
let selectedIndex = -1;
let selectedProveedorIndex = -1;

function aplicarTipoCambioManualCompras() {
    const inputTC = document.getElementById('tipoCambioManualCompras');
    const val = parseFloat(inputTC.value);
    if (!isNaN(val) && val > 0) { TIPO_CAMBIO = val; calcularTotales(); }
}

document.addEventListener('DOMContentLoaded', function() {
    const inputTC = document.getElementById('tipoCambioManualCompras');
    if (inputTC) { inputTC.value = TIPO_CAMBIO.toFixed(4); inputTC.addEventListener('input', aplicarTipoCambioManualCompras); }

    try {
        const rawProductos = @json($productos);
        productos = (rawProductos || []).map(p => ({ id: p.id_producto, codigo: p.codigo || '', descripcion: p.descripcion || '', id_proveedor: p.id_proveedor, proveedor: (p.proveedor_nombre || ''), proveedor_ruc: (p.proveedor_ruc || '') }));
    } catch (e) { productos = []; }
    try {
        const rawProveedores = @json($proveedores);
        proveedores = (rawProveedores || []).map(pr => ({ id: pr.id_proveedor, nombre: pr.razon_social || '', ruc: pr.numero_documento || '' }));
    } catch (e) { proveedores = []; }

    const buscadorProd = document.getElementById('buscadorProducto'); if (buscadorProd) { buscadorProd.disabled = false; buscadorProd.addEventListener('input', buscarProducto); }
    const buscadorProv = document.getElementById('buscadorProveedor'); if (buscadorProv) { buscadorProv.disabled = false; buscadorProv.addEventListener('input', buscarProveedor); }

    actualizarDetallesMonedaVisual();
    calcularTotales();
});

function calcularTotales() {
    if (!TIPO_CAMBIO || TIPO_CAMBIO <= 0) { TIPO_CAMBIO = 3.38; }
    let subtotal = 0;
    document.querySelectorAll('#productos-table tbody tr').forEach(function(row) {
        const cantInput = row.querySelector('input[name$="[cantidad]"]');
        const precInput = row.querySelector('input[name$="[precio_unitario]"]');
        if (!cantInput || !precInput) return;
        const cantidad = parseFloat(cantInput.value) || 0;
        const precio = parseFloat(precInput.value) || 0;
        subtotal += cantidad * precio;
    });
    const incluirIGV = document.getElementById('incluirIGV').checked;
    const igv = incluirIGV ? subtotal * 0.18 : 0;
    const total = subtotal + igv;
    document.getElementById('incluir_igv_hidden').value = incluirIGV ? '1' : '0';
    document.getElementById('subtotal').value = subtotal.toFixed(2);
    document.getElementById('igv').value = igv.toFixed(2);
    document.getElementById('total').value = total.toFixed(2);

    const monedaSelect = document.getElementById('id_moneda');
    const selectedMoneda = monedaSelect.options[monedaSelect.selectedIndex]?.text?.toLowerCase() || '';
    const simboloBase = (selectedMoneda.includes('dólar') || selectedMoneda.includes('usd') || selectedMoneda.includes('dolar')) ? 'USD' : 'PEN';
    const tcIndicador = document.getElementById('tc-indicador'); tcIndicador.textContent = `(Moneda base: ${simboloBase} • TC manual: S/ ${TIPO_CAMBIO.toFixed(4)})`;
    const badgeMoneda = document.getElementById('badge-moneda'); const hiddenCodigo = document.getElementById('moneda_codigo');
    if (badgeMoneda) badgeMoneda.textContent = simboloBase; if (hiddenCodigo) hiddenCodigo.value = simboloBase;
}

document.getElementById('productos-table').addEventListener('input', function(e) {
    if (e.target.matches('input[name$="[cantidad]"]') || e.target.matches('input[name$="[precio_unitario]"]')) { calcularTotales(); }
});

document.getElementById('id_moneda').addEventListener('change', function(){ actualizarDetallesMonedaVisual(); calcularTotales(); });

function actualizarDetallesMonedaVisual() {
    const monedaSelect = document.getElementById('id_moneda');
    const selectedMoneda = monedaSelect.options[monedaSelect.selectedIndex]?.text?.toLowerCase() || '';
    const simboloISO = (selectedMoneda.includes('dólar') || selectedMoneda.includes('usd') || selectedMoneda.includes('dolar')) ? 'USD' : 'PEN';
    const simboloChar = simboloISO === 'USD' ? '$' : 'S/';
    document.querySelectorAll('#productos-table tbody tr input[name$="[precio_unitario]"]').forEach(function(input){ input.placeholder = `${simboloChar} 0.00`; input.setAttribute('data-moneda', simboloISO); });
    const badgeHeader = document.getElementById('detalleMonedaBadgeHeader'); if (badgeHeader) badgeHeader.textContent = simboloISO;
    document.querySelectorAll('#productos-table tbody tr [id^="simboloDetalle"]').forEach(function(span){ span.textContent = simboloChar; });
}

function agregarFilaProducto(idProducto, codigo, descripcion) {
    const tbody = document.querySelector('#productos-table tbody');
    const emptyRowPre = tbody.querySelector('.empty-state-row'); if (emptyRowPre) emptyRowPre.remove();
    const index = Array.from(tbody.querySelectorAll('tr')).filter(tr => !tr.classList.contains('empty-state-row')).length;
    const row = document.createElement('tr');

    const monedaSelect = document.getElementById('id_moneda');
    const selectedMoneda = monedaSelect.options[monedaSelect.selectedIndex]?.text?.toLowerCase() || '';
    const simboloISO = (selectedMoneda.includes('dólar') || selectedMoneda.includes('usd') || selectedMoneda.includes('dolar')) ? 'USD' : 'PEN';
    const simboloChar = simboloISO === 'USD' ? '$' : 'S/';

    const hiddenProv = document.getElementById('id_proveedor_hidden');
    const prod = productos.find(p => p.id === idProducto);
    const provId = prod?.id_proveedor || null;
    if (provId && !hiddenProv.value) {
        const prov = (typeof proveedores !== 'undefined') ? proveedores.find(pp => String(pp.id) === String(provId)) : null;
        if (prov) { setProveedorUI(provId, prov?.nombre || '', prov?.ruc || ''); }
    }

    const provName = prod?.proveedor || '';
    row.innerHTML = `
        <td data-label="Producto">
            <div>
                ${codigo ? `<span class=\"item-code-badge\">${codigo}</span>` : ''}<span class=\"item-desc\">${descripcion}</span>
                ${provName ? `<span class=\"suggestion-proveedor ms-1\"><i class='fas fa-truck me-1'></i>${provName}</span>` : ''}
            </div>
            <input type="hidden" name="detalles[${index}][id_producto]" value="${idProducto}">
        </td>
        <td data-label="Cantidad">
            <input type="number" name="detalles[${index}][cantidad]" class="form-control form-control-sm" min="1" value="1" required>
        </td>
        <td data-label="Precio">
            <div class="input-group input-group-sm">
                <span class="input-group-text" id="simboloDetalle${index}">${simboloChar}</span>
                <input type="number" step="0.01" name="detalles[${index}][precio_unitario]" class="form-control form-control-sm" value="0" required>
            </div>
        </td>
        <td data-label="Acciones" class="text-center">
            <button type="button" class="btn-remove-row" onclick="this.closest('tr').remove(); calcularTotales(); mostrarEstadoVacio();" title="Eliminar">
                <i class="fas fa-trash"></i>
            </button>
        </td>`;
    tbody.appendChild(row);
    calcularTotales();
    mostrarEstadoVacio();
}

function buscarProducto(event) {
    const input = document.getElementById('buscadorProducto');
    const suggestionsList = document.getElementById('suggestionsList');
    const busqueda = (input.value || '').toLowerCase().trim();
    if (event.key === 'ArrowDown') { event.preventDefault(); selectedIndex = Math.min(selectedIndex + 1, suggestionsList.children.length - 1); actualizarSeleccion(); return; }
    if (event.key === 'ArrowUp') { event.preventDefault(); selectedIndex = Math.max(selectedIndex - 1, 0); actualizarSeleccion(); return; }
    if (event.key === 'Enter') { event.preventDefault(); if (selectedIndex >= 0) { const selectedItem = suggestionsList.children[selectedIndex]; if (selectedItem) selectedItem.click(); } return; }
    if (event.key === 'Escape') { suggestionsList.classList.remove('show'); selectedIndex = -1; return; }
    selectedIndex = -1;
    if (!busqueda) { suggestionsList.classList.remove('show'); return; }
    const hiddenProv = document.getElementById('id_proveedor_hidden');
    const provFilter = (hiddenProv && hiddenProv.value && !allowGlobalSearch) ? String(hiddenProv.value) : '';
    const resultados = productos.filter(producto => {
        const codigo = (producto.codigo || '').toLowerCase();
        const descripcion = (producto.descripcion || '').toLowerCase();
        const proveedor = (producto.proveedor || '').toLowerCase();
        const matchTexto = codigo.includes(busqueda) || descripcion.includes(busqueda) || proveedor.includes(busqueda);
        const matchProv = provFilter ? String(producto.id_proveedor) === provFilter : true;
        return matchTexto && matchProv;
    });
    if (resultados.length > 0) {
        suggestionsList.innerHTML = resultados.map((producto, index) => `
            <div class="suggestion-item" data-index="${index}" onclick="seleccionarProducto(${producto.id}, '${producto.codigo}', '${producto.descripcion.replace(/'/g, "\\'")}')">
                <div class="d-flex align-items-center flex-wrap" style="gap:6px;">
                    ${producto.codigo ? `<span class="suggestion-codigo">${producto.codigo}</span>` : ''}
                    <span class="suggestion-descripcion">${producto.descripcion}</span>
                    ${producto.proveedor ? `<span class="suggestion-proveedor"><i class='fas fa-truck me-1'></i>${producto.proveedor}</span>` : ''}
                </div>
                <i class="fas fa-plus-circle text-success"></i>
            </div>
        `).join('');
        suggestionsList.classList.add('show');
    } else {
        suggestionsList.innerHTML = '<div class="no-results"><i class="fas fa-search"></i> No se encontraron productos</div>';
        suggestionsList.classList.add('show');
    }
}

function actualizarSeleccion() {
    const suggestionsList = document.getElementById('suggestionsList');
    const items = suggestionsList.querySelectorAll('.suggestion-item');
    items.forEach((item, index) => { if (index === selectedIndex) { item.classList.add('selected'); item.scrollIntoView({ block:'nearest', behavior:'smooth' }); } else { item.classList.remove('selected'); } });
}

function seleccionarProducto(id, codigo, descripcion) {
    agregarFilaProducto(id, codigo, descripcion);
    document.getElementById('buscadorProducto').value = '';
    document.getElementById('suggestionsList').classList.remove('show');
    selectedIndex = -1;
}

function mostrarEstadoVacio() {
    const tbody = document.querySelector('#productos-table tbody');
    const filasReal = Array.from(tbody.children).filter(tr => !tr.classList.contains('empty-state-row'));
    if (filasReal.length === 0 && !tbody.querySelector('.empty-state-row')) {
        const tr = document.createElement('tr'); tr.className = 'empty-state-row'; tr.innerHTML = `<td colspan="4"><div class="empty-state"><i class=\"fas fa-box-open\"></i><span class=\"ms-2\">No hay productos agregados. Usa el buscador superior para añadir ítems.</span></div></td>`; tbody.appendChild(tr);
    }
}

document.addEventListener('click', function(event) {
    const containers = document.querySelectorAll('.search-container');
    const isInside = Array.from(containers).some(c => c.contains(event.target));
    if (!isInside) {
        const listProd = document.getElementById('suggestionsList');
        const listProv = document.getElementById('proveedorSuggestions');
        if (listProd) listProd.classList.remove('show');
        if (listProv) listProv.classList.remove('show');
        selectedIndex = -1;
        selectedProveedorIndex = -1;
    }
});

function setProveedorUI(id, nombre, ruc) {
    const hidden = document.getElementById('id_proveedor_hidden'); hidden.value = id || '';
    const info = document.getElementById('proveedorSeleccionadoInfo'); const texto = document.getElementById('proveedorSeleccionadoTexto');
    if (id) { texto.textContent = `${nombre || ''}${ruc ? ' • RUC: ' + ruc : ''}`; info.style.display = 'block'; const input = document.getElementById('buscadorProveedor'); input.value = nombre || ''; }
    else { texto.textContent = ''; info.style.display = 'none'; document.getElementById('buscadorProveedor').value = ''; }
}

function buscarProveedor(event) {
    const input = document.getElementById('buscadorProveedor'); const list = document.getElementById('proveedorSuggestions'); const q = (input.value || '').toLowerCase().trim();
    if (event.key === 'ArrowDown') { event.preventDefault(); selectedProveedorIndex = Math.min(selectedProveedorIndex + 1, list.children.length - 1); actualizarSeleccionProveedor(); return; }
    if (event.key === 'ArrowUp') { event.preventDefault(); selectedProveedorIndex = Math.max(selectedProveedorIndex - 1, 0); actualizarSeleccionProveedor(); return; }
    if (event.key === 'Enter') { event.preventDefault(); if (selectedProveedorIndex >= 0) { const item = list.children[selectedProveedorIndex]; if (item) item.click(); } return; }
    if (event.key === 'Escape') { list.classList.remove('show'); selectedProveedorIndex = -1; return; }
    selectedProveedorIndex = -1; if (!q) { list.classList.remove('show'); return; }
    const res = proveedores.filter(p => ((p.nombre || '').toLowerCase().includes(q) || (p.ruc || '').toLowerCase().includes(q)));
    if (res.length) {
        list.innerHTML = res.map((p, idx) => `
            <div class="suggestion-item" data-index="${idx}" onclick="seleccionarProveedor(${p.id}, '${(p.nombre || '').replace(/'/g, "\\'")}', '${p.ruc || ''}')">
                <div class="d-flex align-items-center flex-wrap" style="gap:6px;">
                    <span class="suggestion-descripcion"><i class='fas fa-truck me-1'></i>${p.nombre || '—'}</span>
                    ${p.ruc ? `<span class='suggestion-proveedor'>RUC: ${p.ruc}</span>` : ''}
                </div>
                <i class="fas fa-check text-success"></i>
            </div>
        `).join(''); list.classList.add('show');
    } else { list.innerHTML = '<div class="no-results"><i class="fas fa-search"></i> No se encontraron proveedores</div>'; list.classList.add('show'); }
}

function actualizarSeleccionProveedor() {
    const list = document.getElementById('proveedorSuggestions'); const items = list.querySelectorAll('.suggestion-item');
    items.forEach((el, i) => { if (i === selectedProveedorIndex) { el.classList.add('selected'); el.scrollIntoView({ block:'nearest', behavior:'smooth' }); } else { el.classList.remove('selected'); } });
}

function seleccionarProveedor(id, nombre, ruc) { setProveedorUI(id, nombre, ruc); allowGlobalSearch = false; document.getElementById('proveedorSuggestions').classList.remove('show'); selectedProveedorIndex = -1; }
function limpiarProveedor() { setProveedorUI('', '', ''); allowGlobalSearch = true; }
</script>

@endsection
