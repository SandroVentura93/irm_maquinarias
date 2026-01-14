@extends('layouts.dashboard')

@section('content')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 3px solid #dc2626;
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 0;
    }

    .breadcrumb-custom {
        background: transparent;
        padding: 0;
        margin: 0;
        font-size: 0.875rem;
    }

    .breadcrumb-custom a {
        color: #dc2626;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .breadcrumb-custom a:hover {
        color: #991b1b;
    }

    .card-modern {
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .card-header-gradient {
        background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
        color: white;
        padding: 1.25rem 1.5rem;
        border: none;
    }

    .card-header-gradient h5 {
        margin: 0;
        font-weight: 600;
        font-size: 1.125rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .card-body-custom {
        padding: 2rem;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.375rem;
        font-size: 0.875rem;
    }

    .form-label i {
        color: #dc2626;
        font-size: 0.875rem;
    }

    .form-control, .form-select {
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        padding: 0.625rem 1rem;
        transition: all 0.3s ease;
        font-size: 0.9375rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: #dc2626;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    .form-control:read-only {
        background-color: #f9fafb;
        color: #6b7280;
        font-weight: 600;
    }

    .table-productos {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .table-productos thead {
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
    }

    .table-productos thead th {
        border: none;
        padding: 1rem;
        font-weight: 600;
        color: #991b1b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.8125rem;
    }

    .table-productos tbody td {
        padding: 0.75rem;
        vertical-align: middle;
        border-bottom: 1px solid #f3f4f6;
    }

    .table-productos tbody tr:last-child td {
        border-bottom: none;
    }

    .table-productos tbody tr {
        transition: background-color 0.2s ease;
    }

    .table-productos tbody tr:hover {
        background-color: #fef2f2;
    }

    .btn-add-product {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 0.625rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);
    }

    .btn-add-product:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(16, 185, 129, 0.3);
        color: white;
    }

    .btn-remove-row {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        border: none;
        border-radius: 8px;
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-remove-row:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
    }

    .btn-calculate {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 0.625rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);
    }

    .btn-calculate:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
        color: white;
    }

    .btn-submit {
        background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 0.75rem 2.5rem;
        font-weight: 700;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 6px rgba(220, 38, 38, 0.3);
        font-size: 1rem;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(220, 38, 38, 0.4);
        color: white;
    }

    .btn-cancel {
        background: #6b7280;
        color: white;
        border: none;
        border-radius: 10px;
        padding: 0.75rem 2.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-cancel:hover {
        background: #4b5563;
        transform: translateY(-2px);
        color: white;
    }

    .totals-card {
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .totals-title {
        font-size: 1rem;
        font-weight: 700;
        color: #991b1b;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .total-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #fecaca;
    }

    .total-item:last-child {
        border-bottom: none;
        padding-top: 1rem;
        margin-top: 0.5rem;
        border-top: 2px solid #dc2626;
    }

    .total-label {
        font-weight: 600;
        color: #374151;
    }

    .total-value {
        font-weight: 700;
        color: #dc2626;
        font-size: 1.125rem;
    }

    .total-item:last-child .total-value {
        font-size: 1.5rem;
    }

    .actions-bar {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        margin-top: 2rem;
    }

    .tipo-cambio-info {
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        border-left: 4px solid #f59e0b;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        font-size: 0.875rem;
    }

    .tipo-cambio-info i {
        color: #f59e0b;
        margin-right: 0.5rem;
    }

    /* Estilos para el buscador de productos */
    .search-container {
        position: relative;
    }

    .suggestions-list {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 2px solid #dc2626;
        border-top: none;
        border-radius: 0 0 10px 10px;
        max-height: 300px;
        overflow-y: auto;
        z-index: 10000;
        display: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .suggestions-list.show {
        display: block;
    }

    .suggestion-item {
        padding: 0.75rem 1rem;
        cursor: pointer;
        border-bottom: 1px solid #f3f4f6;
        transition: all 0.2s ease;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .suggestion-item:last-child {
        border-bottom: none;
    }

    .suggestion-item:hover {
        background-color: #fef2f2;
        padding-left: 1.25rem;
    }

    .suggestion-item.selected {
        background-color: #fee2e2;
        border-left: 4px solid #dc2626;
    }

    .suggestion-codigo {
        font-weight: 600;
        color: #dc2626;
        margin-right: 0.5rem;
    }

    .suggestion-descripcion {
        color: #374151;
        flex: 1;
    }

    .suggestion-proveedor {
        color: #6b7280;
        font-size: 0.75rem;
        margin-left: 0.5rem;
        background: #f3f4f6;
        padding: 2px 6px;
        border-radius: 6px;
        white-space: nowrap;
    }

    .no-results {
        padding: 1rem;
        text-align: center;
        color: #6b7280;
        font-style: italic;
    }

    /* Items UI mejorado */
    .item-code-badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        background: #fee2e2;
        color: #991b1b;
        font-weight: 700;
        font-size: 0.8125rem;
        margin-right: 0.5rem;
    }
    .item-desc {
        color: #111827;
        font-weight: 600;
    }
    .empty-state {
        background: #f9fafb;
        border: 2px dashed #e5e7eb;
        border-radius: 12px;
        padding: 1rem;
        text-align: center;
        color: #6b7280;
    }
    .empty-state i { color: #9ca3af; }

    /* Garantizar que los inputs de búsqueda siempre sean interactivos y sobrepongan sugerencias correctamente */
    .search-container .form-control { position: relative; z-index: 2; }
    .search-container .input-group-text { position: relative; z-index: 2; }

    @media (max-width: 768px) {
        .actions-bar {
            flex-direction: column;
            gap: 1rem;
        }
    }

    /* Mobile: stack form and product table */
    @media (max-width: 768px) {
        .card-body-custom { padding: 1rem; }
        .form-control, .form-select { width: 100%; }
        .table-productos thead { display: none; }
        .table-productos, .table-productos tbody, .table-productos tr, .table-productos td { display: block; width: 100%; }
        .table-productos tr { margin-bottom: 0.75rem; border-bottom: 1px solid #eee; padding-bottom: 0.5rem; }
        .table-productos td { padding: 0.5rem 0; display: flex; justify-content: space-between; align-items: center; }
        .table-productos td:before { content: attr(data-label); font-weight: 600; color: #6b7280; margin-right: 0.5rem; }
        .btn-add-product, .btn-calculate, .btn-submit, .btn-cancel { width: 100%; justify-content: center; }
        .input-group-text { min-width: auto; }
    }
</style>

<div class="container-fluid px-4 py-4">
    <div class="page-header">
        <div>
            <nav aria-label="breadcrumb" class="breadcrumb-custom mb-2">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('compras.index') }}"><i class="fas fa-shopping-cart me-1"></i>Compras</a></li>
                    <li class="breadcrumb-item active">Nueva</li>
                </ol>
            </nav>
            <h1 class="page-title">
                <i class="fas fa-plus-circle"></i>
                Registrar Nueva Compra
            </h1>
        </div>
    </div>

    <form action="{{ route('compras.store') }}" method="POST">
        @csrf
        <!-- Proveedor oculto: se deriva del primer producto agregado -->
        <input type="hidden" name="id_proveedor" id="id_proveedor_hidden" value="">

        <!-- Información General -->
        <div class="card-modern">
            <div class="card-header-gradient">
                <h5>
                    <i class="fas fa-info-circle"></i>
                    Información General
                </h5>
            </div>
            <div class="card-body-custom">
                <div class="row g-3">
                    <!-- Buscador de Proveedor -->
                    <div class="col-md-8">
                        <label for="buscadorProveedor" class="form-label">
                            <i class="fas fa-truck"></i>
                            Proveedor
                        </label>
                        <div class="search-container">
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" id="buscadorProveedor" class="form-control" placeholder="Buscar proveedor por razón social o RUC..." 
                                       autocomplete="off" onkeyup="buscarProveedor(event)" onfocus="buscarProveedor(event)">
                                <button type="button" class="btn btn-outline-secondary" onclick="limpiarProveedor()" title="Limpiar proveedor">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div id="proveedorSuggestions" class="suggestions-list"></div>
                            <div id="proveedorSeleccionadoInfo" class="mt-2" style="display:none; font-size: 0.85rem;">
                                <span class="badge bg-secondary"><i class="fas fa-truck me-1"></i><span id="proveedorSeleccionadoTexto"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="id_moneda" class="form-label">
                            <i class="fas fa-coins"></i>
                            Moneda
                        </label>
                        <select name="id_moneda" id="id_moneda" class="form-select" required>
                            <option value="">Seleccione una moneda</option>
                            @foreach($monedas as $moneda)
                                <option value="{{ $moneda->id_moneda }}"
                                    {{ ($moneda->codigo_iso == 'USD') ? 'selected' : '' }}
                                    style="{{ ($moneda->codigo_iso == 'USD') ? 'background-color: #e8f5e9; font-weight: bold;' : '' }}">
                                    {{ $moneda->simbolo }} {{ $moneda->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="fecha" class="form-label">
                            <i class="fas fa-calendar-alt"></i>
                            Fecha
                        </label>
                        <input type="date" name="fecha" id="fecha" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                <!-- Tipo de Cambio (Manual) -->
                <div class="tipo-cambio-info mt-3">
                    <i class="fas fa-exchange-alt"></i>
                    <span class="me-2">Tipo de cambio manual:</span>
                    <input type="number" step="0.0001" min="0" id="tipoCambioManualCompras" name="tipo_cambio_manual" class="form-control form-control-sm d-inline-block" style="width: 140px;" placeholder="S/ 3.8000">
                    <span class="ms-2 text-muted" title="Valor ingresado manualmente">(Manual)</span>
                </div>
            </div>
        </div>

        <!-- Productos -->
        <div class="card-modern">
            <div class="card-header-gradient">
                <h5>
                    <i class="fas fa-boxes"></i>
                    Productos de la Compra
                </h5>
            </div>
            <div class="card-body-custom">
                <!-- Buscador de productos -->
                <div class="mb-3 search-container">
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" id="buscadorProducto" class="form-control" placeholder="Buscar producto por código o descripción..." 
                               autocomplete="off" onkeyup="buscarProducto(event)" onfocus="buscarProducto(event)">
                    </div>
                    <div id="suggestionsList" class="suggestions-list"></div>
                </div>

                <div class="table-responsive">
                    <table class="table table-productos mb-3" id="productos-table">
                        <thead>
                            <tr>
                                <th style="width: 40%;">Producto</th>
                                <th style="width: 20%;">Cantidad</th>
                                <th style="width: 30%;">Precio Unitario <span id="detalleMonedaBadgeHeader" class="badge bg-secondary">USD</span></th>
                                <th style="width: 10%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Estado vacío inicial -->
                            <tr class="empty-state-row">
                                <td colspan="4">
                                    <div class="empty-state">
                                        <i class="fas fa-box-open"></i>
                                        <span class="ms-2">No hay productos agregados. Usa el buscador superior para añadir ítems.</span>
                                    </div>
                                </td>
                            </tr>
                            <!-- Las filas se agregarán solo mediante el buscador -->
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="button" class="btn-calculate" onclick="calcularTotales()">
                        <i class="fas fa-calculator"></i>
                        Calcular Totales
                    </button>
                </div>
            </div>
        </div>

        <!-- Totales -->
        <div class="row">
            <div class="col-lg-6 offset-lg-6">
                <div class="totals-card">
                    <div class="totals-title">
                        <i class="fas fa-receipt"></i>
                        Resumen de Compra
                        <span id="tc-indicador" class="ms-2 text-muted" style="font-weight:600;"></span>
                        <span id="badge-moneda" class="badge bg-primary ms-2">USD</span>
                    </div>
                    
                    <div class="total-item">
                        <span class="total-label">Subtotal:</span>
                        <span class="total-value">
                            <input type="text" name="subtotal" id="subtotal" class="form-control form-control-sm text-end" readonly 
                                   style="display: inline-block; width: 150px; border: none; background: transparent; font-weight: 700; color: #dc2626;">
                        </span>
                    </div>
                    
                    <div class="total-item">
                        <span class="total-label">
                            <div class="form-check form-switch d-inline-block me-2">
                                <input class="form-check-input" type="checkbox" id="incluirIGV" checked onchange="calcularTotales()">
                            </div>
                            IGV (18%):
                        </span>
                        <span class="total-value">
                            <input type="text" name="igv" id="igv" class="form-control form-control-sm text-end" readonly 
                                   style="display: inline-block; width: 150px; border: none; background: transparent; font-weight: 700; color: #dc2626;">
                        </span>
                    </div>
                    
                    <div class="total-item">
                        <span class="total-label" style="font-size: 1.125rem;">TOTAL:</span>
                        <span class="total-value">
                            <input type="text" name="total" id="total" class="form-control text-end" readonly 
                                   style="display: inline-block; width: 180px; border: none; background: transparent; font-weight: 700; color: #dc2626; font-size: 1.5rem;">
                        </span>
                    </div>
                </div>

                <!-- Conversión a Soles cuando la moneda es USD -->
                <div id="totales-soles" style="display:none;" class="mt-3">
                    <div class="totals-title">
                        <i class="fas fa-money-bill-wave"></i>
                        Conversión a Soles (TC manual)
                    </div>
                    <div class="total-item">
                        <span class="total-label">Subtotal (S/):</span>
                        <span class="total-value">
                            <input type="text" id="subtotal_pen" class="form-control form-control-sm text-end" readonly 
                                   style="display: inline-block; width: 150px; border: none; background: transparent; font-weight: 700; color: #dc2626;">
                        </span>
                    </div>
                    <div class="total-item">
                        <span class="total-label">IGV (S/):</span>
                        <span class="total-value">
                            <input type="text" id="igv_pen" class="form-control form-control-sm text-end" readonly 
                                   style="display: inline-block; width: 150px; border: none; background: transparent; font-weight: 700; color: #dc2626;">
                        </span>
                    </div>
                    <div class="total-item">
                        <span class="total-label">Total (S/):</span>
                        <span class="total-value">
                            <input type="text" id="total_pen" class="form-control text-end" readonly 
                                   style="display: inline-block; width: 180px; border: none; background: transparent; font-weight: 800; color: #dc2626; font-size: 1.25rem;">
                        </span>
                    </div>
                </div>

                <!-- Totales en USD (se muestra si la moneda es dólar) -->
                <div id="totales-dolares" style="display:none;" class="mt-3">
                    <div class="totals-card" style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);">
                        <div class="totals-title" style="color: #d97706;">
                            <i class="fas fa-dollar-sign"></i>
                            Conversión a Dólares (USD)
                        </div>
                        
                        <div class="total-item" style="border-bottom-color: #fde68a;">
                            <span class="total-label">Subtotal USD:</span>
                            <span class="total-value" style="color: #d97706;">
                                <input type="text" id="subtotal_usd" class="form-control form-control-sm text-end" readonly 
                                       style="display: inline-block; width: 150px; border: none; background: transparent; font-weight: 700; color: #d97706;">
                            </span>
                        </div>
                        
                        <div class="total-item" style="border-bottom-color: #fde68a;">
                            <span class="total-label">IGV USD:</span>
                            <span class="total-value" style="color: #d97706;">
                                <input type="text" id="igv_usd" class="form-control form-control-sm text-end" readonly 
                                       style="display: inline-block; width: 150px; border: none; background: transparent; font-weight: 700; color: #d97706;">
                            </span>
                        </div>
                        
                        <div class="total-item" style="border-top-color: #f59e0b;">
                            <span class="total-label" style="font-size: 1.125rem;">TOTAL USD:</span>
                            <span class="total-value" style="color: #d97706;">
                                <input type="text" id="total_usd" class="form-control text-end" readonly 
                                       style="display: inline-block; width: 180px; border: none; background: transparent; font-weight: 700; color: #d97706; font-size: 1.5rem;">
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Campo oculto para enviar el código de moneda seleccionado -->
                <input type="hidden" name="moneda_codigo" id="moneda_codigo" value="USD">
            </div>
        </div>

        <!-- Campo oculto para incluir_igv -->
        <input type="hidden" name="incluir_igv" id="incluir_igv_hidden" value="1">

        <!-- Acciones -->
        <div class="actions-bar">
            <a href="{{ route('compras.index') }}" class="btn-cancel">
                <i class="fas fa-times"></i>
                Cancelar
            </a>
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i>
                Registrar Compra
            </button>
        </div>
    </form>
</div>

<script>
let TIPO_CAMBIO = 3.80; // Valor inicial sugerido; editable por el usuario
// Flag para permitir búsquedas globales sin filtrar por proveedor cuando el usuario limpia manualmente
let allowGlobalSearch = false;

// Aplicar el tipo de cambio ingresado manualmente
function aplicarTipoCambioManualCompras() {
    const inputTC = document.getElementById('tipoCambioManualCompras');
    const val = parseFloat(inputTC.value);
    if (!isNaN(val) && val > 0) {
        TIPO_CAMBIO = val;
        console.log('Tipo de cambio manual aplicado:', TIPO_CAMBIO.toFixed(4));
        calcularTotales();
    }
}

// Inicializar al cargar
document.addEventListener('DOMContentLoaded', function() {
    const inputTC = document.getElementById('tipoCambioManualCompras');
    if (inputTC) {
        inputTC.value = TIPO_CAMBIO.toFixed(4);
        inputTC.addEventListener('input', aplicarTipoCambioManualCompras);
    }

    // Inicializar buscador con todos los productos desde el backend
    try {
        const rawProductos = @json($productos);
        productos = (rawProductos || []).map(p => ({
            id: p.id_producto,
            codigo: p.codigo || '',
            descripcion: p.descripcion || '',
            id_proveedor: p.id_proveedor,
            proveedor: (p.proveedor_nombre || ''),
            proveedor_ruc: (p.proveedor_ruc || '')
        }));
    } catch (e) {
        console.warn('No se pudieron inicializar los productos desde el backend');
        productos = [];
    }

    // Inicializar proveedores para el buscador de proveedor
    try {
        const rawProveedores = @json($proveedores);
        proveedores = (rawProveedores || []).map(pr => ({
            id: pr.id_proveedor,
            nombre: pr.razon_social || '',
            ruc: pr.numero_documento || ''
        }));
    } catch (e) {
        console.warn('No se pudieron inicializar los proveedores desde el backend');
        proveedores = [];
    }

    // Asegurar que inputs de búsqueda estén habilitados
    const buscadorProd = document.getElementById('buscadorProducto');
    if (buscadorProd) {
        buscadorProd.disabled = false;
        buscadorProd.addEventListener('input', buscarProducto);
    }
    const buscadorProv = document.getElementById('buscadorProveedor');
    if (buscadorProv) {
        buscadorProv.disabled = false;
        buscadorProv.addEventListener('input', buscarProveedor);
    }

    calcularTotales();
    // Inicializar detalles con el símbolo de moneda seleccionado
    actualizarDetallesMonedaVisual();
});

function calcularTotales() {
    // Validar que tenemos un tipo de cambio válido
    if (!TIPO_CAMBIO || TIPO_CAMBIO <= 0) {
        console.warn('⚠ Tipo de cambio no válido, usando 3.38 como fallback');
        TIPO_CAMBIO = 3.38;
    }
    
    let subtotal = 0;
    document.querySelectorAll('#productos-table tbody tr').forEach(function(row) {
        const cantidad = parseFloat(row.querySelector('input[name$="[cantidad]"]').value) || 0;
        const precio = parseFloat(row.querySelector('input[name$="[precio_unitario]"]').value) || 0;
        subtotal += cantidad * precio;
    });
    
    // Calcular IGV solo si el checkbox está marcado
    const incluirIGV = document.getElementById('incluirIGV').checked;
    const igv = incluirIGV ? subtotal * 0.18 : 0;
    const total = subtotal + igv;
    
    // Actualizar campo hidden para enviar en el formulario
    document.getElementById('incluir_igv_hidden').value = incluirIGV ? '1' : '0';
    
    document.getElementById('subtotal').value = subtotal.toFixed(2);
    document.getElementById('igv').value = igv.toFixed(2);
    document.getElementById('total').value = total.toFixed(2);

    // Moneda base según selección; todo el detalle se mantiene en la moneda seleccionada
    const monedaSelect = document.getElementById('id_moneda');
    const selectedMoneda = monedaSelect.options[monedaSelect.selectedIndex]?.text?.toLowerCase() || '';
    // Actualizar indicador de TC y moneda base
    const tcIndicador = document.getElementById('tc-indicador');
    const simboloBase = (selectedMoneda.includes('dólar') || selectedMoneda.includes('usd') || selectedMoneda.includes('dolar')) ? 'USD' : 'PEN';
    tcIndicador.textContent = `(Moneda base: ${simboloBase} • TC manual: S/ ${TIPO_CAMBIO.toFixed(4)})`;
    // Actualizar etiquetas de totales con la moneda base
    const labelBase = document.getElementById('label-moneda-base');
    const labelBaseIgv = document.getElementById('label-moneda-base-igv');
    const labelBaseTotal = document.getElementById('label-moneda-base-total');
    if (labelBase) labelBase.textContent = `(${simboloBase})`;
    if (labelBaseIgv) labelBaseIgv.textContent = `(${simboloBase})`;
    if (labelBaseTotal) labelBaseTotal.textContent = `(${simboloBase})`;
    // Actualizar badge y campo oculto con la moneda seleccionada
    const badgeMoneda = document.getElementById('badge-moneda');
    const hiddenCodigo = document.getElementById('moneda_codigo');
    if (badgeMoneda) badgeMoneda.textContent = simboloBase;
    if (hiddenCodigo) hiddenCodigo.value = simboloBase;
    // Ocultar secciones de conversión; todo se muestra solo en la moneda seleccionada
    const totUSD = document.getElementById('totales-dolares');
    const totPEN = document.getElementById('totales-soles');
    if (totUSD) totUSD.style.display = 'none';
    if (totPEN) totPEN.style.display = 'none';
}

// Recalcular al cambiar valores en la tabla
document.getElementById('productos-table').addEventListener('input', function(e) {
    if (e.target.matches('input[name$="[cantidad]"]') || e.target.matches('input[name$="[precio_unitario]"]')) {
        calcularTotales();
    }
});

// Recalcular al cambiar moneda
document.getElementById('id_moneda').addEventListener('change', function(){
    actualizarDetallesMonedaVisual();
    calcularTotales();
});

function actualizarDetallesMonedaVisual() {
    const monedaSelect = document.getElementById('id_moneda');
    const selectedMoneda = monedaSelect.options[monedaSelect.selectedIndex]?.text?.toLowerCase() || '';
    const simboloISO = (selectedMoneda.includes('dólar') || selectedMoneda.includes('usd') || selectedMoneda.includes('dolar')) ? 'USD' : 'PEN';
    const simboloChar = simboloISO === 'USD' ? '$' : 'S/';
    // Actualizar placeholder/sufijo de precio unitario en todas las filas
    document.querySelectorAll('#productos-table tbody tr input[name$="[precio_unitario]"]').forEach(function(input){
        input.placeholder = `${simboloChar} 0.00`;
        // Opcional: ajustar style para indicar visualmente la moneda
        input.setAttribute('data-moneda', simboloISO);
    });
    // Actualizar badge del encabezado y símbolos visibles en cada fila
    const badgeHeader = document.getElementById('detalleMonedaBadgeHeader');
    if (badgeHeader) badgeHeader.textContent = simboloISO;
    document.querySelectorAll('#productos-table tbody tr [id^="simboloDetalle"]').forEach(function(span){
        span.textContent = simboloChar;
    });
}

// Agregar fila de producto seleccionada desde el buscador
function agregarFilaProducto(idProducto, codigo, descripcion) {
    const tbody = document.querySelector('#productos-table tbody');
    // Remover estado vacío si existe ANTES de calcular el índice
    const emptyRowPre = tbody.querySelector('.empty-state-row');
    if (emptyRowPre) emptyRowPre.remove();
    // Calcular índice real según cantidad de filas de productos existentes
    const index = Array.from(tbody.querySelectorAll('tr')).filter(tr => !tr.classList.contains('empty-state-row')).length;
    const row = document.createElement('tr');
    const monedaSelect = document.getElementById('id_moneda');
    const selectedMoneda = monedaSelect.options[monedaSelect.selectedIndex]?.text?.toLowerCase() || '';
    const simboloISO = (selectedMoneda.includes('dólar') || selectedMoneda.includes('usd') || selectedMoneda.includes('dolar')) ? 'USD' : 'PEN';
    const simboloChar = simboloISO === 'USD' ? '$' : 'S/';

    // Derivar proveedor del producto y validar homogeneidad
    const hiddenProv = document.getElementById('id_proveedor_hidden');
    const prod = productos.find(p => p.id === idProducto);
    const provId = prod?.id_proveedor || null;
    if (!provId) {
        alert('No se pudo determinar el proveedor del producto seleccionado.');
        return;
    }
    // Si el proveedor cabecera está limpio, llenar automáticamente según el producto seleccionado
    if (!hiddenProv.value) {
        try {
            const prov = (typeof proveedores !== 'undefined') ? proveedores.find(pp => String(pp.id) === String(provId)) : null;
            if (prov) {
                setProveedorUI(prov.id, prov.nombre, prov.ruc);
            }
        } catch (e) {}
    }

    row.innerHTML = `
        <td data-label="Producto">
            <div>
                ${codigo ? `<span class=\"item-code-badge\">${codigo}</span>` : ''}<span class=\"item-desc\">${descripcion}</span>
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
        </td>
    `;
    tbody.appendChild(row);
    calcularTotales();
    mostrarEstadoVacio();
}

// (Eliminado) filtrarProductos: ya no se usan listas, solo buscador

// Variables para el buscador
let productos = [];
let selectedIndex = -1;

// No cargar productos al inicio - se cargarán al seleccionar proveedor
document.addEventListener('DOMContentLoaded', function() {
    // Habilitar búsqueda desde el inicio
    const buscadorInput = document.getElementById('buscadorProducto');
    if (buscadorInput) {
        buscadorInput.disabled = false;
        buscadorInput.placeholder = 'Buscar producto por código o descripción...';
    }
});

function buscarProducto(event) {
    const input = document.getElementById('buscadorProducto');
    const suggestionsList = document.getElementById('suggestionsList');
    const busqueda = input.value.toLowerCase().trim();
    
    // Manejar teclas de navegación
    if (event.key === 'ArrowDown') {
        event.preventDefault();
        selectedIndex = Math.min(selectedIndex + 1, suggestionsList.children.length - 1);
        actualizarSeleccion();
        return;
    } else if (event.key === 'ArrowUp') {
        event.preventDefault();
        selectedIndex = Math.max(selectedIndex - 1, 0);
        actualizarSeleccion();
        return;
    } else if (event.key === 'Enter') {
        event.preventDefault();
        if (selectedIndex >= 0) {
            const selectedItem = suggestionsList.children[selectedIndex];
            if (selectedItem) {
                selectedItem.click();
            }
        }
        return;
    } else if (event.key === 'Escape') {
        suggestionsList.classList.remove('show');
        selectedIndex = -1;
        return;
    }
    
    // Resetear selección al escribir
    selectedIndex = -1;
    
    if (busqueda.length === 0) {
        suggestionsList.classList.remove('show');
        return;
    }
    
    // Filtrar productos considerando proveedor seleccionado
    const hiddenProv = document.getElementById('id_proveedor_hidden');
    const provFilter = (hiddenProv && hiddenProv.value) ? String(hiddenProv.value) : '';
    const resultados = productos.filter(producto => {
        const codigo = (producto.codigo || '').toLowerCase();
        const descripcion = (producto.descripcion || '').toLowerCase();
        const proveedor = (producto.proveedor || '').toLowerCase();
        const matchTexto = codigo.includes(busqueda) || descripcion.includes(busqueda) || proveedor.includes(busqueda);
        const matchProv = provFilter ? String(producto.id_proveedor) === provFilter : true;
        return matchTexto && matchProv;
    });
    
    // Mostrar resultados
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
    
    items.forEach((item, index) => {
        if (index === selectedIndex) {
            item.classList.add('selected');
            item.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
        } else {
            item.classList.remove('selected');
        }
    });
}

function seleccionarProducto(id, codigo, descripcion) {
    // Agregar directamente una fila con el producto seleccionado
    agregarFilaProducto(id, codigo, descripcion);
    // Limpiar búsqueda
    document.getElementById('buscadorProducto').value = '';
    document.getElementById('suggestionsList').classList.remove('show');
    selectedIndex = -1;
}

// Mostrar/ocultar estado vacío
function mostrarEstadoVacio() {
    const tbody = document.querySelector('#productos-table tbody');
    const filasReal = Array.from(tbody.children).filter(tr => !tr.classList.contains('empty-state-row'));
    if (filasReal.length === 0 && !tbody.querySelector('.empty-state-row')) {
        const tr = document.createElement('tr');
        tr.className = 'empty-state-row';
        tr.innerHTML = `<td colspan="4"><div class="empty-state"><i class=\"fas fa-box-open\"></i><span class=\"ms-2\">No hay productos agregados. Usa el buscador superior para añadir ítems.</span></div></td>`;
        tbody.appendChild(tr);
    }
}

// Cerrar sugerencias al hacer click fuera
document.addEventListener('click', function(event) {
    const containers = document.querySelectorAll('.search-container');
    const isInside = Array.from(containers).some(c => c.contains(event.target));
    if (!isInside) {
        const listProd = document.getElementById('suggestionsList');
        const listProv = document.getElementById('proveedorSuggestions');
        if (listProd) listProd.classList.remove('show');
        if (listProv) listProv.classList.remove('show');
        selectedIndex = -1;
        if (typeof selectedProveedorIndex !== 'undefined') selectedProveedorIndex = -1;
    }
});

// ==========================
// Buscador de Proveedor
// ==========================
let proveedores = [];
let selectedProveedorIndex = -1;

function buscarProveedor(event) {
    const input = document.getElementById('buscadorProveedor');
    const list = document.getElementById('proveedorSuggestions');
    const q = (input.value || '').toLowerCase().trim();

    // Teclas navegación
    if (event.key === 'ArrowDown') {
        event.preventDefault();
        selectedProveedorIndex = Math.min(selectedProveedorIndex + 1, list.children.length - 1);
        actualizarSeleccionProveedor();
        return;
    } else if (event.key === 'ArrowUp') {
        event.preventDefault();
        selectedProveedorIndex = Math.max(selectedProveedorIndex - 1, 0);
        actualizarSeleccionProveedor();
        return;
    } else if (event.key === 'Enter') {
        event.preventDefault();
        if (selectedProveedorIndex >= 0) {
            const item = list.children[selectedProveedorIndex];
            if (item) item.click();
        }
        return;
    } else if (event.key === 'Escape') {
        list.classList.remove('show');
        selectedProveedorIndex = -1;
        return;
    }

    selectedProveedorIndex = -1;
    if (!q) { list.classList.remove('show'); return; }

    const res = proveedores.filter(p => (
        (p.nombre || '').toLowerCase().includes(q) || (p.ruc || '').toLowerCase().includes(q)
    ));

    if (res.length) {
        list.innerHTML = res.map((p, idx) => `
            <div class="suggestion-item" data-index="${idx}" onclick="seleccionarProveedor(${p.id}, '${(p.nombre || '').replace(/'/g, "\\'")}', '${p.ruc || ''}')">
                <div class="d-flex align-items-center flex-wrap" style="gap:6px;">
                    <span class="suggestion-descripcion"><i class='fas fa-truck me-1'></i>${p.nombre || '—'}</span>
                    ${p.ruc ? `<span class='suggestion-proveedor'>RUC: ${p.ruc}</span>` : ''}
                </div>
                <i class="fas fa-check text-success"></i>
            </div>
        `).join('');
        list.classList.add('show');
    } else {
        list.innerHTML = '<div class="no-results"><i class="fas fa-search"></i> No se encontraron proveedores</div>';
        list.classList.add('show');
    }
}

function actualizarSeleccionProveedor() {
    const list = document.getElementById('proveedorSuggestions');
    const items = list.querySelectorAll('.suggestion-item');
    items.forEach((el, i) => {
        if (i === selectedProveedorIndex) {
            el.classList.add('selected');
            el.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
        } else {
            el.classList.remove('selected');
        }
    });
}

function setProveedorUI(id, nombre, ruc) {
    const hidden = document.getElementById('id_proveedor_hidden');
    hidden.value = id || '';
    const info = document.getElementById('proveedorSeleccionadoInfo');
    const texto = document.getElementById('proveedorSeleccionadoTexto');
    if (id) {
        texto.textContent = `${nombre || ''}${ruc ? ' • RUC: ' + ruc : ''}`;
        info.style.display = 'block';
        const input = document.getElementById('buscadorProveedor');
        input.value = nombre || '';
    } else {
        texto.textContent = '';
        info.style.display = 'none';
        document.getElementById('buscadorProveedor').value = '';
    }
}

function seleccionarProveedor(id, nombre, ruc) {
    // Permitir cambiar el proveedor cabecera en cualquier momento (multi-proveedor por producto)
    setProveedorUI(id, nombre, ruc);
    allowGlobalSearch = false; // al seleccionar proveedor, se vuelve a filtrar por ese proveedor
    document.getElementById('proveedorSuggestions').classList.remove('show');
    selectedProveedorIndex = -1;
}

function limpiarProveedor() {
    // Permitir limpiar SIEMPRE: búsquedas globales de productos
    setProveedorUI('', '', '');
    allowGlobalSearch = true;
}

</script>

@endsection
