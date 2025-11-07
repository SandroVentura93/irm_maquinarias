@extends('layouts.dashboard')

@section('content')
<div class="container">
    <h1 class="my-4">Registrar Nueva Venta</h1>

    <form action="{{ route('ventas.store') }}" method="POST">
        @csrf
        @php
            $usuarioAutenticado = Auth::user();
            $fechaActual = date('Y-m-d');
        @endphp

        <!-- Información de la Venta -->
        <div class="card mb-4">
            <div class="card-header">Información de la Venta</div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="fecha" name="fecha" value="{{ $fechaActual }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="dni_ruc_cliente" class="form-label">Buscar Cliente por DNI o RUC</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="dni_ruc_cliente" placeholder="Ingrese DNI o RUC del cliente">
                            <button type="button" class="btn btn-secondary" id="buscarCliente">Buscar</button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="cliente" class="form-label">Cliente</label>
                        <select class="form-select" id="cliente" name="cliente_id" required>
                            <option value="">Seleccione un cliente</option>
                            <!-- Aquí se llenarán los clientes dinámicamente -->
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Agregar Productos -->
        <div class="card mb-4">
            <div class="card-header">Agregar Productos</div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="buscar_producto" class="form-label">Buscar Producto por Nombre</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="buscar_producto" placeholder="Ingrese el nombre del producto">
                            <button type="button" class="btn btn-secondary" id="buscarProducto">Buscar</button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="producto" class="form-label">Producto</label>
                        <select class="form-select" id="producto" name="producto_id">
                            <option value="">Seleccione un producto</option>
                            <!-- Aquí se llenarán los productos dinámicamente -->
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="cantidad" class="form-label">Cantidad</label>
                        <input type="number" class="form-control" id="cantidad" name="cantidad" min="1">
                    </div>
                    <div class="col-md-3">
                        <label for="precio_venta" class="form-label">Precio</label>
                        <input type="number" class="form-control" id="precio_venta" name="precio_venta" step="0.01">
                    </div>
                </div>
                <button type="button" class="btn btn-success" id="agregarProducto">Agregar Producto</button>
            </div>
        </div>

        <!-- Detalle de la Venta -->
        <div class="card mb-4">
            <div class="card-header">Detalle de la Venta</div>
            <div class="card-body">
                <table class="table table-bordered" id="detalleVenta">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Subtotal</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Aquí se agregarán dinámicamente los productos -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Botón de Guardar -->
        <button type="submit" class="btn btn-primary">Guardar Venta</button>
    </form>
</div>

<script>
    document.getElementById('agregarProducto').addEventListener('click', function() {
        const productoSelect = document.getElementById('producto');
        const cantidadInput = document.getElementById('cantidad');
        const precioInput = document.getElementById('precio_venta');

        const productoId = productoSelect.value;
        const productoNombre = productoSelect.options[productoSelect.selectedIndex].text;
        const cantidad = cantidadInput.value;
        const precio = precioInput.value;
        const subtotal = (cantidad * precio).toFixed(2);

        if (productoId && cantidad > 0 && precio > 0) {
            const tabla = document.getElementById('detalleVenta').querySelector('tbody');
            const fila = document.createElement('tr');

            fila.innerHTML = `
                <td>${productoNombre}</td>
                <td>${cantidad}</td>
                <td>${precio}</td>
                <td>${subtotal}</td>
                <td><button type="button" class="btn btn-danger btn-sm eliminarProducto">Eliminar</button></td>
            `;

            tabla.appendChild(fila);

            // Limpiar campos
            productoSelect.value = '';
            cantidadInput.value = '';
            precioInput.value = '';

            // Agregar evento para eliminar producto
            fila.querySelector('.eliminarProducto').addEventListener('click', function() {
                fila.remove();
            });
        } else {
            alert('Por favor, complete todos los campos del producto.');
        }
    });

    document.getElementById('buscarCliente').addEventListener('click', function() {
        const dniRuc = document.getElementById('dni_ruc_cliente').value;

        if (dniRuc) {
            // Realizar una solicitud AJAX para buscar el cliente por DNI o RUC
            fetch(`/api/clientes?dni_ruc=${dniRuc}`)
                .then(response => response.json())
                .then(data => {
                    const clienteSelect = document.getElementById('cliente');

                    // Limpiar opciones existentes
                    clienteSelect.innerHTML = '<option value="">Seleccione un cliente</option>';

                    if (data.length > 0) {
                        data.forEach(cliente => {
                            const option = document.createElement('option');
                            option.value = cliente.id;
                            option.textContent = `${cliente.nombre} (${cliente.dni || cliente.ruc})`;
                            clienteSelect.appendChild(option);
                        });
                    } else {
                        alert('No se encontró ningún cliente con ese DNI o RUC.');
                    }
                })
                .catch(error => {
                    console.error('Error al buscar el cliente:', error);
                    alert('Hubo un error al buscar el cliente.');
                });
        } else {
            alert('Por favor, ingrese un DNI o RUC.');
        }
    });

    document.getElementById('buscarProducto').addEventListener('click', function() {
        const nombreProducto = document.getElementById('buscar_producto').value;

        if (nombreProducto) {
            // Realizar una solicitud AJAX para buscar productos por nombre
            fetch(`/api/productos?nombre=${nombreProducto}`)
                .then(response => response.json())
                .then(data => {
                    const productoSelect = document.getElementById('producto');

                    // Limpiar opciones existentes
                    productoSelect.innerHTML = '<option value="">Seleccione un producto</option>';

                    if (data.length > 0) {
                        data.forEach(producto => {
                            const option = document.createElement('option');
                            option.value = producto.id_producto;
                            option.textContent = `${producto.descripcion} - ${producto.precio_venta}`;
                            productoSelect.appendChild(option);
                        });
                    } else {
                        alert('No se encontró ningún producto con ese nombre.');
                    }
                })
                .catch(error => {
                    console.error('Error al buscar el producto:', error);
                    alert('Hubo un error al buscar el producto.');
                });
        } else {
            alert('Por favor, ingrese el nombre del producto.');
        }
    });

    document.getElementById('producto').addEventListener('change', function() {
        const productoId = this.value;

        if (productoId) {
            // Realizar una solicitud AJAX para obtener el precio del producto seleccionado
            fetch(`/api/productos/${productoId}`)
                .then(response => response.json())
                .then(data => {
                    const precioInput = document.getElementById('precio_venta');

                    if (data && data.precio_venta) {
                        precioInput.value = data.precio_venta;
                    } else {
                        alert('No se pudo obtener el precio del producto.');
                    }
                })
                .catch(error => {
                    console.error('Error al obtener el precio del producto:', error);
                    alert('Hubo un error al obtener el precio del producto.');
                });
        } else {
            document.getElementById('precio_venta').value = '';
        }
    });

    // Búsqueda dinámica de clientes por DNI o RUC
    document.getElementById('dni_ruc_cliente').addEventListener('input', function() {
        const dniRuc = this.value;

        if (dniRuc.length >= 3) { // Realizar la búsqueda después de 3 caracteres
            fetch(`/api/clientes?dni_ruc=${dniRuc}`)
                .then(response => response.json())
                .then(data => {
                    const clienteSelect = document.getElementById('cliente');

                    clienteSelect.innerHTML = '<option value="">Seleccione un cliente</option>';

                    if (data.length > 0) {
                        data.forEach(cliente => {
                            const option = document.createElement('option');
                            option.value = cliente.id;
                            option.textContent = `${cliente.nombre} (${cliente.dni || cliente.ruc})`;
                            clienteSelect.appendChild(option);
                        });
                    } else {
                        clienteSelect.innerHTML = '<option value="">No se encontraron clientes</option>';
                    }
                })
                .catch(error => {
                    console.error('Error al buscar el cliente:', error);
                });
        }
    });

    // Búsqueda dinámica de productos por nombre
    document.getElementById('buscar_producto').addEventListener('input', function() {
        const nombreProducto = this.value;

        if (nombreProducto.length >= 3) { // Realizar la búsqueda después de 3 caracteres
            fetch(`/api/productos?nombre=${nombreProducto}`)
                .then(response => response.json())
                .then(data => {
                    const productoSelect = document.getElementById('producto');

                    productoSelect.innerHTML = '<option value="">Seleccione un producto</option>';

                    if (data.length > 0) {
                        data.forEach(producto => {
                            const option = document.createElement('option');
                            option.value = producto.id_producto;
                            option.textContent = `${producto.descripcion} - ${producto.precio_venta}`;
                            productoSelect.appendChild(option);
                        });
                    } else {
                        productoSelect.innerHTML = '<option value="">No se encontraron productos</option>';
                    }
                })
                .catch(error => {
                    console.error('Error al buscar el producto:', error);
                });
        }
    });
</script>
@endsection