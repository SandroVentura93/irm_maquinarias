@extends('layouts.dashboard')

@section('title', 'Nueva Venta')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Nueva Venta</h1>
    </div>

    <form id="ventaForm" method="POST" action="{{ route('ventas.store') }}">
        @csrf

        <!-- Información General -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Información General</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <label for="fecha">Fecha</label>
                        <input type="date" class="form-control" id="fecha" name="fecha" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label for="hora">Hora</label>
                        <input type="time" class="form-control" id="hora" name="hora" value="{{ date('H:i') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label for="tipo_comprobante_id">Tipo Comprobante</label>
                        <select class="form-control" id="tipo_comprobante_id" name="tipo_comprobante_id" required>
                            <option value="">Seleccionar</option>
                            @foreach($tiposComprobante as $tipo)
                                <option value="{{ $tipo->id }}">{{ $tipo->descripcion }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="moneda_id">Moneda</label>
                        <select class="form-control" id="moneda_id" name="moneda_id" required>
                            <option value="">Seleccionar</option>
                            @foreach($monedas as $moneda)
                                <option value="{{ $moneda->id }}" {{ $moneda->codigo == 'PEN' ? 'selected' : '' }}>
                                    {{ $moneda->descripcion }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <label for="serie">Serie</label>
                        <input type="text" class="form-control" id="serie" name="serie" value="B001" required>
                    </div>
                    <div class="col-md-3">
                        <label for="correlativo">Correlativo</label>
                        <input type="text" class="form-control" id="correlativo" name="correlativo" value="{{ str_pad(1, 8, '0', STR_PAD_LEFT) }}" required readonly>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cliente -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Cliente</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <label for="cliente_id">Cliente</label>
                        <select class="form-control" id="cliente_id" name="cliente_id" required>
                            <option value="">Seleccionar Cliente</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->nombre }} - {{ $cliente->documento }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="observaciones">Observaciones</label>
                        <input type="text" class="form-control" id="observaciones" name="observaciones" placeholder="Observaciones adicionales">
                    </div>
                </div>
            </div>
        </div>

        <!-- Productos -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Agregar Productos</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <label for="producto_id">Producto</label>
                        <select class="form-control" id="producto_id">
                            <option value="">Seleccionar Producto</option>
                            @foreach($productos as $producto)
                                <option value="{{ $producto->id }}" data-precio="{{ $producto->precio_venta }}">
                                    {{ $producto->nombre }} - S/. {{ number_format($producto->precio_venta, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="cantidad">Cantidad</label>
                        <input type="number" class="form-control" id="cantidad" min="1" step="1">
                    </div>
                    <div class="col-md-2">
                        <label for="precio_unitario">Precio Unit.</label>
                        <input type="number" class="form-control" id="precio_unitario" step="0.01" readonly>
                    </div>
                    <div class="col-md-2">
                        <label for="descuento">Descuento %</label>
                        <input type="number" class="form-control" id="descuento" min="0" max="100" step="0.01" value="0">
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-success btn-block" id="agregarProducto">
                            <i class="fas fa-plus"></i> Agregar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detalle de Productos -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Detalle de la Venta</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="tablaProductos">
                        <thead class="thead-light">
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unit.</th>
                                <th>Descuento</th>
                                <th>Subtotal</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Los productos se agregarán aquí dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Totales -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row justify-content-end">
                    <div class="col-md-4">
                        <table class="table">
                            <tr>
                                <td><strong>Subtotal:</strong></td>
                                <td id="subtotalDisplay">S/. 0.00</td>
                            </tr>
                            <tr>
                                <td><strong>IGV (18%):</strong></td>
                                <td id="igvDisplay">S/. 0.00</td>
                            </tr>
                            <tr class="table-active">
                                <td><strong>Total:</strong></td>
                                <td><strong id="totalDisplay">S/. 0.00</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Campos ocultos para totales -->
        <input type="hidden" id="subtotal" name="subtotal" value="0">
        <input type="hidden" id="igv" name="igv" value="0">
        <input type="hidden" id="total" name="total" value="0">

        <!-- Campos ocultos para productos -->
        <div id="productosHidden"></div>

        <!-- Botones -->
        <div class="text-center mb-4">
            <a href="{{ route('ventas.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left"></i> Cancelar
            </a>
            <button type="submit" class="btn btn-primary" id="guardarVenta">
                <i class="fas fa-save"></i> Guardar Venta
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
let productos = [];

document.addEventListener('DOMContentLoaded', function() {
    // Event listener para cambio de producto
    document.getElementById('producto_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const precio = selectedOption.getAttribute('data-precio') || 0;
        document.getElementById('precio_unitario').value = precio;
    });

    // Event listener para agregar producto
    document.getElementById('agregarProducto').addEventListener('click', agregarProducto);
    
    // Event listener para el formulario
    document.getElementById('ventaForm').addEventListener('submit', function(e) {
        if (productos.length === 0) {
            e.preventDefault();
            alert('Debe agregar al menos un producto a la venta');
            return false;
        }
        
        actualizarCamposOcultos();
        return true;
    });
});

function agregarProducto() {
    const productoSelect = document.getElementById('producto_id');
    const cantidadInput = document.getElementById('cantidad');
    const precioInput = document.getElementById('precio_unitario');
    const descuentoInput = document.getElementById('descuento');

    const productoId = productoSelect.value;
    const productoNombre = productoSelect.options[productoSelect.selectedIndex].text;
    const cantidad = parseFloat(cantidadInput.value) || 0;
    const precio = parseFloat(precioInput.value) || 0;
    const descuento = parseFloat(descuentoInput.value) || 0;

    if (!productoId || cantidad <= 0 || precio <= 0) {
        alert('Complete todos los campos correctamente');
        return;
    }

    // Verificar si el producto ya existe
    const existingIndex = productos.findIndex(p => p.producto_id == productoId);
    if (existingIndex >= 0) {
        productos[existingIndex].cantidad += cantidad;
        productos[existingIndex].subtotal = productos[existingIndex].cantidad * productos[existingIndex].precio_unitario * (1 - productos[existingIndex].descuento / 100);
    } else {
        const precioConDescuento = precio * (1 - descuento / 100);
        const subtotal = cantidad * precioConDescuento;

        const nuevoProducto = {
            producto_id: productoId,
            nombre: productoNombre,
            cantidad: cantidad,
            precio_unitario: precio,
            descuento: descuento,
            subtotal: subtotal
        };

        productos.push(nuevoProducto);
    }

    actualizarTablaProductos();
    limpiarFormularioProducto();
    actualizarTotales();
}

function actualizarTablaProductos() {
    const tbody = document.querySelector('#tablaProductos tbody');
    tbody.innerHTML = '';

    productos.forEach((producto, index) => {
        const fila = tbody.insertRow();
        const precioConDescuento = producto.precio_unitario * (1 - producto.descuento / 100);
        
        fila.innerHTML = `
            <td>${producto.nombre.split(' - ')[0]}</td>
            <td>${producto.cantidad}</td>
            <td>S/. ${producto.precio_unitario.toFixed(2)}</td>
            <td>${producto.descuento}%</td>
            <td>S/. ${producto.subtotal.toFixed(2)}</td>
            <td>
                <button type="button" class="btn btn-sm btn-danger" onclick="eliminarProducto(${index})">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
    });
}

function eliminarProducto(index) {
    productos.splice(index, 1);
    actualizarTablaProductos();
    actualizarTotales();
}

function limpiarFormularioProducto() {
    document.getElementById('producto_id').value = '';
    document.getElementById('cantidad').value = '';
    document.getElementById('precio_unitario').value = '';
    document.getElementById('descuento').value = '0';
}

function actualizarTotales() {
    let subtotalSum = productos.reduce((sum, producto) => sum + producto.subtotal, 0);
    let igv = subtotalSum * 0.18;
    let total = subtotalSum + igv;

    document.getElementById('subtotalDisplay').textContent = `S/. ${subtotalSum.toFixed(2)}`;
    document.getElementById('igvDisplay').textContent = `S/. ${igv.toFixed(2)}`;
    document.getElementById('totalDisplay').textContent = `S/. ${total.toFixed(2)}`;

    document.getElementById('subtotal').value = subtotalSum.toFixed(2);
    document.getElementById('igv').value = igv.toFixed(2);
    document.getElementById('total').value = total.toFixed(2);
}

function actualizarCamposOcultos() {
    const container = document.getElementById('productosHidden');
    container.innerHTML = '';

    productos.forEach((producto, index) => {
        container.innerHTML += `
            <input type="hidden" name="productos[${index}][producto_id]" value="${producto.producto_id}">
            <input type="hidden" name="productos[${index}][cantidad]" value="${producto.cantidad}">
            <input type="hidden" name="productos[${index}][precio_unitario]" value="${producto.precio_unitario}">
            <input type="hidden" name="productos[${index}][descuento]" value="${producto.descuento}">
            <input type="hidden" name="productos[${index}][subtotal]" value="${producto.subtotal}">
        `;
    });
}
</script>
@endsection