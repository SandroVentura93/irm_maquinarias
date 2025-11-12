@extends('layouts.dashboard')

@section('content')
<div class="container">
    <h1>Detalles de la Venta</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">
                Venta #{{ $venta->id_venta }}
                @if($venta->id_tipo_comprobante == 4)
                    <span class="badge badge-primary">Cotización</span>
                @elseif($venta->id_tipo_comprobante == 1)
                    <span class="badge badge-success">Factura</span>
                @elseif($venta->id_tipo_comprobante == 2)
                    <span class="badge badge-info">Boleta</span>
                @elseif($venta->id_tipo_comprobante == 3)
                    <span class="badge badge-danger">Nota de Crédito</span>
                @endif
            </h5>
            <p class="card-text"><strong>Cliente:</strong> {{ $venta->cliente->razon_social ?: $venta->cliente->nombre }}</p>
            <p class="card-text"><strong>Vendedor:</strong> {{ $venta->vendedor ? $venta->vendedor->nombre : 'Sin vendedor asignado' }}</p>
            <p class="card-text"><strong>Serie:</strong> {{ $venta->serie }}</p>
            <p class="card-text"><strong>Número:</strong> {{ $venta->numero }}</p>
            <p class="card-text"><strong>Fecha:</strong> {{ $venta->fecha }}</p>
            <p class="card-text"><strong>Subtotal:</strong> S/ {{ number_format($venta->subtotal, 2) }}</p>
            <p class="card-text"><strong>IGV:</strong> S/ {{ number_format($venta->igv, 2) }}</p>
            <p class="card-text"><strong>Total:</strong> S/ {{ number_format($venta->total, 2) }}</p>
            <p class="card-text"><strong>Estado XML:</strong> 
                <span class="badge badge-{{ $venta->xml_estado === 'ANULADO' ? 'danger' : ($venta->xml_estado === 'ACEPTADO' ? 'success' : 'info') }}">
                    {{ $venta->xml_estado }}
                </span>
            </p>
            @if($venta->xml_estado === 'ANULADO')
            <div class="alert alert-danger mt-3">
                <h6><i class="fas fa-exclamation-triangle"></i> Venta Anulada</h6>
                <p class="mb-1"><strong>Fecha de Anulación:</strong> {{ \Carbon\Carbon::parse($venta->fecha_anulacion)->format('d/m/Y H:i:s') }}</p>
                <p class="mb-0"><strong>Motivo:</strong> {{ $venta->motivo_anulacion }}</p>
            </div>
            @endif
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('ventas.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        @if($venta->xml_estado !== 'ANULADO')
        <a href="{{ route('ventas.pdf', $venta) }}" class="btn btn-success" target="_blank">
            <i class="fas fa-file-pdf"></i> Imprimir Comprobante
        </a>
        @endif
        @if($venta->xml_estado === 'PENDIENTE')
        <a href="{{ route('ventas.edit', $venta) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Editar
        </a>
        @endif
        
        {{-- Botón especial para convertir cotización --}}
        @if($venta->id_tipo_comprobante == 4 && $venta->xml_estado === 'PENDIENTE')
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-exchange-alt"></i> Convertir Cotización
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="javascript:void(0)" onclick="convertirCotizacion({{ $venta->id_venta }}, 'Factura')">
                    <i class="fas fa-file-invoice text-success"></i> Convertir a Factura
                </a>
                <a class="dropdown-item" href="javascript:void(0)" onclick="convertirCotizacion({{ $venta->id_venta }}, 'Boleta')">
                    <i class="fas fa-receipt text-info"></i> Convertir a Boleta
                </a>
            </div>
        </div>
        @endif
        
        @if(in_array($venta->xml_estado, ['PENDIENTE', 'ENVIADO', 'ACEPTADO']))
        <a href="{{ route('ventas.confirm-cancel', $venta) }}" class="btn btn-danger">
            <i class="fas fa-times"></i> Anular Venta
        </a>
        @endif
    </div>
</div>

<script>
function convertirCotizacion(idVenta, tipoDestino) {
    // Confirmar la conversión
    const mensaje = `¿Está seguro que desea convertir esta cotización a ${tipoDestino}?\n\nEsta acción:\n• Cambiará el tipo de comprobante\n• Generará una nueva serie y número\n• No se puede deshacer`;
    
    if (!confirm(mensaje)) {
        return;
    }
    
    // Mostrar loading
    const btnDropdown = document.querySelector('.btn-info.dropdown-toggle');
    const originalText = btnDropdown.innerHTML;
    btnDropdown.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Convirtiendo...';
    btnDropdown.disabled = true;
    
    // Realizar la conversión
    fetch(`/ventas/${idVenta}/convertir`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            tipo_destino: tipoDestino
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`¡Cotización convertida exitosamente!\n\nNuevo comprobante: ${data.nueva_serie}-${data.nuevo_numero}\nTipo: ${tipoDestino}`);
            // Recargar la página para mostrar los cambios
            window.location.reload();
        } else {
            alert('Error al convertir: ' + (data.error || 'Error desconocido'));
            // Restaurar botón
            btnDropdown.innerHTML = originalText;
            btnDropdown.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error de conexión al convertir la cotización');
        // Restaurar botón
        btnDropdown.innerHTML = originalText;
        btnDropdown.disabled = false;
    });
}
</script>
@endsection