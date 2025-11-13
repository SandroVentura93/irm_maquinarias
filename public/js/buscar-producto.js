let detalle = [];

function agregarProducto(prod) {
    detalle.push({
        id: prod.id,
        nombre: prod.nombre,
        cantidad: 1,
        precio: prod.precio_venta,
        descuento: 0
    });
    renderDetalle();
}

function renderDetalle() {
    let html = '';
    detalle.forEach((item, index) => {
        html += `
            <tr>
                <td>${item.nombre}</td>
                <td>${item.cantidad}</td>
                <td>${item.precio.toFixed(2)}</td>
                <td>${item.descuento.toFixed(2)}</td>
                <td>${(item.cantidad * item.precio - item.descuento).toFixed(2)}</td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarProducto(${index})">Eliminar</button>
                </td>
            </tr>
        `;
    });
    $('#detalleVenta tbody').html(html);
    calcularTotales();
}

function calcularTotales() {
    let subtotal = 0;
    detalle.forEach(item => {
        subtotal += (item.precio * item.cantidad) - item.descuento;
    });
    let igv = subtotal * 0.18;
    let total = subtotal + igv;

    $('#subtotal').text(subtotal.toFixed(2));
    $('#igv').text(igv.toFixed(2));
    $('#total').text(total.toFixed(2));
}

function eliminarProducto(index) {
    detalle.splice(index, 1);
    renderDetalle();
}