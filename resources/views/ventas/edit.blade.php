@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-edit"></i> Editar Venta #{{ $venta->serie }}-{{ $venta->numero }}</h4>
                </div>
                <div class="card-body">
                    @if($venta->xml_estado !== 'PENDIENTE')
                    <div class="alert alert-warning">
                        <strong>Advertencia:</strong> Esta venta tiene estado "{{ $venta->xml_estado }}". Solo se pueden editar ventas en estado "PENDIENTE".
                    </div>
                    <div class="text-center">
                        <a href="{{ route('ventas.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver al Listado
                        </a>
                    </div>
                    @else
                    
                    <form action="{{ route('ventas.update', $venta) }}" method="POST" id="ventaForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Información de la Venta -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha">Fecha <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="fecha" name="fecha" 
                                           value="{{ \Carbon\Carbon::parse($venta->fecha)->format('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hora">Hora <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control" id="hora" name="hora" 
                                           value="{{ \Carbon\Carbon::parse($venta->fecha)->format('H:i') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cliente_id">Cliente <span class="text-danger">*</span></label>
                                    <select class="form-control" id="cliente_id" name="cliente_id" required>
                                        <option value="">Seleccione un cliente</option>
                                        @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id_cliente }}" {{ $venta->id_cliente == $cliente->id_cliente ? 'selected' : '' }}>
                                            {{ $cliente->razon_social ?: $cliente->nombre }} ({{ $cliente->numero_documento }})
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="serie">Serie <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="serie" name="serie" 
                                           value="{{ $venta->serie }}" required maxlength="10">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="correlativo">Correlativo <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="correlativo" name="correlativo" 
                                           value="{{ $venta->numero }}" required maxlength="20">
                                </div>
                            </div>
                        </div>

                        <!-- Productos -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5><i class="fas fa-boxes"></i> Productos</h5>
                            </div>
                            <div class="card-body">
                                <div id="productos-container">
                                    @foreach($venta->detalleVentas as $index => $detalle)
                                    <div class="producto-row border p-3 mb-3" data-index="{{ $index }}">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Producto <span class="text-danger">*</span></label>
                                                <select class="form-control producto-select" name="productos[{{ $index }}][producto_id]" required>
                                                    <option value="">Seleccionar producto</option>
                                                    @foreach($productos as $producto)
                                                    <option value="{{ $producto->id_producto }}" 
                                                            data-precio="{{ $producto->precio_venta }}"
                                                            {{ $detalle->id_producto == $producto->id_producto ? 'selected' : '' }}>
                                                        {{ $producto->descripcion }} - S/ {{ number_format($producto->precio_venta, 2) }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Cantidad <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control cantidad-input" 
                                                       name="productos[{{ $index }}][cantidad]" 
                                                       value="{{ $detalle->cantidad }}" min="1" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Precio Unit.</label>
                                                <input type="number" class="form-control precio-input" 
                                                       name="productos[{{ $index }}][precio_unitario]" 
                                                       value="{{ $detalle->precio_unitario }}" step="0.01" readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Desc. %</label>
                                                <input type="number" class="form-control descuento-input" 
                                                       name="productos[{{ $index }}][descuento_porcentaje]" 
                                                       value="{{ $detalle->descuento_porcentaje }}" min="0" max="100" step="0.1">
                                            </div>
                                            <div class="col-md-2">
                                                <label>Precio Final</label>
                                                <input type="number" class="form-control precio-final-input" 
                                                       name="productos[{{ $index }}][precio_final]" 
                                                       value="{{ $detalle->precio_final }}" step="0.01" readonly>
                                                <button type="button" class="btn btn-danger btn-sm mt-1 remove-product">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                
                                <button type="button" class="btn btn-success" id="add-product">
                                    <i class="fas fa-plus"></i> Agregar Producto
                                </button>
                            </div>
                        </div>

                        <!-- Resumen -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h5>Resumen de la Venta</h5>
                                    </div>
                                    <div class="col-md-4">
                                        <table class="table">
                                            <tr>
                                                <td><strong>Subtotal:</strong></td>
                                                <td class="text-right"><span id="subtotal-display">S/ 0.00</span></td>
                                            </tr>
                                            <tr>
                                                <td><strong>IGV (18%):</strong></td>
                                                <td class="text-right"><span id="igv-display">S/ 0.00</span></td>
                                            </tr>
                                            <tr class="table-active">
                                                <td><strong>Total:</strong></td>
                                                <td class="text-right"><strong><span id="total-display">S/ 0.00</span></strong></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-center">
                            <a href="{{ route('ventas.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Actualizar Venta
                            </button>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let productoIndex = {{ $venta->detalleVentas->count() }};
    
    // Función para calcular totales
    function calcularTotales() {
        let subtotal = 0;
        
        document.querySelectorAll('.producto-row').forEach(function(row) {
            const cantidad = parseFloat(row.querySelector('.cantidad-input').value) || 0;
            const precioFinal = parseFloat(row.querySelector('.precio-final-input').value) || 0;
            subtotal += cantidad * precioFinal;
        });
        
        const igv = subtotal * 0.18;
        const total = subtotal + igv;
        
        document.getElementById('subtotal-display').textContent = 'S/ ' + subtotal.toFixed(2);
        document.getElementById('igv-display').textContent = 'S/ ' + igv.toFixed(2);
        document.getElementById('total-display').textContent = 'S/ ' + total.toFixed(2);
    }
    
    // Función para calcular precio con descuento
    function calcularPrecioFinal(row) {
        const precio = parseFloat(row.querySelector('.precio-input').value) || 0;
        const descuento = parseFloat(row.querySelector('.descuento-input').value) || 0;
        const precioFinal = precio * (1 - descuento / 100);
        row.querySelector('.precio-final-input').value = precioFinal.toFixed(2);
        calcularTotales();
    }
    
    // Event listeners para productos existentes
    document.querySelectorAll('.producto-row').forEach(function(row) {
        const productoSelect = row.querySelector('.producto-select');
        const cantidadInput = row.querySelector('.cantidad-input');
        const descuentoInput = row.querySelector('.descuento-input');
        
        productoSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const precio = selectedOption.getAttribute('data-precio') || 0;
            row.querySelector('.precio-input').value = precio;
            calcularPrecioFinal(row);
        });
        
        cantidadInput.addEventListener('input', function() {
            calcularTotales();
        });
        
        descuentoInput.addEventListener('input', function() {
            calcularPrecioFinal(row);
        });
    });
    
    // Generar opciones de productos
    const productosOptions = `
        <option value="">Seleccionar producto</option>
        @foreach($productos as $producto)
        <option value="{{ $producto->id_producto }}" data-precio="{{ $producto->precio_venta }}">
            {{ $producto->descripcion }} - S/ {{ number_format($producto->precio_venta, 2) }}
        </option>
        @endforeach
    `;

    // Agregar producto
    document.getElementById('add-product').addEventListener('click', function() {
        const container = document.getElementById('productos-container');
        const newRow = document.createElement('div');
        newRow.className = 'producto-row border p-3 mb-3';
        newRow.setAttribute('data-index', productoIndex);
        
        newRow.innerHTML = `
            <div class="row">
                <div class="col-md-4">
                    <label>Producto <span class="text-danger">*</span></label>
                    <select class="form-control producto-select" name="productos[${productoIndex}][producto_id]" required>
                        ${productosOptions}
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Cantidad <span class="text-danger">*</span></label>
                    <input type="number" class="form-control cantidad-input" name="productos[${productoIndex}][cantidad]" value="1" min="1" required>
                </div>
                <div class="col-md-2">
                    <label>Precio Unit.</label>
                    <input type="number" class="form-control precio-input" name="productos[${productoIndex}][precio_unitario]" step="0.01" readonly>
                </div>
                <div class="col-md-2">
                    <label>Desc. %</label>
                    <input type="number" class="form-control descuento-input" name="productos[${productoIndex}][descuento_porcentaje]" value="0" min="0" max="100" step="0.1">
                </div>
                <div class="col-md-2">
                    <label>Precio Final</label>
                    <input type="number" class="form-control precio-final-input" name="productos[${productoIndex}][precio_final]" step="0.01" readonly>
                    <button type="button" class="btn btn-danger btn-sm mt-1 remove-product">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        
        container.appendChild(newRow);
        
        // Agregar event listeners al nuevo row
        const productoSelect = newRow.querySelector('.producto-select');
        const cantidadInput = newRow.querySelector('.cantidad-input');
        const descuentoInput = newRow.querySelector('.descuento-input');
        
        productoSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const precio = selectedOption.getAttribute('data-precio') || 0;
            newRow.querySelector('.precio-input').value = precio;
            calcularPrecioFinal(newRow);
        });
        
        cantidadInput.addEventListener('input', function() {
            calcularTotales();
        });
        
        descuentoInput.addEventListener('input', function() {
            calcularPrecioFinal(newRow);
        });
        
        productoIndex++;
    });
    
    // Remover producto
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-product') || e.target.parentElement.classList.contains('remove-product')) {
            const row = e.target.closest('.producto-row');
            row.remove();
            calcularTotales();
        }
    });
    
    // Calcular totales iniciales
    calcularTotales();
});
</script>
@endsection