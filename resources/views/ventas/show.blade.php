@extends('layouts.dashboard')

@section('content')
<div class="container-fluid modern-container">
    <!-- Modern Header -->
    <div class="page-header mb-4">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="page-icon me-3">
                    <i class="fas fa-eye"></i>
                </div>
                <div>
                    <h2 class="page-title mb-0">Detalle de Venta</h2>
                    <p class="page-subtitle mb-0">Información completa del comprobante</p>
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('ventas.index') }}" class="btn btn-outline-secondary btn-modern me-2">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </a>
                @if($venta->xml_estado !== 'ANULADO')
                <a href="{{ route('ventas.pdf', $venta) }}" class="btn btn-success btn-modern me-2" target="_blank">
                    <i class="fas fa-file-pdf me-2"></i>Imprimir PDF
                </a>
                @endif
                @if($venta->xml_estado === 'PENDIENTE')
                <a href="{{ route('ventas.edit', $venta) }}" class="btn btn-warning btn-modern">
                    <i class="fas fa-edit me-2"></i>Editar
                </a>
                @endif
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Columna Izquierda - Información Principal -->
        <div class="col-lg-8">
            <!-- Información General -->
            <div class="card modern-card mb-4">
                <div class="card-header modern-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2 text-primary"></i>
                        Información General
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="info-label">
                                    <i class="fas fa-hashtag me-1 text-primary"></i>
                                    ID de Venta
                                </label>
                                <div class="info-value large-id">#{{ $venta->id_venta }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="info-label">
                                    <i class="fas fa-file-invoice me-1 text-info"></i>
                                    Tipo de Comprobante
                                </label>
                                <div class="info-value">
                                    @if($venta->id_tipo_comprobante == 8)
                                        <span class="badge-modern badge-primary">📝 Cotización</span>
                                    @elseif($venta->id_tipo_comprobante == 1)
                                        <span class="badge-modern badge-success">📄 Factura</span>
                                    @elseif($venta->id_tipo_comprobante == 2)
                                        <span class="badge-modern badge-info">🧾 Boleta</span>
                                    @elseif($venta->id_tipo_comprobante == 3)
                                        <span class="badge-modern badge-danger">📃 Nota de Crédito</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="info-label">
                                    <i class="fas fa-barcode me-1 text-secondary"></i>
                                    Número de Comprobante
                                </label>
                                <div class="info-value comprobante-number">{{ $venta->serie }}-{{ $venta->numero }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="info-label">
                                    <i class="fas fa-calendar-alt me-1 text-warning"></i>
                                    Fecha y Hora
                                </label>
                                <div class="info-value">{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del Cliente -->
            <div class="card modern-card mb-4">
                <div class="card-header modern-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-tie me-2 text-info"></i>
                        Cliente
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="info-group">
                                <label class="info-label">
                                    <i class="fas fa-building me-1 text-info"></i>
                                    Nombre / Razón Social
                                </label>
                                <div class="info-value client-name">{{ $venta->cliente->nombre }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-group">
                                <label class="info-label">
                                    <i class="fas fa-id-card me-1 text-secondary"></i>
                                    Documento
                                </label>
                                <div class="info-value">{{ $venta->cliente->numero_documento }}</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-group">
                                <label class="info-label">
                                    <i class="fas fa-user me-1 text-success"></i>
                                    Vendedor Asignado
                                </label>
                                <div class="info-value">{{ $venta->vendedor ? $venta->vendedor->nombre : 'Sin vendedor asignado' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($venta->xml_estado === 'ANULADO')
            <!-- Información de Anulación -->
            <div class="card modern-card alert-card mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Venta Anulada
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="info-label">
                                    <i class="fas fa-clock me-1 text-danger"></i>
                                    Fecha de Anulación
                                </label>
                                <div class="info-value">{{ \Carbon\Carbon::parse($venta->fecha_anulacion)->format('d/m/Y H:i:s') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="info-label">
                                    <i class="fas fa-comment me-1 text-danger"></i>
                                    Motivo
                                </label>
                                <div class="info-value">{{ $venta->motivo_anulacion }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Columna Derecha - Totales y Estado -->
        <div class="col-lg-4">
            <!-- Estado de la Venta -->
            <div class="card modern-card mb-4">
                <div class="card-header modern-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-flag me-2 text-warning"></i>
                        Estado
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="status-display">
                        @if($venta->xml_estado === 'ACEPTADO')
                            <div class="status-icon status-success">
                                <i class="fas fa-check-circle fa-3x"></i>
                            </div>
                            <h4 class="status-text text-success">ACEPTADO</h4>
                            <p class="text-muted">Comprobante válido y aceptado</p>
                        @elseif($venta->xml_estado === 'PENDIENTE')
                            <div class="status-icon status-warning">
                                <i class="fas fa-clock fa-3x"></i>
                            </div>
                            <h4 class="status-text text-warning">PENDIENTE</h4>
                            <p class="text-muted">Esperando procesamiento</p>
                        @elseif($venta->xml_estado === 'ANULADO')
                            <div class="status-icon status-danger">
                                <i class="fas fa-times-circle fa-3x"></i>
                            </div>
                            <h4 class="status-text text-danger">ANULADO</h4>
                            <p class="text-muted">Comprobante anulado</p>
                        @else
                            <div class="status-icon status-info">
                                <i class="fas fa-exclamation-circle fa-3x"></i>
                            </div>
                            <h4 class="status-text text-info">{{ $venta->xml_estado }}</h4>
                            <p class="text-muted">Estado del comprobante</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Resumen Financiero -->
            <div class="card modern-card">
                <div class="card-header modern-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calculator me-2 text-success"></i>
                        Resumen Financiero
                    </h5>
                </div>
                <div class="card-body">
                    <div class="financial-summary">
                        <div class="summary-row">
                            <span class="summary-label">
                                <i class="fas fa-calculator me-1 text-info"></i>
                                Subtotal:
                            </span>
                            <div class="summary-amounts">
                                <span class="amount-pen">S/ {{ number_format($venta->subtotal, 2) }}</span>
                                <small class="amount-usd">${{ number_format($venta->subtotal / $tipoCambio, 2) }}</small>
                            </div>
                        </div>
                        
                        <div class="summary-row">
                            <span class="summary-label">
                                <i class="fas fa-percent me-1 text-warning"></i>
                                IGV (18%):
                            </span>
                            <div class="summary-amounts">
                                <span class="amount-pen">S/ {{ number_format($venta->igv, 2) }}</span>
                                <small class="amount-usd">${{ number_format($venta->igv / $tipoCambio, 2) }}</small>
                            </div>
                        </div>
                        
                        <hr class="my-3">
                        
                        <div class="summary-row total-row">
                            <span class="summary-label fw-bold">
                                <i class="fas fa-money-bill-wave me-1 text-success"></i>
                                TOTAL:
                            </span>
                            <div class="summary-amounts">
                                <span class="amount-pen-total">S/ {{ number_format($venta->total, 2) }}</span>
                                <small class="amount-usd">${{ number_format($venta->total / $tipoCambio, 2) }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="tipo-cambio-info text-center">
                            <i class="fas fa-exchange-alt text-warning me-2"></i>
                            <small class="text-muted">Tipo de Cambio: S/ {{ number_format($tipoCambio, 2) }} por USD</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones Especiales -->
    @if($venta->id_tipo_comprobante == 8 && $venta->xml_estado === 'PENDIENTE')
    <div class="row mt-4">
        <div class="col-12">
            <div class="card modern-card special-actions">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exchange-alt me-2"></i>
                        Convertir Cotización
                    </h5>
                </div>
                <div class="card-body text-center">
                    <p class="text-muted mb-3">Esta cotización puede ser convertida a un comprobante fiscal</p>
                    <div class="d-flex gap-3 justify-content-center">
                        <button class="btn btn-success btn-modern" onclick="convertirCotizacion({{ $venta->id_venta }}, 'Factura')">
                            <i class="fas fa-file-invoice me-2"></i>Convertir a Factura
                        </button>
                        <button class="btn btn-primary btn-modern" onclick="convertirCotizacion({{ $venta->id_venta }}, 'Boleta')">
                            <i class="fas fa-receipt me-2"></i>Convertir a Boleta
                        </button>
                        <button class="btn btn-warning btn-modern" onclick="convertirCotizacion({{ $venta->id_venta }}, 'Ticket')">
                            <i class="fas fa-ticket-alt me-2"></i>Convertir a Ticket
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(in_array($venta->xml_estado, ['PENDIENTE', 'ENVIADO', 'ACEPTADO']))
    <!-- Zona de Peligro -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card modern-card danger-zone">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Zona de Peligro
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">
                        <i class="fas fa-warning me-1 text-warning"></i>
                        Esta acción es permanente y no se puede deshacer
                    </p>
                    <a href="{{ route('ventas.confirm-cancel', $venta) }}" class="btn btn-danger btn-modern">
                        <i class="fas fa-ban me-2"></i>Anular Venta
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
<style>
/* Variables CSS para diseño moderno */
:root {
    --primary-color: #667eea;
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-color: #28a745;
    --success-gradient: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    --info-color: #17a2b8;
    --info-gradient: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    --warning-color: #ffc107;
    --warning-gradient: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    --danger-color: #dc3545;
    --danger-gradient: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    --border-radius: 12px;
    --box-shadow: 0 8px 25px -8px rgba(0, 0, 0, 0.1);
    --hover-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.15);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Container principal */
.modern-container {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: 100vh;
    padding: 2rem 0;
}

/* Header moderno */
.page-header {
    background: white;
    border-radius: var(--border-radius);
    padding: 2rem;
    box-shadow: var(--box-shadow);
    margin-bottom: 2rem;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.page-icon {
    width: 60px;
    height: 60px;
    background: var(--info-gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: white;
    box-shadow: var(--box-shadow);
}

.page-title {
    font-size: 2.2rem;
    font-weight: 700;
    color: #2d3748;
    margin: 0;
}

.page-subtitle {
    font-size: 1rem;
    color: #718096;
    margin: 0;
}

/* Header actions */
.header-actions {
    display: flex;
    gap: 0.75rem;
    align-items: center;
}

/* Cards modernas */
.modern-card {
    border-radius: var(--border-radius);
    border: none;
    box-shadow: var(--box-shadow);
    overflow: hidden;
    background: white;
    transition: var(--transition);
}

.modern-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--hover-shadow);
}

.modern-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    padding: 1.5rem;
}

/* Grupos de información */
.info-group {
    margin-bottom: 1rem;
}

.info-label {
    font-weight: 600;
    color: #4a5568;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
    display: block;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-value {
    font-size: 1rem;
    color: #2d3748;
    font-weight: 500;
    padding: 0.5rem 0;
}

.large-id {
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary-color);
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.comprobante-number {
    font-family: 'Courier New', monospace;
    font-size: 1.2rem;
    font-weight: 700;
    color: #2d3748;
    background: #f8f9fa;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    display: inline-block;
}

.client-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--info-color);
}

/* Badges modernos */
.badge-modern {
    padding: 0.75rem 1.25rem;
    border-radius: 25px;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.badge-primary {
    background: var(--primary-gradient);
    color: white;
}

.badge-success {
    background: var(--success-gradient);
    color: white;
}

.badge-info {
    background: var(--info-gradient);
    color: white;
}

.badge-danger {
    background: var(--danger-gradient);
    color: white;
}

/* Estado display */
.status-display {
    padding: 2rem 1rem;
}

.status-icon {
    margin-bottom: 1.5rem;
}

.status-success {
    color: var(--success-color);
}

.status-warning {
    color: var(--warning-color);
}

.status-danger {
    color: var(--danger-color);
}

.status-info {
    color: var(--info-color);
}

.status-text {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    letter-spacing: 1px;
}

/* Resumen financiero */
.financial-summary {
    padding: 1rem 0;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding: 0.5rem 0;
}

.summary-row.total-row {
    border-top: 2px solid #e9ecef;
    padding-top: 1rem;
    margin-top: 1rem;
    font-size: 1.1rem;
}

.summary-label {
    font-weight: 600;
    color: #4a5568;
}

.summary-amounts {
    text-align: right;
}

.amount-pen {
    font-weight: 700;
    color: #2d3748;
    display: block;
    font-size: 1rem;
}

.amount-pen-total {
    font-weight: 800;
    color: var(--success-color);
    font-size: 1.3rem;
    display: block;
    background: #e8f5e8;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    border: 2px solid #d4edda;
}

.amount-usd {
    font-size: 0.8rem;
    color: #718096;
    display: block;
}

.tipo-cambio-info {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    padding: 0.75rem;
    border-radius: 8px;
    border: 1px solid #f0d000;
}

/* Botones modernos */
.btn-modern {
    border-radius: 8px;
    font-weight: 600;
    padding: 0.75rem 1.5rem;
    transition: var(--transition);
    border: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.875rem;
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: var(--hover-shadow);
}

.btn-success.btn-modern {
    background: var(--success-gradient);
    color: white;
}

.btn-primary.btn-modern {
    background: var(--primary-gradient);
    color: white;
}

.btn-warning.btn-modern {
    background: var(--warning-gradient);
    color: white;
}

.btn-danger.btn-modern {
    background: var(--danger-gradient);
    color: white;
}

.btn-outline-secondary.btn-modern {
    background: white;
    color: #6c757d;
    border: 2px solid #e9ecef;
}

.btn-outline-secondary.btn-modern:hover {
    background: #6c757d;
    color: white;
}

/* Cards especiales */
.alert-card .card-header {
    background: var(--danger-gradient) !important;
}

.special-actions .card-header {
    background: var(--info-gradient) !important;
}

.danger-zone .card-header {
    background: var(--danger-gradient) !important;
}

/* Animaciones */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modern-card {
    animation: slideInUp 0.5s ease-out;
}

.modern-card:nth-child(1) { animation-delay: 0.1s; }
.modern-card:nth-child(2) { animation-delay: 0.2s; }
.modern-card:nth-child(3) { animation-delay: 0.3s; }

/* Responsive */
@media (max-width: 768px) {
    .page-title {
        font-size: 1.8rem;
    }
    
    .page-icon {
        width: 50px;
        height: 50px;
        font-size: 1.4rem;
    }
    
    .header-actions {
        flex-direction: column;
        gap: 0.5rem;
        width: 100%;
    }
    
    .header-actions .btn {
        width: 100%;
    }
    
    .large-id {
        font-size: 1.5rem;
    }
    
    .comprobante-number {
        font-size: 1rem;
    }
    
    .summary-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .summary-amounts {
        text-align: left;
        width: 100%;
    }
}

/* Loading states */
.btn-modern:disabled {
    opacity: 0.7;
    transform: none !important;
    cursor: not-allowed;
}

.loading {
    position: relative;
    overflow: hidden;
}

.loading::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% {
        left: -100%;
    }
    100% {
        left: 100%;
    }
}
</style>

<script>
function convertirCotizacion(idVenta, tipoDestino) {
    // Confirmar la conversión con un mensaje más moderno
    const mensaje = `¿Está seguro que desea convertir esta cotización a ${tipoDestino}?

    Esta acción:
    • Cambiará el tipo de comprobante
    • Generará una nueva serie y número  
    • No se puede deshacer

    ¿Desea continuar?`;
    if (!confirm(mensaje)) {
        return;
    }

    // Buscar el botón clickeado
    const buttons = document.querySelectorAll('.btn-modern');
    let targetButton = null;
    buttons.forEach(btn => {
        if (btn.textContent.includes(tipoDestino)) {
            targetButton = btn;
        }
    });
    if (targetButton) {
        const originalText = targetButton.innerHTML;
        targetButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Convirtiendo...';
        targetButton.disabled = true;
        targetButton.classList.add('loading');

        // Si es Factura o Boleta, redirigir como antes
        let url = null;
        if (tipoDestino.toLowerCase() === 'factura') {
            url = `/ventas/${idVenta}/convertir-factura`;
            window.location.href = url;
            return;
        } else if (tipoDestino.toLowerCase() === 'boleta') {
            url = `/ventas/${idVenta}/convertir-boleta`;
            window.location.href = url;
            return;
        }

        // Si es Ticket, hacer POST AJAX al endpoint convertirCotizacion
        if (tipoDestino.toLowerCase() === 'ticket') {
            fetch(`/ventas/${idVenta}/convertir`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ tipo_destino: 'Ticket' })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.error || 'No se pudo convertir.'));
                }
            })
            .catch(() => {
                alert('Error de red al convertir.');
            })
            .finally(() => {
                targetButton.innerHTML = originalText;
                targetButton.disabled = false;
                targetButton.classList.remove('loading');
            });
        } else {
            alert('❌ Tipo de conversión no válido');
            targetButton.innerHTML = originalText;
            targetButton.disabled = false;
            targetButton.classList.remove('loading');
        }
    }
}

// Animaciones adicionales cuando se carga la página
document.addEventListener('DOMContentLoaded', function() {
    // Animar el ícono de estado
    const statusIcon = document.querySelector('.status-icon i');
    if (statusIcon) {
        statusIcon.style.animation = 'pulse 2s infinite';
    }
    
    // Animar los totales
    const amounts = document.querySelectorAll('.amount-pen, .amount-pen-total');
    amounts.forEach((amount, index) => {
        setTimeout(() => {
            amount.style.animation = 'slideInUp 0.5s ease-out';
        }, index * 100);
    });
});

// Añadir animación de pulso
const style = document.createElement('style');
style.textContent = `
    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
        }
    }
`;
document.head.appendChild(style);
</script>
@endsection