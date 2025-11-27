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

    .input-with-icon {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #dc2626;
        font-size: 0.875rem;
    }

    .input-with-icon .form-control,
    .input-with-icon .form-select {
        padding-left: 2.5rem;
    }

    .section-divider {
        margin: 2rem 0;
        border-top: 2px dashed #fecaca;
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
                        <div class="input-with-icon">
                            <i class="fas fa-building input-icon"></i>
                            <select name="id_proveedor" id="id_proveedor" class="form-select" required>
                                @foreach($proveedores as $proveedor)
                                    <option value="{{ $proveedor->id_proveedor }}" {{ $compra->id_proveedor == $proveedor->id_proveedor ? 'selected' : '' }}>
                                        {{ $proveedor->razon_social }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="id_moneda" class="form-label">
                            <i class="fas fa-coins"></i>
                            Moneda
                        </label>
                        <div class="input-with-icon">
                            <i class="fas fa-coins input-icon"></i>
                            <select name="id_moneda" id="id_moneda" class="form-select" required>
                                @foreach($monedas as $moneda)
                                    <option value="{{ $moneda->id_moneda }}" 
                                        {{ $compra->id_moneda == $moneda->id_moneda ? 'selected' : '' }}
                                        style="{{ ($moneda->codigo_iso == 'PEN' || $moneda->id_moneda == 1) ? 'background-color: #e8f5e9; font-weight: bold;' : '' }}">
                                        {{ $moneda->simbolo ?? '' }} {{ $moneda->descripcion ?? $moneda->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="fecha" class="form-label">
                            <i class="fas fa-calendar-alt"></i>
                            Fecha y Hora
                        </label>
                        <div class="input-with-icon">
                            <i class="fas fa-calendar-alt input-icon"></i>
                            <input type="datetime-local" name="fecha" id="fecha" class="form-control" 
                                   value="{{ date('Y-m-d\TH:i', strtotime($compra->fecha)) }}" required>
                        </div>
                    </div>
                </div>

                <div class="form-group form-check">
                    <input type="hidden" name="incluir_igv" value="0">
                    <input type="checkbox" name="incluir_igv" id="incluir_igv" class="form-check-input" value="1" {{ $compra->incluir_igv ? 'checked' : '' }}>
                    <label for="incluir_igv" class="form-check-label">Incluir IGV</label>
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
                <div class="table-responsive">
                    <table class="table table-productos mb-3">
                        <thead>
                            <tr>
                                <th style="width: 30%;">Producto</th>
                                <th style="width: 12%;">Cantidad</th>
                                <th style="width: 14%;">Precio Unit.</th>
                                <th style="width: 14%;">Subtotal</th>
                                <th style="width: 12%;">IGV (18%)</th>
                                <th style="width: 14%;">Total</th>
                                <th style="width: 4%;"></th>
                            </tr>
                        </thead>
                        <tbody id="productos-table">
                            @foreach($compra->detalles as $i => $detalle)
                            <tr>
                                <td>
                                    <select name="detalles[{{ $i }}][id_producto]" class="form-select form-select-sm" required>
                                        <option value="">Seleccione...</option>
                                        @foreach($productos as $producto)
                                            <option value="{{ $producto->id_producto }}" {{ $detalle->id_producto == $producto->id_producto ? 'selected' : '' }}>
                                                {{ $producto->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="detalles[{{ $i }}][cantidad]" class="form-control form-control-sm" 
                                           min="1" value="{{ $detalle->cantidad }}" required oninput="calcularFila(this)">
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="detalles[{{ $i }}][precio_unitario]" 
                                           class="form-control form-control-sm" value="{{ $detalle->precio_unitario }}" 
                                           required oninput="calcularFila(this)">
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="detalles[{{ $i }}][subtotal]" 
                                           class="form-control form-control-sm" 
                                           value="{{ $detalle->subtotal ?? ($detalle->cantidad * $detalle->precio_unitario) }}" readonly>
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="detalles[{{ $i }}][igv]" 
                                           class="form-control form-control-sm" 
                                           value="{{ $detalle->igv ?? (($detalle->cantidad * $detalle->precio_unitario) * 0.18) }}" readonly>
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="detalles[{{ $i }}][total]" 
                                           class="form-control form-control-sm" 
                                           value="{{ $detalle->total ?? (($detalle->cantidad * $detalle->precio_unitario) * 1.18) }}" readonly>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn-remove-row" onclick="this.closest('tr').remove();calcularTotales();" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="button" class="btn-add-product" onclick="agregarFilaProducto()">
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
                        <span class="total-value" style="font-size: 1rem;">
                            <input type="number" step="0.01" name="subtotal" id="subtotal" 
                                   class="form-control form-control-sm text-end" 
                                   value="{{ $compra->subtotal }}" required readonly 
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
                        <span class="total-value" style="font-size: 1rem;">
                            <input type="number" step="0.01" name="igv" id="igv" 
                                   class="form-control form-control-sm text-end" 
                                   value="{{ $compra->igv }}" required readonly 
                                   style="display: inline-block; width: 150px; border: none; background: transparent; font-weight: 700; color: #dc2626;">
                        </span>
                    </div>
                    
                    <div class="total-item">
                        <span class="total-label" style="font-size: 1.125rem;">TOTAL:</span>
                        <span class="total-value">
                            <input type="number" step="0.01" name="total" id="total" 
                                   class="form-control text-end" 
                                   value="{{ $compra->total }}" required readonly 
                                   style="display: inline-block; width: 180px; border: none; background: transparent; font-weight: 700; color: #dc2626; font-size: 1.5rem;">
                        </span>
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
                Actualizar Compra
            </button>
        </div>
    </form>
</div>

<script>
let fila = {{ count($compra->detalles) }};

function agregarFilaProducto() {
    let table = document.getElementById('productos-table');
    let row = document.createElement('tr');
    row.innerHTML = `
        <td>
            <select name="detalles[${fila}][id_producto]" class="form-select form-select-sm" required>
                <option value="">Seleccione...</option>
                @foreach($productos as $producto)
                <option value="{{ $producto->id_producto }}">{{ $producto->descripcion }}</option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="number" name="detalles[${fila}][cantidad]" class="form-control form-control-sm" 
                   min="1" required oninput="calcularFila(this)">
        </td>
        <td>
            <input type="number" step="0.01" name="detalles[${fila}][precio_unitario]" 
                   class="form-control form-control-sm" required oninput="calcularFila(this)">
        </td>
        <td>
            <input type="number" step="0.01" name="detalles[${fila}][subtotal]" 
                   class="form-control form-control-sm" readonly>
        </td>
        <td>
            <input type="number" step="0.01" name="detalles[${fila}][igv]" 
                   class="form-control form-control-sm" readonly>
        </td>
        <td>
            <input type="number" step="0.01" name="detalles[${fila}][total]" 
                   class="form-control form-control-sm" readonly>
        </td>
        <td class="text-center">
            <button type="button" class="btn-remove-row" onclick="this.closest('tr').remove();calcularTotales();" title="Eliminar">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    table.appendChild(row);
    fila++;
}

function calcularFila(input) {
    let row = input.closest('tr');
    let cantidad = parseFloat(row.querySelector('input[name*="[cantidad]"]').value) || 0;
    let precio = parseFloat(row.querySelector('input[name*="[precio_unitario]"]').value) || 0;
    let sub = cantidad * precio;

    // Check IGV toggle state
    const incluirIGV = document.getElementById('incluirIGV').checked;
    let igvProd = incluirIGV ? sub * 0.18 : 0;
    let totProd = sub + igvProd;

    row.querySelector('input[name*="[subtotal]"]').value = sub.toFixed(2);
    row.querySelector('input[name*="[igv]"]').value = igvProd.toFixed(2);
    row.querySelector('input[name*="[total]"]').value = totProd.toFixed(2);
    calcularTotales();
}

function calcularTotales() {
    let subtotal = 0;
    let igv = 0;
    let total = 0;
    let rows = document.querySelectorAll('#productos-table tr');

    // Check IGV toggle state
    const incluirIGV = document.getElementById('incluirIGV').checked;

    rows.forEach(row => {
        let cantidad = parseFloat(row.querySelector('input[name*="[cantidad]"]').value) || 0;
        let precio = parseFloat(row.querySelector('input[name*="[precio_unitario]"]').value) || 0;
        let sub = cantidad * precio;
        let igvProd = incluirIGV ? sub * 0.18 : 0;
        let totProd = sub + igvProd;

        subtotal += sub;
        igv += incluirIGV ? igvProd : 0;
        total += incluirIGV ? totProd : sub;

        row.querySelector('input[name*="[subtotal]"]').value = sub.toFixed(2);
        row.querySelector('input[name*="[igv]"]').value = incluirIGV ? igvProd.toFixed(2) : "0.00";
        row.querySelector('input[name*="[total]"]').value = incluirIGV ? totProd.toFixed(2) : sub.toFixed(2);
    });

    document.getElementById('subtotal').value = subtotal.toFixed(2);
    document.getElementById('igv').value = incluirIGV ? igv.toFixed(2) : "0.00";
    document.getElementById('total').value = total.toFixed(2);

    // Update hidden field for IGV inclusion
    document.getElementById('incluir_igv_hidden').value = incluirIGV ? '1' : '0';
}

// Inicializar tooltips
document.addEventListener('DOMContentLoaded', function() {
    calcularTotales();
});
</script>

@endsection
