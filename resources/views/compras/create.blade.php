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
        z-index: 1000;
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

    .no-results {
        padding: 1rem;
        text-align: center;
        color: #6b7280;
        font-style: italic;
    }

    @media (max-width: 768px) {
        .actions-bar {
            flex-direction: column;
            gap: 1rem;
        }
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
                    <div class="col-md-4">
                        <label for="id_proveedor" class="form-label">
                            <i class="fas fa-building"></i>
                            Proveedor
                        </label>
                        <select name="id_proveedor" id="id_proveedor" class="form-select" required>
                            <option value="">Seleccione un proveedor</option>
                            @foreach($proveedores as $proveedor)
                                <option value="{{ $proveedor->id_proveedor }}">{{ $proveedor->razon_social }}</option>
                            @endforeach
                        </select>
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
                                    {{ ($moneda->codigo_iso == 'PEN' || $moneda->id_moneda == 1) ? 'selected' : '' }}
                                    style="{{ ($moneda->codigo_iso == 'PEN' || $moneda->id_moneda == 1) ? 'background-color: #e8f5e9; font-weight: bold;' : '' }}">
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

                <!-- Información de Tipo de Cambio -->
                <div class="tipo-cambio-info mt-3">
                    <i class="fas fa-exchange-alt"></i>
                    <span id="tipo-cambio-info">Consultando tipo de cambio...</span>
                    <button type="button" class="btn btn-sm btn-outline-info ms-2" onclick="actualizarTipoCambioCompras()" id="btnActualizarTC">
                        <i class="fas fa-sync-alt"></i> Actualizar
                    </button>
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
                                <th style="width: 30%;">Precio Unitario</th>
                                <th style="width: 10%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="detalles[0][id_producto]" class="form-select form-select-sm producto-select" required>
                                        <option value="">Primero seleccione un proveedor</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="detalles[0][cantidad]" class="form-control form-control-sm" min="1" value="1" required>
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="detalles[0][precio_unitario]" class="form-control form-control-sm" value="0" required>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn-remove-row" onclick="this.closest('tr').remove(); calcularTotales();" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="button" class="btn-add-product" onclick="agregarProductoRow()">
                        <i class="fas fa-plus"></i>
                        Agregar Producto
                    </button>
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
let TIPO_CAMBIO = null; // Se obtendrá desde SUNAT

// Función para obtener tipo de cambio desde la API interna primero, luego externa
async function obtenerTipoCambio() {
    console.log('Iniciando consulta de tipo de cambio...');
    
    // Intentar primero con la API interna
    try {
        const response = await fetch('/ventas/tipo-cambio');
        const contentType = response.headers.get('content-type');
        
        // Verificar si la respuesta es JSON
        if (contentType && contentType.includes('application/json')) {
            const data = await response.json();
            console.log('Respuesta de API interna:', data);
            
            if (data && data.success && data.tipo_cambio) {
                TIPO_CAMBIO = parseFloat(data.tipo_cambio);
                const fuente = data.fuente || 'API Externa';
                const cacheInfo = data.cache_hit ? ' (en caché)' : ' (actualizado)';
                document.getElementById('tipo-cambio-info').innerHTML = 
                    `<i class="fas fa-check-circle text-success"></i> Tipo de cambio ${fuente}: S/ ${TIPO_CAMBIO.toFixed(4)}${cacheInfo}`;
                calcularTotales();
                console.log(`✓ Tipo de cambio obtenido: ${TIPO_CAMBIO} de ${fuente}`);
                return;
            }
        }
        
        console.warn('API interna no disponible o respuesta no válida, intentando API externa...');
    } catch (error) {
        console.warn('Error con API interna:', error);
    }
    
    // Si falla la API interna, intentar directamente con SUNAT
    try {
        const fecha = new Date().toISOString().split('T')[0];
        const response = await fetch(`https://api.apis.net.pe/v1/tipo-cambio-sunat?fecha=${fecha}`);
        const data = await response.json();
        
        console.log('Respuesta de API SUNAT:', data);
        
        if (data && data.compra) {
            // Usar precio de COMPRA (cuántos soles por dólar)
            TIPO_CAMBIO = parseFloat(data.compra);
            document.getElementById('tipo-cambio-info').innerHTML = 
                `<i class="fas fa-check-circle text-success"></i> Tipo de cambio SUNAT (compra): S/ ${TIPO_CAMBIO.toFixed(4)} (directo)`;
            calcularTotales();
            console.log(`✓ Tipo de cambio COMPRA obtenido de SUNAT: ${TIPO_CAMBIO}`);
            return;
        } else if (data && data.venta) {
            // Fallback a venta si compra no está disponible
            TIPO_CAMBIO = parseFloat(data.venta);
            document.getElementById('tipo-cambio-info').innerHTML = 
                `<i class="fas fa-exclamation-triangle text-warning"></i> Tipo de cambio SUNAT (venta): S/ ${TIPO_CAMBIO.toFixed(4)} (directo)`;
            calcularTotales();
            console.log(`⚠ Tipo de cambio VENTA obtenido de SUNAT: ${TIPO_CAMBIO}`);
            return;
        }
    } catch (error) {
        console.error('Error con API SUNAT:', error);
    }
    
    // Si todo falla, usar 3.38 como fallback
    if (!TIPO_CAMBIO || TIPO_CAMBIO <= 0) {
        TIPO_CAMBIO = 3.38;
        console.warn('⚠ Usando tipo de cambio de fallback:', TIPO_CAMBIO);
    }
    
    document.getElementById('tipo-cambio-info').innerHTML = 
        `<i class="fas fa-exclamation-triangle text-warning"></i> No se pudo obtener el tipo de cambio. Usando S/ ${TIPO_CAMBIO.toFixed(4)} (fallback)`;
    calcularTotales();
}

// Llamar al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    obtenerTipoCambio();

    // Configurar el filtro de productos por proveedor
    const proveedorSelect = document.getElementById('id_proveedor');
    proveedorSelect.addEventListener('change', function() {
        const proveedorId = this.value;
        if (proveedorId) {
            fetch(`/compras/productos-por-proveedor/${proveedorId}`)
                .then(response => response.json())
                .then(data => {
                    // Actualizar array de productos para el buscador
                    productos = data.map(p => ({
                        id: p.id_producto,
                        codigo: p.codigo || '',
                        descripcion: p.descripcion
                    }));
                    
                    // Actualizar todos los selects
                    document.querySelectorAll('.producto-select').forEach(function(productSelect) {
                        const currentValue = productSelect.value;
                        productSelect.innerHTML = '<option value="">Seleccione...</option>';
                        data.forEach(function(producto) {
                            const selected = currentValue == producto.id_producto ? 'selected' : '';
                            const codigoText = producto.codigo ? producto.codigo + ' - ' : '';
                            productSelect.innerHTML += `<option value="${producto.id_producto}" data-codigo="${producto.codigo || ''}" data-descripcion="${producto.descripcion}" ${selected}>${codigoText}${producto.descripcion}</option>`;
                        });
                    });
                    
                    // Limpiar búsqueda
                    document.getElementById('buscadorProducto').value = '';
                    document.getElementById('suggestionsList').classList.remove('show');
                })
                .catch(() => {
                    console.error('Error al cargar productos del proveedor');
                });
        } else {
            // Si no hay proveedor seleccionado, limpiar productos
            productos = [];
            document.querySelectorAll('.producto-select').forEach(function(productSelect) {
                productSelect.innerHTML = '<option value="">Primero seleccione un proveedor</option>';
            });
        }
    });

    calcularTotales();
});

// Función para actualizar el tipo de cambio manualmente
async function actualizarTipoCambioCompras() {
    const btn = document.getElementById('btnActualizarTC');
    const originalHTML = btn.innerHTML;
    
    try {
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Actualizando...';
        btn.disabled = true;
        
        await obtenerTipoCambio();
        
    } catch (error) {
        console.error('Error al actualizar tipo de cambio:', error);
        alert('❌ Error al actualizar el tipo de cambio.');
    } finally {
        btn.innerHTML = originalHTML;
        btn.disabled = false;
    }
}

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

    // Si la moneda es dólares, mostrar conversión a USD
    const monedaSelect = document.getElementById('id_moneda');
    const selectedMoneda = monedaSelect.options[monedaSelect.selectedIndex]?.text?.toLowerCase() || '';
    if (selectedMoneda.includes('dólar') || selectedMoneda.includes('usd') || selectedMoneda.includes('dolar')) {
        document.getElementById('totales-dolares').style.display = 'block';
        document.getElementById('subtotal_usd').value = (subtotal / TIPO_CAMBIO).toFixed(2);
        document.getElementById('igv_usd').value = (igv / TIPO_CAMBIO).toFixed(2);
        document.getElementById('total_usd').value = (total / TIPO_CAMBIO).toFixed(2);
    } else {
        document.getElementById('totales-dolares').style.display = 'none';
    }
}

// Recalcular al cambiar valores en la tabla
document.getElementById('productos-table').addEventListener('input', function(e) {
    if (e.target.matches('input[name$="[cantidad]"]') || e.target.matches('input[name$="[precio_unitario]"]')) {
        calcularTotales();
    }
});

// Recalcular al cambiar moneda
document.getElementById('id_moneda').addEventListener('change', calcularTotales);

function agregarProductoRow() {
    const tbody = document.querySelector('#productos-table tbody');
    const index = tbody.children.length;
    const row = document.createElement('tr');
    
    // Obtener opciones del primer select para mantener la lista de productos
    const primerSelect = document.querySelector('.producto-select');
    let optionsHTML = '';
    
    if (primerSelect) {
        const options = primerSelect.querySelectorAll('option');
        options.forEach(option => {
            optionsHTML += option.outerHTML;
        });
    } else {
        optionsHTML = '<option value="">Primero seleccione un proveedor</option>';
    }
    
    row.innerHTML = `
        <td>
            <select name="detalles[${index}][id_producto]" class="form-select form-select-sm producto-select" required>
                ${optionsHTML}
            </select>
        </td>
        <td>
            <input type="number" name="detalles[${index}][cantidad]" class="form-control form-control-sm" min="1" value="1" required>
        </td>
        <td>
            <input type="number" step="0.01" name="detalles[${index}][precio_unitario]" class="form-control form-control-sm" value="0" required>
        </td>
        <td class="text-center">
            <button type="button" class="btn-remove-row" onclick="this.closest('tr').remove(); calcularTotales();" title="Eliminar">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(row);
    calcularTotales();
}

function filtrarProductos() {
    const busqueda = document.getElementById('buscadorProducto').value.toLowerCase();
    const selects = document.querySelectorAll('.producto-select');
    
    selects.forEach(select => {
        const options = select.querySelectorAll('option');
        let hayCoincidencias = false;
        
        options.forEach(option => {
            if (option.value === '') {
                option.style.display = '';
                return;
            }
            
            const codigo = option.dataset.codigo?.toLowerCase() || '';
            const descripcion = option.dataset.descripcion?.toLowerCase() || '';
            
            if (codigo.includes(busqueda) || descripcion.includes(busqueda)) {
                option.style.display = '';
                hayCoincidencias = true;
            } else {
                option.style.display = 'none';
            }
        });
        
        // Si hay búsqueda y coincidencias, abrir el select
        if (busqueda && hayCoincidencias && !select.value) {
            select.focus();
        }
    });
}

// Variables para el buscador
let productos = [];
let selectedIndex = -1;

// No cargar productos al inicio - se cargarán al seleccionar proveedor
document.addEventListener('DOMContentLoaded', function() {
    // Deshabilitar búsqueda hasta que se seleccione proveedor
    const buscadorInput = document.getElementById('buscadorProducto');
    buscadorInput.disabled = true;
    buscadorInput.placeholder = 'Primero seleccione un proveedor...';
    
    // Habilitar búsqueda cuando se seleccione proveedor
    const proveedorSelect = document.getElementById('id_proveedor');
    proveedorSelect.addEventListener('change', function() {
        if (this.value) {
            buscadorInput.disabled = false;
            buscadorInput.placeholder = 'Buscar producto por código o descripción...';
        } else {
            buscadorInput.disabled = true;
            buscadorInput.placeholder = 'Primero seleccione un proveedor...';
            buscadorInput.value = '';
        }
    });
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
    
    // Filtrar productos
    const resultados = productos.filter(producto => {
        const codigo = producto.codigo.toLowerCase();
        const descripcion = producto.descripcion.toLowerCase();
        return codigo.includes(busqueda) || descripcion.includes(busqueda);
    });
    
    // Mostrar resultados
    if (resultados.length > 0) {
        suggestionsList.innerHTML = resultados.map((producto, index) => `
            <div class="suggestion-item" data-index="${index}" onclick="seleccionarProducto(${producto.id}, '${producto.codigo}', '${producto.descripcion.replace(/'/g, "\\'")}')">
                <div class="d-flex align-items-center">
                    ${producto.codigo ? `<span class="suggestion-codigo">${producto.codigo}</span>` : ''}
                    <span class="suggestion-descripcion">${producto.descripcion}</span>
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
    // Buscar el primer select vacío o agregar una nueva fila
    const selects = document.querySelectorAll('.producto-select');
    let selectVacio = null;
    
    for (let select of selects) {
        if (!select.value) {
            selectVacio = select;
            break;
        }
    }
    
    // Si no hay select vacío, agregar una nueva fila
    if (!selectVacio) {
        agregarProductoRow();
        // Obtener el nuevo select
        const tbody = document.querySelector('#productos-table tbody');
        const ultimaFila = tbody.lastElementChild;
        selectVacio = ultimaFila.querySelector('.producto-select');
    }
    
    // Seleccionar el producto
    if (selectVacio) {
        selectVacio.value = id;
        // Enfocar el campo de cantidad
        const filaActual = selectVacio.closest('tr');
        const inputCantidad = filaActual.querySelector('input[name*="[cantidad]"]');
        if (inputCantidad) {
            inputCantidad.focus();
            inputCantidad.select();
        }
    }
    
    // Limpiar búsqueda
    document.getElementById('buscadorProducto').value = '';
    document.getElementById('suggestionsList').classList.remove('show');
    selectedIndex = -1;
}

// Cerrar sugerencias al hacer click fuera
document.addEventListener('click', function(event) {
    const searchContainer = document.querySelector('.search-container');
    if (searchContainer && !searchContainer.contains(event.target)) {
        document.getElementById('suggestionsList').classList.remove('show');
        selectedIndex = -1;
    }
});

</script>

@endsection
