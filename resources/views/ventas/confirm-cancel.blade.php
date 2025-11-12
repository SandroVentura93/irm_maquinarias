@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h4><i class="fas fa-exclamation-triangle"></i> Confirmar Anulación de Venta</h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            <strong>Error:</strong> {{ session('error') }}
                        </div>
                    @endif
                    
                    @if(session('success'))
                        <div class="alert alert-success">
                            <strong>Éxito:</strong> {{ session('success') }}
                        </div>
                    @endif
                    
                    <div class="alert alert-warning">
                        <strong>¡Atención!</strong> Esta acción anulará la venta y revertirá el stock de todos los productos. Esta operación no se puede deshacer.
                    </div>

                    <h5>Detalles de la Venta</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Número:</strong> {{ $venta->serie }}-{{ $venta->numero }}</p>
                            <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i') }}</p>
                            <p><strong>Cliente:</strong> {{ $venta->cliente->razon_social ?: $venta->cliente->nombre }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Vendedor:</strong> {{ $venta->vendedor ? $venta->vendedor->nombre : 'Sin asignar' }}</p>
                            <p><strong>Estado Actual:</strong> 
                                <span class="badge badge-{{ $venta->xml_estado === 'ACEPTADO' ? 'success' : 'info' }}">
                                    {{ $venta->xml_estado }}
                                </span>
                            </p>
                            <p><strong>Total:</strong> <span class="text-success font-weight-bold">S/ {{ number_format($venta->total, 2) }}</span></p>
                        </div>
                    </div>

                    <h5>Productos que serán revertidos al stock</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Código</th>
                                    <th>Producto</th>
                                    <th>Cantidad Vendida</th>
                                    <th>Stock Actual</th>
                                    <th>Stock Después</th>
                                    <th>Precio</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalReversion = 0; @endphp
                                @foreach($venta->detalleVentas as $detalle)
                                <tr>
                                    <td>{{ $detalle->producto->codigo }}</td>
                                    <td>{{ $detalle->producto->descripcion }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-warning">{{ $detalle->cantidad }}</span>
                                    </td>
                                    <td class="text-center">{{ $detalle->producto->stock_actual }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-success">{{ $detalle->producto->stock_actual + $detalle->cantidad }}</span>
                                    </td>
                                    <td class="text-right">S/ {{ number_format($detalle->total, 2) }}</td>
                                </tr>
                                @php $totalReversion += $detalle->total; @endphp
                                @endforeach
                            </tbody>
                            <tfoot class="thead-light">
                                <tr>
                                    <th colspan="5" class="text-right">Total a Revertir:</th>
                                    <th class="text-right">S/ {{ number_format($totalReversion, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mt-4">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('ventas.index') }}" class="btn btn-secondary btn-block">
                                    <i class="fas fa-arrow-left"></i> Cancelar y Volver
                                </a>
                            </div>
                            <div class="col-md-6">
                                <form action="{{ route('ventas.cancel', $venta) }}" method="POST" id="cancelForm">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="motivo" value="Anulación desde interfaz web">
                                    <button type="button" class="btn btn-danger btn-block" id="confirmButton">
                                        <i class="fas fa-times"></i> Confirmar Anulación
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado - iniciando script de cancelación');
    
    var form = document.getElementById('cancelForm');
    var button = document.getElementById('confirmButton');
    
    console.log('Formulario encontrado:', form);
    console.log('Botón encontrado:', button);
    console.log('Acción del formulario:', form ? form.action : 'No encontrado');
    console.log('Método del formulario:', form ? form.method : 'No encontrado');
    
    if (button && form) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Botón de cancelación clickeado');
            
            // Confirmar la acción
            if (confirm('¿Estás completamente seguro de anular esta venta?\n\nEsta acción:\n- Cambiará el estado a ANULADO\n- Revertirá el stock de todos los productos\n- NO se puede deshacer\n\n¿Continuar?')) {
                console.log('Usuario confirmó la anulación');
                
                // Deshabilitar botón y mostrar estado de carga
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Anulando venta...';
                button.disabled = true;
                
                // Debug: mostrar datos del formulario
                console.log('Enviando formulario...');
                console.log('URL destino:', form.action);
                console.log('Método:', form.method);
                
                // Verificar que tenemos CSRF token
                var csrfToken = form.querySelector('input[name="_token"]');
                console.log('CSRF Token encontrado:', csrfToken ? csrfToken.value : 'NO');
                
                var methodInput = form.querySelector('input[name="_method"]');
                console.log('Method input encontrado:', methodInput ? methodInput.value : 'NO');
                
                // Enviar formulario
                try {
                    form.submit();
                    console.log('Formulario enviado exitosamente');
                } catch (error) {
                    console.error('Error al enviar formulario:', error);
                    button.innerHTML = '<i class="fas fa-times"></i> Error - Reintentar';
                    button.disabled = false;
                    alert('Error al enviar el formulario. Por favor, intenta nuevamente.');
                }
            } else {
                console.log('Usuario canceló la operación');
            }
        });
        
        console.log('Event listener agregado exitosamente');
    } else {
        console.error('Error: No se pudo encontrar el formulario o el botón');
        console.error('Elementos en el DOM:');
        console.error('- Formularios:', document.querySelectorAll('form').length);
        console.error('- Botones:', document.querySelectorAll('button').length);
    }
});
</script>
@endsection