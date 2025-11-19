
@extends('layouts.dashboard')

@section('content')
<div class="container py-4">
    <h2>Editar Compra</h2>
    <form action="{{ route('compras.update', $compra->id_compra) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="id_proveedor" class="form-label">Proveedor</label>
                <select name="id_proveedor" id="id_proveedor" class="form-control" required>
                    @foreach($proveedores as $proveedor)
                        <option value="{{ $proveedor->id_proveedor }}" {{ $compra->id_proveedor == $proveedor->id_proveedor ? 'selected' : '' }}>{{ $proveedor->razon_social }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="id_moneda" class="form-label">Moneda</label>
                <select name="id_moneda" id="id_moneda" class="form-control" required>
                    @foreach($monedas as $moneda)
                        <option value="{{ $moneda->id_moneda }}" {{ $compra->id_moneda == $moneda->id_moneda ? 'selected' : '' }}>{{ $moneda->descripcion }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="fecha" class="form-label">Fecha</label>
                <input type="datetime-local" name="fecha" id="fecha" class="form-control" value="{{ date('Y-m-d\TH:i', strtotime($compra->fecha)) }}" required>
            </div>
        </div>
        <h4>Productos</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                    <th>IGV</th>
                    <th>Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="productos-table">
                @foreach($compra->detalles as $i => $detalle)
                <tr>
                    <td>
                        <select name="detalles[{{ $i }}][id_producto]" class="form-control" required>
                            <option value="">Seleccione...</option>
                            @foreach($productos as $producto)
                                <option value="{{ $producto->id_producto }}" {{ $detalle->id_producto == $producto->id_producto ? 'selected' : '' }}>{{ $producto->descripcion }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" name="detalles[{{ $i }}][cantidad]" class="form-control" min="1" value="{{ $detalle->cantidad }}" required oninput="calcularFila(this)"></td>
                    <td><input type="number" step="0.01" name="detalles[{{ $i }}][precio_unitario]" class="form-control" value="{{ $detalle->precio_unitario }}" required oninput="calcularFila(this)"></td>
                    <td><input type="number" step="0.01" name="detalles[{{ $i }}][subtotal]" class="form-control" value="{{ $detalle->subtotal ?? ($detalle->cantidad * $detalle->precio_unitario) }}" readonly></td>
                    <td><input type="number" step="0.01" name="detalles[{{ $i }}][igv]" class="form-control" value="{{ $detalle->igv ?? (($detalle->cantidad * $detalle->precio_unitario) * 0.18) }}" readonly></td>
                    <td><input type="number" step="0.01" name="detalles[{{ $i }}][total]" class="form-control" value="{{ $detalle->total ?? (($detalle->cantidad * $detalle->precio_unitario) * 1.18) }}" readonly></td>
                    <td><button type="button" class="btn btn-danger" onclick="this.closest('tr').remove();calcularTotales();">-</button></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <button type="button" class="btn btn-secondary mb-3" onclick="agregarFilaProducto()">Agregar Producto</button>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="subtotal" class="form-label">Subtotal</label>
                <input type="number" step="0.01" name="subtotal" id="subtotal" class="form-control" value="{{ $compra->subtotal }}" required readonly>
            </div>
            <div class="col-md-4">
                <label for="igv" class="form-label">IGV</label>
                <input type="number" step="0.01" name="igv" id="igv" class="form-control" value="{{ $compra->igv }}" required readonly>
            </div>
            <div class="col-md-4">
                <label for="total" class="form-label">Total</label>
                <input type="number" step="0.01" name="total" id="total" class="form-control" value="{{ $compra->total }}" required readonly>
            </div>
        </div>
        <button type="button" class="btn btn-info mb-3" onclick="calcularTotales()">Calcular</button>
        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('compras.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<script>
let fila = {{ count($compra->detalles) }};
function agregarFilaProducto() {
    let table = document.getElementById('productos-table');
    let row = document.createElement('tr');
    row.innerHTML = `<td><select name="detalles[${fila}][id_producto]" class="form-control" required><option value="">Seleccione...</option>@foreach($productos as $producto)<option value="{{ $producto->id_producto }}">{{ $producto->descripcion }}</option>@endforeach</select></td><td><input type="number" name="detalles[${fila}][cantidad]" class="form-control" min="1" required oninput="calcularFila(this)"></td><td><input type="number" step="0.01" name="detalles[${fila}][precio_unitario]" class="form-control" required oninput="calcularFila(this)"></td><td><input type="number" step="0.01" name="detalles[${fila}][subtotal]" class="form-control" readonly></td><td><input type="number" step="0.01" name="detalles[${fila}][igv]" class="form-control" readonly></td><td><input type="number" step="0.01" name="detalles[${fila}][total]" class="form-control" readonly></td><td><button type="button" class="btn btn-danger" onclick="this.closest('tr').remove();calcularTotales();">-</button></td>`;
    table.appendChild(row);
    fila++;
}

function calcularFila(input) {
    let row = input.closest('tr');
    let cantidad = parseFloat(row.querySelector('input[name*="[cantidad]"]').value) || 0;
    let precio = parseFloat(row.querySelector('input[name*="[precio_unitario]"]').value) || 0;
    let sub = cantidad * precio;
    let igvProd = sub * 0.18;
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
    rows.forEach(row => {
        let sub = parseFloat(row.querySelector('input[name*="[subtotal]"]').value) || 0;
        let igvProd = parseFloat(row.querySelector('input[name*="[igv]"]').value) || 0;
        let totProd = parseFloat(row.querySelector('input[name*="[total]"]').value) || 0;
        subtotal += sub;
        igv += igvProd;
        total += totProd;
    });
    document.getElementById('subtotal').value = subtotal.toFixed(2);
    document.getElementById('igv').value = igv.toFixed(2);
    document.getElementById('total').value = total.toFixed(2);
}
</script>
@endsection
