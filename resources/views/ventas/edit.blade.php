@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @php
                        $esCotizacion = ($venta->id_tipo_comprobante == 8 ||
                            (isset($venta->tipoComprobante) &&
                             (stripos($venta->tipoComprobante->descripcion, 'cotiz') !== false ||
                              stripos($venta->tipoComprobante->codigo_sunat ?? '', 'CT') !== false)) ||
                            stripos($venta->serie, 'COT') !== false);
                    @endphp
                    <h4>
                        <i class="fas fa-edit"></i>
                        {{ $esCotizacion ? 'Editar Cotización' : 'Editar Venta' }} #{{ $venta->serie }}-{{ $venta->numero }}
                    </h4>
                </div>
                <div class="card-body">
                    @php
                        $esCotizacion = ($venta->id_tipo_comprobante == 8 ||
                            (isset($venta->tipoComprobante) &&
                             (stripos($venta->tipoComprobante->descripcion, 'cotiz') !== false ||
                              stripos($venta->tipoComprobante->codigo_sunat ?? '', 'CT') !== false)) ||
                            stripos($venta->serie, 'COT') !== false);
                        $editablePorEstado = $venta->xml_estado === 'PENDIENTE' || ($esCotizacion && $venta->xml_estado !== 'ANULADO');
                    @endphp
                    @if(!$editablePorEstado)
                    <div class="alert alert-warning">
                        <strong>Advertencia:</strong> Esta venta tiene estado "{{ $venta->xml_estado }}" y no es editable.
                        Solo se pueden editar ventas en estado "PENDIENTE" o cotizaciones en cualquier estado excepto "ANULADO".
                    </div>
                    <div class="text-center">
                        <a href="{{ route('ventas.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver al Listado
                        </a>
                    </div>
                    @else
                    
                    <!-- Errores de validación -->
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    
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
                                    <label for="id_cliente">Cliente <span class="text-danger">*</span></label>
                                    <select class="form-control" id="id_cliente" name="id_cliente" required>
                                        <option value="">Seleccione un cliente</option>
                                        @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id_cliente }}" {{ $venta->id_cliente == $cliente->id_cliente ? 'selected' : '' }}>
                                            {{ $cliente->nombre }} ({{ $cliente->numero_documento }})
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
                                    <label for="numero">Correlativo <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="numero" name="numero" 
                                           value="{{ $venta->numero }}" required maxlength="20">
                                </div>
                            </div>
                        </div>

                        <!-- Mostrar todos los campos de la venta -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="xml_estado">Estado XML</label>
                                    <input type="text" class="form-control" id="xml_estado" name="xml_estado" value="{{ $venta->xml_estado }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="moneda">Moneda</label>
                                    @php
                                        // Resolver ISO desde relación u origen string
                                        $codigoIsoSelect = is_object($venta->moneda) ? ($venta->moneda->codigo_iso ?? 'PEN') : ($venta->moneda ?? 'PEN');
                                    @endphp
                                    <select class="form-control" id="moneda" name="moneda" required>
                                        <option value="PEN" {{ $codigoIsoSelect === 'PEN' ? 'selected' : '' }}>Soles (PEN)</option>
                                        <option value="USD" {{ $codigoIsoSelect === 'USD' ? 'selected' : '' }}>Dólares (USD)</option>
                                    </select>
                                    <input type="hidden" id="tipoCambio" value="{{ number_format($tipoCambio ?? 3.75, 4, '.', '') }}">
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
                                                <select class="form-control producto-select" name="detalle[{{ $index }}][id_producto]" required>
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
                                                       name="detalle[{{ $index }}][cantidad]" 
                                                       value="{{ $detalle->cantidad }}" min="1" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Precio Unit.</label>
                                                <div class="input-group input-group-sm">
                                                    @php
                                                        $codigoIso = is_object($venta->moneda) ? ($venta->moneda->codigo_iso ?? 'PEN') : ($venta->moneda ?? 'PEN');
                                                    @endphp
                                                    <span class="input-group-text" id="simboloDetalle{{ $index }}">{{ $codigoIso === 'USD' ? '$' : 'S/' }}</span>
                                                    <input type="number" class="form-control precio-input" 
                                                           name="detalle[{{ $index }}][precio_unitario]" 
                                                           value="{{ $detalle->precio_unitario }}" step="0.01" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Desc. %</label>
                                                <input type="number" class="form-control descuento-input" 
                                                       name="detalle[{{ $index }}][descuento_porcentaje]" 
                                                       value="{{ $detalle->descuento_porcentaje }}" min="0" max="100" step="0.1">
                                            </div>
                                            <div class="col-md-2">
                                                <label>Precio Final</label>
                                                <input type="number" class="form-control precio-final-input" 
                                                       name="detalle[{{ $index }}][precio_final]" 
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
                                        @php
                                            $codigoIso = is_object($venta->moneda) ? ($venta->moneda->codigo_iso ?? 'PEN') : ($venta->moneda ?? 'PEN');
                                            $simboloMoneda = is_object($venta->moneda) ? ($venta->moneda->simbolo ?? 'S/') : ($codigoIso === 'USD' ? '$' : 'S/');
                                            $icono = $codigoIso === 'USD' ? 'fas fa-dollar-sign' : 'fas fa-money-bill-wave';
                                        @endphp
                                        <table class="table">
                                            <tr>
                                                <td><strong>Subtotal:</strong> <span class="badge bg-secondary" id="ventaMonedaBadge">{{ $codigoIso }}</span></td>
                                                <td class="text-right"><span id="subtotal-display"><i class="{{ $icono }} me-1"></i>{{ $simboloMoneda }} 0.00</span></td>
                                            </tr>
                                            <tr>
                                                <td><strong>IGV (18%):</strong></td>
                                                <td class="text-right">
                                                    <span id="igv-display"><i class="{{ $icono }} me-1"></i>{{ $simboloMoneda }} 0.00</span>
                                                    <input class="form-check-input ml-2" type="checkbox" id="igv-checkbox" checked>
                                                    <label class="form-check-label" for="igv-checkbox">Aplicar</label>
                                                </td>
                                            </tr>
                                            <tr class="table-active">
                                                <td><strong>Total:</strong></td>
                                                <td class="text-right"><strong><span id="total-display"><i class="{{ $icono }} me-1"></i>{{ $simboloMoneda }} 0.00</span></strong></td>
                                            </tr>
                                        </table>
                                        
                                        <!-- Campos ocultos para los totales -->
                                        <input type="hidden" name="subtotal" id="subtotal-input" value="{{ $venta->subtotal }}">
                                        <input type="hidden" name="igv" id="igv-input" value="{{ $venta->igv }}">
                                        <input type="hidden" name="total" id="total-input" value="{{ $venta->total }}">
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
    const TIPO_CAMBIO = parseFloat(document.getElementById('tipoCambio')?.value || '3.75');
    
    // Manejar envío del formulario
    document.getElementById('ventaForm').addEventListener('submit', function(e) {
        // Validar que hay al menos un producto
        const productosRows = document.querySelectorAll('.producto-row');
        if (productosRows.length === 0) {
            e.preventDefault();
            alert('Debe agregar al menos un producto a la venta');
            return false;
        }
        
        // Calcular totales antes de enviar
        calcularTotales();
        
        // Validar totales
        const total = parseFloat(document.getElementById('total-input').value);
        if (total <= 0) {
            e.preventDefault();
            alert('El total de la venta debe ser mayor a cero');
            return false;
        }
        
        // Mostrar mensaje de carga
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Actualizando...';
        
        return true;
    });
    
    // Función para calcular totales
    function calcularTotales() {
        let subtotal = 0;
        
        document.querySelectorAll('.producto-row').forEach(function(row) {
            const cantidad = parseFloat(row.querySelector('.cantidad-input').value) || 0;
            const precioFinal = parseFloat(row.querySelector('.precio-final-input').value) || 0;
            subtotal += cantidad * precioFinal;
        });
        
        let igv = 0;
        if (document.getElementById('igv-checkbox').checked) {
            igv = subtotal * 0.18;
        }

        const total = subtotal + igv;
        
        // Actualizar displays
        const monedaSel = document.getElementById('moneda');
        const codigoIso = monedaSel ? monedaSel.value : '{{ is_object($venta->moneda) ? ($venta->moneda->codigo_iso ?? 'PEN') : ($venta->moneda ?? 'PEN') }}';
        const simbolo = codigoIso === 'USD' ? '$' : 'S/';
        const icono = codigoIso === 'USD' ? 'fas fa-dollar-sign' : 'fas fa-money-bill-wave';
        document.getElementById('ventaMonedaBadge').textContent = codigoIso;
        document.getElementById('subtotal-display').innerHTML = `<i class="${icono} me-1"></i>${simbolo} ${subtotal.toFixed(2)}`;
        document.getElementById('igv-display').innerHTML = `<i class="${icono} me-1"></i>${simbolo} ${igv.toFixed(2)}`;
        document.getElementById('total-display').innerHTML = `<i class="${icono} me-1"></i>${simbolo} ${total.toFixed(2)}`;
        
        // Actualizar campos ocultos para el formulario
        document.getElementById('subtotal-input').value = subtotal.toFixed(2);
        document.getElementById('igv-input').value = igv.toFixed(2);
        document.getElementById('total-input').value = total.toFixed(2);
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
            const precioCatalogoPen = parseFloat(selectedOption.getAttribute('data-precio') || '0');
            const monedaVenta = document.getElementById('moneda')?.value || 'PEN';
            // Convertir precio de catálogo (PEN) a moneda de la venta
            const precioUnit = monedaVenta === 'USD' ? (precioCatalogoPen / TIPO_CAMBIO) : precioCatalogoPen;
            row.querySelector('.precio-input').value = precioUnit.toFixed(2);
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
                    <select class="form-control producto-select" name="detalle[${productoIndex}][id_producto]" required>
                        ${productosOptions}
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Cantidad <span class="text-danger">*</span></label>
                    <input type="number" class="form-control cantidad-input" name="detalle[${productoIndex}][cantidad]" value="1" min="1" required>
                </div>
                <div class="col-md-2">
                    <label>Precio Unit.</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" id="simboloDetalle${productoIndex}">S/</span>
                        <input type="number" class="form-control precio-input" name="detalle[${productoIndex}][precio_unitario]" step="0.01" readonly>
                    </div>
                </div>
                <div class="col-md-2">
                    <label>Desc. %</label>
                    <input type="number" class="form-control descuento-input" name="detalle[${productoIndex}][descuento_porcentaje]" value="0" min="0" max="100" step="0.1">
                </div>
                <div class="col-md-2">
                    <label>Precio Final</label>
                    <input type="number" class="form-control precio-final-input" name="detalle[${productoIndex}][precio_final]" step="0.01" readonly>
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
        // Ajustar símbolo inicial según moneda actual
        const monedaActual = document.getElementById('moneda')?.value || 'PEN';
        const simboloInicial = monedaActual === 'USD' ? '$' : 'S/';
        const simboloSpan = newRow.querySelector(`#simboloDetalle${productoIndex}`);
        if (simboloSpan) simboloSpan.textContent = simboloInicial;
        
        productoSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const precioCatalogoPen = parseFloat(selectedOption.getAttribute('data-precio') || '0');
            const monedaVenta = document.getElementById('moneda')?.value || 'PEN';
            const precioUnit = monedaVenta === 'USD' ? (precioCatalogoPen / TIPO_CAMBIO) : precioCatalogoPen;
            newRow.querySelector('.precio-input').value = precioUnit.toFixed(2);
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
    // Actualizar símbolos cuando cambia la moneda
    const monedaSel = document.getElementById('moneda');
    if (monedaSel) {
        monedaSel.addEventListener('change', function() {
            const codigoIso = this.value;
            const simboloChar = codigoIso === 'USD' ? '$' : 'S/';
            document.getElementById('ventaMonedaBadge').textContent = codigoIso;
            document.querySelectorAll('[id^="simboloDetalle"]').forEach(function(span){
                span.textContent = simboloChar;
            });
            // Reconvertir todos los precios unitarios a la moneda de la venta
            document.querySelectorAll('.producto-row').forEach(function(row) {
                const select = row.querySelector('.producto-select');
                if (!select) return;
                const selectedOption = select.options[select.selectedIndex];
                const precioCatalogoPen = parseFloat(selectedOption.getAttribute('data-precio') || '0');
                const precioUnit = codigoIso === 'USD' ? (precioCatalogoPen / TIPO_CAMBIO) : precioCatalogoPen;
                row.querySelector('.precio-input').value = precioUnit.toFixed(2);
                // Recalcular precio final para cada fila
                const descuentoInput = row.querySelector('.descuento-input');
                const descuento = parseFloat(descuentoInput?.value || '0');
                const precioFinal = precioUnit * (1 - (descuento / 100));
                row.querySelector('.precio-final-input').value = precioFinal.toFixed(2);
            });
            calcularTotales();
        });
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const igvCheckbox = document.getElementById('igv-checkbox');

    // Escuchar cambios en el checkbox
    igvCheckbox.addEventListener('change', function() {
        calcularTotales();
    });

    // Modificar la función calcularTotales para considerar el checkbox
    function calcularTotales() {
        let subtotal = 0;

        document.querySelectorAll('.producto-row').forEach(function(row) {
            const cantidad = parseFloat(row.querySelector('.cantidad-input').value) || 0;
            const precioFinal = parseFloat(row.querySelector('.precio-final-input').value) || 0;
            subtotal += cantidad * precioFinal;
        });

        let igv = 0;
        if (igvCheckbox.checked) {
            igv = subtotal * 0.18;
        }

        const total = subtotal + igv;

        // Actualizar displays
        const monedaSel = document.getElementById('moneda');
        const codigoIso = monedaSel ? monedaSel.value : 'PEN';
        const simbolo = codigoIso === 'USD' ? '$' : 'S/';
        const icono = codigoIso === 'USD' ? 'fas fa-dollar-sign' : 'fas fa-money-bill-wave';
        document.getElementById('ventaMonedaBadge').textContent = codigoIso;
        document.getElementById('subtotal-display').innerHTML = `<i class="${icono} me-1"></i>${simbolo} ${subtotal.toFixed(2)}`;
        document.getElementById('igv-display').innerHTML = `<i class="${icono} me-1"></i>${simbolo} ${igv.toFixed(2)}`;
        document.getElementById('total-display').innerHTML = `<i class="${icono} me-1"></i>${simbolo} ${total.toFixed(2)}`;

        // Actualizar campos ocultos para el formulario
        document.getElementById('subtotal-input').value = subtotal.toFixed(2);
        document.getElementById('igv-input').value = igv.toFixed(2);
        document.getElementById('total-input').value = total.toFixed(2);
    }

    // Calcular totales iniciales
    calcularTotales();
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('ventaForm');
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        // Enviar el formulario con fetch
        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (response.ok) {
                alert('Venta actualizada exitosamente');
                window.location.href = "{{ route('ventas.index') }}";
            } else {
                alert('Hubo un error al actualizar la venta');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hubo un error al procesar la solicitud');
        });
    });
});
</script>
@endsection
