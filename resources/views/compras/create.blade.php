<script>
let TIPO_CAMBIO = 3.8; // Valor por defecto

function calcularTotales() {
    let subtotal = 0;
    document.querySelectorAll('#productos-table tbody tr').forEach(function(row) {
        const cantidad = parseFloat(row.querySelector('input[name$="[cantidad]"]').value) || 0;
        const precio = parseFloat(row.querySelector('input[name$="[precio_unitario]"]').value) || 0;
        subtotal += cantidad * precio;
    });
    const igv = subtotal * 0.18;
    const total = subtotal + igv;
    document.getElementById('subtotal').value = subtotal.toFixed(2);
    document.getElementById('igv').value = igv.toFixed(2);
    document.getElementById('total').value = total.toFixed(2);

    // Si la moneda es dólares, mostrar totales en USD
    const monedaSelect = document.getElementById('id_moneda');
    const selectedMoneda = monedaSelect.options[monedaSelect.selectedIndex]?.text?.toLowerCase() || '';
    if (selectedMoneda.includes('dólar') || selectedMoneda.includes('usd')) {
        document.getElementById('totales-dolares').style.display = '';
        document.getElementById('subtotal_usd').value = (subtotal / TIPO_CAMBIO).toFixed(2);
        document.getElementById('igv_usd').value = (igv / TIPO_CAMBIO).toFixed(2);
        document.getElementById('total_usd').value = (total / TIPO_CAMBIO).toFixed(2);
    } else {
        document.getElementById('totales-dolares').style.display = 'none';
    }
}

// Consultar tipo de cambio SUNAT al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    fetch('https://api.apis.net.pe/v1/tipo-cambio-sunat?fecha=' + new Date().toISOString().slice(0,10))
        .then(response => response.json())
        .then(data => {
            if (data && data.cambio) {
                TIPO_CAMBIO = parseFloat(data.cambio);
                document.getElementById('tipo-cambio-info').textContent = 'Tipo de cambio SUNAT: S/ ' + TIPO_CAMBIO;
                document.getElementById('tipo-cambio-manual').style.display = 'none';
                calcularTotales();
            } else {
                mostrarTipoCambioManual();
            }
        })
        .catch(() => {
            mostrarTipoCambioManual();
        });
    calcularTotales();
});

function mostrarTipoCambioManual() {
    document.getElementById('tipo-cambio-info').textContent = 'No se pudo obtener el tipo de cambio SUNAT. Usando valor manual.';
    document.getElementById('tipo-cambio-manual').style.display = '';
    TIPO_CAMBIO = parseFloat(document.getElementById('tipo_cambio_manual').value) || 3.8;
    calcularTotales();
}

document.getElementById('tipo_cambio_manual').addEventListener('input', function() {
    TIPO_CAMBIO = parseFloat(this.value) || 3.8;
    calcularTotales();
});

// Recalcular totales al cambiar cantidad, precio, moneda
document.getElementById('productos-table').addEventListener('input', function(e) {
    if (e.target.matches('input[name$="[cantidad]"]') || e.target.matches('input[name$="[precio_unitario]"]')) {
        calcularTotales();
    }
});
document.getElementById('id_moneda').addEventListener('change', calcularTotales);

// Recalcular al agregar/eliminar productos
function agregarProductoRow() {
    const tbody = document.querySelector('#productos-table tbody');
    const index = tbody.children.length;
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>
            <select name="detalles[${index}][id_producto]" class="form-select" required>
                <option value="">Seleccione...</option>
                @foreach($productos as $producto)
                    <option value="{{ $producto->id_producto }}">{{ $producto->descripcion }}</option>
                @endforeach
            </select>
        </td>
        <td><input type="number" name="detalles[${index}][cantidad]" class="form-control" min="1" value="1" required></td>
        <td><input type="number" step="0.01" name="detalles[${index}][precio_unitario]" class="form-control" value="0" required></td>
        <td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove(); calcularTotales();">Eliminar</button></td>
    `;
    tbody.appendChild(row);
    calcularTotales();
}

// Calcular totales al cargar la página (por si ya hay datos)
document.addEventListener('DOMContentLoaded', calcularTotales);
</script>


@extends('layouts.dashboard')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Registrar Compra</h2>
    <form action="{{ route('compras.store') }}" method="POST" class="shadow rounded p-4 bg-white mx-auto" style="max-width: 700px;">
        @csrf
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label for="id_proveedor" class="form-label">Proveedor</label>
                <select name="id_proveedor" id="id_proveedor" class="form-select" required>
                    <option value="">Seleccione un proveedor</option>
                    @foreach($proveedores as $proveedor)
                        <option value="{{ $proveedor->id_proveedor }}">{{ $proveedor->razon_social }}</option>
                    @endforeach
                </select>
                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const proveedorSelect = document.getElementById('id_proveedor');
                        proveedorSelect.addEventListener('change', function() {
                            const proveedorId = this.value;
                            fetch(`/compras/productos-por-proveedor/${proveedorId}`)
                                .then(response => response.json())
                                .then(data => {
                                    document.querySelectorAll('select[name^="detalles"]').forEach(function(productSelect) {
                                        productSelect.innerHTML = '<option value="">Seleccione...</option>';
                                        data.forEach(function(producto) {
                                            productSelect.innerHTML += `<option value="${producto.id_producto}">${producto.descripcion}</option>`;
                                        });
                                    });
                                });
                        });
                    });
                    </script>
            </div>
            <div class="col-md-4">
                <label for="id_moneda" class="form-label">Moneda</label>
                <select name="id_moneda" id="id_moneda" class="form-select" required>
                    <option value="">Seleccione una moneda</option>
                    @foreach($monedas as $moneda)
                        <option value="{{ $moneda->id_moneda }}">{{ $moneda->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="fecha" class="form-label">Fecha</label>
        <div class="row mb-3" id="totales-dolares" style="display:none;">
            <div class="col-md-4">
                <label class="form-label">Subtotal (USD)</label>
                <input type="text" id="subtotal_usd" class="form-control" readonly>
            </div>
            <div class="col-md-4">
                <label class="form-label">IGV (USD)</label>
                <input type="text" id="igv_usd" class="form-control" readonly>
            </div>
            <div class="col-md-4">
                <label class="form-label">Total (USD)</label>
                <input type="text" id="total_usd" class="form-control" readonly>
            </div>
        </div>
            <div class="mb-3">
                <span id="tipo-cambio-info" class="text-muted small"></span>
                <div id="tipo-cambio-manual" style="display:none;">
                    <label for="tipo_cambio_manual" class="form-label">Tipo de cambio manual (USD a PEN)</label>
                    <input type="number" step="0.0001" min="0" id="tipo_cambio_manual" class="form-control" value="3.8">
                </div>
            </div>
                <input type="date" name="fecha" id="fecha" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>
        </div>

        <h4 class="mt-4">Productos</h4>
        <div class="table-responsive mb-3">
            <table class="table table-bordered align-middle" id="productos-table">
                <thead class="table-light">
<script>
// Tipo de cambio fijo, puedes cambiarlo o hacerlo dinámico
const TIPO_CAMBIO = 3.8;

function calcularTotales() {
    let subtotal = 0;
    document.querySelectorAll('#productos-table tbody tr').forEach(function(row) {
        const cantidad = parseFloat(row.querySelector('input[name$="[cantidad]"]').value) || 0;
        const precio = parseFloat(row.querySelector('input[name$="[precio_unitario]"]').value) || 0;
        subtotal += cantidad * precio;
    });
    const igv = subtotal * 0.18;
    const total = subtotal + igv;
    document.getElementById('subtotal').value = subtotal.toFixed(2);
    document.getElementById('igv').value = igv.toFixed(2);
    document.getElementById('total').value = total.toFixed(2);

    // Si la moneda es dólares, mostrar totales en USD
    const monedaSelect = document.getElementById('id_moneda');
    const selectedMoneda = monedaSelect.options[monedaSelect.selectedIndex]?.text?.toLowerCase() || '';
    if (selectedMoneda.includes('dólar') || selectedMoneda.includes('usd')) {
        document.getElementById('totales-dolares').style.display = '';
        document.getElementById('subtotal_usd').value = (subtotal / TIPO_CAMBIO).toFixed(2);
        document.getElementById('igv_usd').value = (igv / TIPO_CAMBIO).toFixed(2);
        document.getElementById('total_usd').value = (total / TIPO_CAMBIO).toFixed(2);
    } else {
        document.getElementById('totales-dolares').style.display = 'none';
    }
}

// Recalcular totales al cambiar cantidad, precio, moneda
document.getElementById('productos-table').addEventListener('input', function(e) {
    if (e.target.matches('input[name$="[cantidad]"]') || e.target.matches('input[name$="[precio_unitario]"]')) {
        calcularTotales();
    }
});
document.getElementById('id_moneda').addEventListener('change', calcularTotales);

// Recalcular al agregar/eliminar productos
function agregarProductoRow() {
    const tbody = document.querySelector('#productos-table tbody');
    const index = tbody.children.length;
    const row = document.createElement('tr');
    row.innerHTML = `
        <script>
        let TIPO_CAMBIO = 3.8; // Valor por defecto

        // Consultar tipo de cambio SUNAT al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            fetch('https://api.apis.net.pe/v1/tipo-cambio-sunat?fecha=' + new Date().toISOString().slice(0,10))
                .then(response => response.json())
                .then(data => {
                    if (data && data.cambio) {
                        TIPO_CAMBIO = parseFloat(data.cambio);
                        document.getElementById('tipo-cambio-info').textContent = 'Tipo de cambio SUNAT: S/ ' + TIPO_CAMBIO;
                        document.getElementById('tipo-cambio-manual').style.display = 'none';
                        calcularTotales();
                    } else {
                        mostrarTipoCambioManual();
                    }
                })
                .catch(() => {
                    mostrarTipoCambioManual();
                });
        });

        function mostrarTipoCambioManual() {
            document.getElementById('tipo-cambio-info').textContent = 'No se pudo obtener el tipo de cambio SUNAT. Usando valor manual.';
            document.getElementById('tipo-cambio-manual').style.display = '';
            TIPO_CAMBIO = parseFloat(document.getElementById('tipo_cambio_manual').value) || 3.8;
            calcularTotales();
        }

        document.getElementById('tipo_cambio_manual').addEventListener('input', function() {
            TIPO_CAMBIO = parseFloat(this.value) || 3.8;
            calcularTotales();
        });
</script>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <select name="detalles[0][id_producto]" class="form-select" required>
                                    <option value="">Seleccione...</option>
                                    @foreach($productos as $producto)
                                        <option value="{{ $producto->id_producto }}">{{ $producto->descripcion }}</option>
                                    @endforeach
                            </select>
                        </td>
                        <td><input type="number" name="detalles[0][cantidad]" class="form-control" min="1" required></td>
                        <td><input type="number" step="0.01" name="detalles[0][precio_unitario]" class="form-control" required></td>
                        <td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">Eliminar</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    <button type="button" class="btn btn-secondary w-100 mb-3" onclick="agregarProductoRow()">Agregar Producto</button>
    <button type="button" class="btn btn-info w-100 mb-3" onclick="calcularTotales()">Calcular</button>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label for="subtotal" class="form-label">Subtotal</label>
                <input type="text" name="subtotal" id="subtotal" class="form-control" readonly>
            </div>
            <div class="col-md-4">
                <label for="igv" class="form-label">IGV</label>
                <input type="text" name="igv" id="igv" class="form-control" readonly>
            </div>
            <div class="col-md-4">
                <label for="total" class="form-label">Total</label>
                <input type="text" name="total" id="total" class="form-control" readonly>
            </div>
        </div>
        <div class="d-flex gap-2 justify-content-end">
            <button type="submit" class="btn btn-success px-4">Registrar</button>
            <a href="{{ route('compras.index') }}" class="btn btn-secondary px-4">Cancelar</a>
        </div>
    </form>
</div>
@endsection

<style>
    /* Encabezado */
    h1 {
        font-size: 2.8rem;
        font-weight: bold;
        color: #ffffff;
        text-align: center;
        margin-bottom: 30px;
        background: linear-gradient(90deg, #007bff, #6610f2);
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* Contenedor */
    .form-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 2rem;
        background: #ffffff;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    /* Campos del formulario */
    .form-label {
        font-weight: bold;
        color: #495057;
        margin-bottom: 8px;
    }

    .form-control {
        border-radius: 10px;
        border: 1px solid #ced4da;
        padding: 10px;
        box-shadow: none;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

    .form-select {
        border-radius: 10px;
        border: 1px solid #ced4da;
        padding: 10px;
        transition: all 0.3s ease;
    }

    .form-select:focus {
        border-color: #80bdff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

    /* Botones */
    .btn {
        border-radius: 10px;
        font-weight: bold;
        padding: 10px 20px;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: linear-gradient(135deg, #007bff, #0056b3);
        border: none;
        color: #fff;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #0056b3, #003f7f);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .btn-secondary {
        background: linear-gradient(135deg, #6c757d, #343a40);
        border: none;
        color: #fff;
    }

    .btn-secondary:hover {
        background: linear-gradient(135deg, #343a40, #23272b);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    /* Tabla */
    .table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 10px;
    }

    .table thead th {
        background: #007bff;
        color: #fff;
        font-weight: bold;
        text-transform: uppercase;
        padding: 12px;
        border: none;
        border-radius: 10px;
    }

    .table tbody tr {
        background: #f8f9fa;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .table tbody tr td {
        padding: 12px;
        border-top: none;
    }

    .table tbody tr td:first-child {
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
    }

    .table tbody tr td:last-child {
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
    }
</style>
