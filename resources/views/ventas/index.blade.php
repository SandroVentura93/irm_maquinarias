@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="d-flex align-items-center">
                <div class="page-icon me-3">
                    <i class="fas fa-shopping-cart text-primary"></i>
                </div>
                <div>
                    <h1 class="page-title mb-0">Gestión de Ventas</h1>
                    <p class="page-subtitle text-muted mb-0">Administra todas las ventas y comprobantes electrónicos</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 text-lg-end">
            <a href="{{ route('ventas.create') }}" class="btn btn-primary btn-modern">
                <i class="fas fa-plus me-2"></i> Nueva Venta
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stats-card stats-primary">
                <div class="stats-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stats-content">
                    <div class="stats-value">{{ count($ventas) }}</div>
                    <div class="stats-label">Total Ventas</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card stats-success">
                <div class="stats-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stats-content">
                    <div class="stats-value">{{ $ventas->where('xml_estado', 'ACEPTADO')->count() }}</div>
                    <div class="stats-label">Aceptadas</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card stats-warning">
                <div class="stats-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stats-content">
                    <div class="stats-value">{{ $ventas->where('xml_estado', 'PENDIENTE')->count() }}</div>
                    <div class="stats-label">Pendientes</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card stats-info">
                <div class="stats-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stats-content">
                    <div class="stats-value">{{ number_format($tipoCambio, 2) }}</div>
                    <div class="stats-label">Tipo Cambio USD</div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-modern alert-dismissible fade show" role="alert">
            <div class="alert-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="alert-content">
                <strong>¡Éxito!</strong> {{ session('success') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-modern alert-dismissible fade show" role="alert">
            <div class="alert-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="alert-content">
                <strong>Error:</strong> {{ session('error') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Modal de Pago (Mejorado visualmente con saldo incluido) -->
    <div class="modal fade" id="modalPago" tabindex="-1" aria-labelledby="modalPagoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalPagoLabel">Registrar Pago</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Información del saldo en ambas monedas -->
                    <div class="alert alert-info mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-0">
                                    <i class="fas fa-flag-pe me-2"></i>
                                    <strong>Soles:</strong> <span id="saldoPendienteSoles" class="fw-bold fs-5">S/ 0.00</span>
                                </h6>
                            </div>
                            <div class="col-md-6">
                                <h6 class="mb-0">
                                    <i class="fas fa-flag-usa me-2"></i>
                                    <strong>Dólares:</strong> <span id="saldoPendienteDolares" class="fw-bold fs-5">$ 0.00</span>
                                </h6>
                            </div>
                        </div>
                        <div class="mt-2 text-muted small d-flex align-items-center justify-content-between">
                            <span>
                                <i class="fas fa-exchange-alt me-1"></i>
                                Tipo de cambio: S/ <span id="tipoCambioDisplay" class="fw-bold" title="Obtenido desde SUNAT">...</span> por USD
                            </span>
                            <span class="badge bg-info" id="fuenteTipoCambio">
                                <i class="fas fa-sync-alt"></i> Cargando...
                            </span>
                        </div>
                    </div>

                    <form id="formPago" method="POST" action="{{ route('ventas.pago') }}">
                        @csrf
                        <input type="hidden" name="id_venta" id="pago_id_venta">
                        <div class="mb-3">
                            <label for="pago_monto" class="form-label">Monto a Pagar</label>
                            <input type="number" step="0.01" class="form-control" id="pago_monto" name="monto" required>
                        </div>
                        <div class="mb-3">
                            <label for="pago_metodo" class="form-label">Método de Pago</label>
                            <select class="form-select" id="pago_metodo" name="metodo" required>
                                <option value="">Seleccione</option>
                                <option value="Efectivo">Efectivo</option>
                                <option value="Tarjeta">Tarjeta</option>
                                <option value="Transferencia">Transferencia</option>
                                <option value="Yape">Yape</option>
                                <option value="Plin">Plin</option>
                            </select>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-success">Registrar Pago</button>
                        </div>
                    </form>

                    <!-- Historial de Pagos -->
                    <div class="mt-4">
                        <h6>Historial de Pagos</h6>
                        <ul id="historialPagos" class="list-group">
                            <!-- Los pagos se cargarán dinámicamente aquí -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    /* Estilos mejorados para el modal */
    #modalPago .modal-content {
        background-color: #f8f9fa; /* Fondo elegante */
        border-radius: 12px; /* Bordes redondeados */
        box-shadow: 0 8px 25px -8px rgba(0, 0, 0, 0.2); /* Sombra elegante */
    }
    #modalPago .modal-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }
    #modalPago .modal-body {
        padding: 2rem; /* Espaciado interno */
    }
    .alert-info {
        background-color: #e9f7fd; /* Fondo azul claro */
        border: 1px solid #bce8f1; /* Borde azul */
        color: #31708f; /* Texto azul oscuro */
        border-radius: 8px; /* Bordes redondeados */
    }
    .list-group-item {
        border: none; /* Sin bordes */
        border-bottom: 1px solid rgba(0, 0, 0, 0.1); /* Línea divisoria */
    }
    .list-group-item:last-child {
        border-bottom: none; /* Eliminar línea divisoria del último elemento */
    }
    </style>

    <script>
    // Configuración del modal mejorado
    console.log('Inicializando funcionalidad del modalPago...');

    document.addEventListener('DOMContentLoaded', function () {
        const modalPago = document.getElementById('modalPago');

        if (!modalPago) {
            console.error('No se encontró el elemento #modalPago');
            return;
        }

        modalPago.addEventListener('show.bs.modal', async function (event) {
            const button = event.relatedTarget;

            if (!button) {
                console.error('No se encontró el botón que disparó el modal');
                return;
            }

            const idVenta = button.getAttribute('data-id');
            const saldo = button.getAttribute('data-saldo');
            const pagos = button.getAttribute('data-pagos');

            if (!idVenta || !saldo) {
                console.error('Faltan atributos de datos en el botón que disparó el modal');
                return;
            }

            console.log('Cargando datos en el modal:', { idVenta, saldo, pagos });

            // Obtener tipo de cambio desde múltiples fuentes (SOLO desde SUNAT)
            let tipoCambio = null;
            let fuenteTipoCambio = 'Obteniendo...';
            
            // Intentar obtener el tipo de cambio actualizado desde la API interna
            try {
                const responseTipoCambio = await fetch('/ventas/tipo-cambio');
                const contentType = responseTipoCambio.headers.get('content-type');
                
                // Verificar si la respuesta es JSON
                if (contentType && contentType.includes('application/json')) {
                    const dataTipoCambio = await responseTipoCambio.json();
                    
                    if (dataTipoCambio.success && dataTipoCambio.tipo_cambio) {
                        tipoCambio = dataTipoCambio.tipo_cambio;
                        fuenteTipoCambio = dataTipoCambio.fuente || 'API Externa';
                        console.log('✓ Tipo de cambio obtenido de API interna:', tipoCambio, 'Fuente:', fuenteTipoCambio);
                    }
                } else {
                    console.warn('API interna no disponible, intentando SUNAT directamente...');
                    throw new Error('Respuesta no es JSON');
                }
            } catch (error) {
                console.warn('Error con API interna, intentando SUNAT directamente:', error);
                
                // Fallback: Consultar directamente a SUNAT
                try {
                    const fecha = new Date().toISOString().split('T')[0];
                    const responseSunat = await fetch(`https://api.apis.net.pe/v1/tipo-cambio-sunat?fecha=${fecha}`);
                    const dataSunat = await responseSunat.json();
                    
                    if (dataSunat && dataSunat.compra) {
                        tipoCambio = parseFloat(dataSunat.compra);
                        fuenteTipoCambio = 'SUNAT (compra)';
                        console.log('✓ Tipo de cambio obtenido de SUNAT directo:', tipoCambio);
                    } else if (dataSunat && dataSunat.venta) {
                        tipoCambio = parseFloat(dataSunat.venta);
                        fuenteTipoCambio = 'SUNAT (venta)';
                        console.log('⚠ Tipo de cambio obtenido de SUNAT (venta):', tipoCambio);
                    }
                } catch (errorSunat) {
                    console.error('Error al obtener tipo de cambio de SUNAT:', errorSunat);
                    // Si falla todo, usar 3.38 como último recurso
                    tipoCambio = 3.38;
                    fuenteTipoCambio = 'Fallback';
                }
            }
            
            // Validar que tenemos un tipo de cambio válido
            if (!tipoCambio || tipoCambio <= 0) {
                tipoCambio = 3.38; // Último recurso
                fuenteTipoCambio = 'Fallback';
                console.warn('⚠ Usando tipo de cambio de fallback:', tipoCambio);
            }
            
            // Calcular montos en ambas monedas
            const saldoNumerico = parseFloat(saldo);
            const saldoSoles = saldoNumerico;
            const saldoDolares = saldoNumerico / tipoCambio;

            document.getElementById('pago_id_venta').value = idVenta;
            document.getElementById('pago_monto').value = saldo;
            
            // Actualizar displays de monedas con información de fuente
            document.getElementById('saldoPendienteSoles').textContent = `S/ ${saldoSoles.toFixed(2)}`;
            document.getElementById('saldoPendienteDolares').textContent = `$ ${saldoDolares.toFixed(2)}`;
            
            // Mostrar tipo de cambio con fuente
            const tipoCambioElement = document.getElementById('tipoCambioDisplay');
            tipoCambioElement.textContent = tipoCambio.toFixed(4);
            tipoCambioElement.title = `Fuente: ${fuenteTipoCambio}`;
            
            // Actualizar badge de fuente
            const fuenteBadge = document.getElementById('fuenteTipoCambio');
            if (fuenteBadge) {
                fuenteBadge.innerHTML = `<i class="fas fa-check-circle"></i> ${fuenteTipoCambio}`;
                fuenteBadge.className = fuenteTipoCambio.includes('SUNAT') ? 'badge bg-success' : 'badge bg-info';
            }
            
            console.log(`💱 Usando tipo de cambio: S/ ${tipoCambio.toFixed(4)} (${fuenteTipoCambio})`);

            // Cargar historial de pagos
            const historialPagos = document.getElementById('historialPagos');
            historialPagos.innerHTML = ''; // Limpiar historial previo

            if (pagos) {
                try {
                    const pagosData = JSON.parse(pagos);
                    pagosData.forEach(pago => {
                        const montoSoles = parseFloat(pago.monto);
                        const montoDolares = montoSoles / tipoCambio;
                        
                        const listItem = document.createElement('li');
                        listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                        listItem.innerHTML = `
                            <div>
                                <strong>${pago.metodo}</strong> - ${pago.fecha}
                            </div>
                            <div class="text-end">
                                <span class="badge bg-success me-2">S/ ${montoSoles.toFixed(2)}</span>
                                <span class="badge bg-primary">$ ${montoDolares.toFixed(2)}</span>
                            </div>
                        `;
                        historialPagos.appendChild(listItem);
                    });
                } catch (error) {
                    console.error('Error al procesar el historial de pagos:', error);
                }
            } else {
                const listItem = document.createElement('li');
                listItem.className = 'list-group-item text-muted';
                listItem.textContent = 'No hay pagos registrados.';
                historialPagos.appendChild(listItem);
            }
        });

        console.log('Funcionalidad del modalPago inicializada.');
    });
    </script>

    <!-- Main Table Card -->
    <div class="card modern-card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2 text-primary"></i>
                        Lista de Ventas
                    </h5>
                </div>
                <div class="col-auto">
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                            <i class="fas fa-print me-1"></i> Imprimir
                        </button>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-filter me-1"></i> Filtrar
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="filterByStatus('all')">Todos</a></li>
                                <li><a class="dropdown-item" href="#" onclick="filterByStatus('ACEPTADO')">Aceptados</a></li>
                                <li><a class="dropdown-item" href="#" onclick="filterByStatus('PENDIENTE')">Pendientes</a></li>
                                <li><a class="dropdown-item" href="#" onclick="filterByStatus('ANULADO')">Anulados</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern">
                <thead class="table-header">
                    <tr>
                        <th class="sortable" data-sort="id">
                            <div class="th-content">
                                ID <i class="fas fa-sort"></i>
                            </div>
                        </th>
                        <th class="sortable" data-sort="cliente">
                            <div class="th-content">
                                Cliente <i class="fas fa-sort"></i>
                            </div>
                        </th>
                        <th>Tipo Comprobante</th>
                        <th class="sortable" data-sort="numero">
                            <div class="th-content">
                                Número <i class="fas fa-sort"></i>
                            </div>
                        </th>
                        <th class="sortable" data-sort="fecha">
                            <div class="th-content">
                                Fecha <i class="fas fa-sort"></i>
                            </div>
                        </th>
                        <th class="sortable" data-sort="total">
                            <div class="th-content">
                                Total <i class="fas fa-sort"></i>
                            </div>
                        </th>
                        <th>Estado</th>
                        <th class="text-center" style="width: 200px;">Acciones</th>
                    </tr>
                </thead>
                <tbody class="table-body">
                    @foreach($ventas as $venta)
                        <tr class="table-row" data-status="{{ $venta->xml_estado }}">
                            <td class="fw-semibold text-primary">#{{ $venta->id_venta }}</td>
                            <td>
                                <div class="client-info">
                                    <div class="client-name">{{ $venta->cliente ? $venta->cliente->nombre : 'Sin cliente' }}</div>
                                    <div class="client-doc text-muted">{{ $venta->cliente ? $venta->cliente->numero_documento : '' }}</div>
                                </div>
                            </td>
                            <td>
                                <span class="comprobante-type">
                                    {{ $venta->tipoComprobante->descripcion ?? 'N/A' }}
                                </span>
                                @php
                                    $esCotizacion = ($venta->id_tipo_comprobante == 8 || 
                                                   (isset($venta->tipoComprobante) && 
                                                    (stripos($venta->tipoComprobante->descripcion, 'cotiz') !== false ||
                                                     stripos($venta->tipoComprobante->codigo_sunat, 'CT') !== false)) ||
                                                   stripos($venta->serie, 'COT') !== false);
                                @endphp
                                @if($esCotizacion)
                                    <small class="badge bg-info text-white">🔄 Convertible</small>
                                @endif
                            </td>
                            <!-- <td class="fw-semibold">{{ $venta->serie }}-{{ $venta->numero }}</td> -->
                            <td class="fw-semibold">
                                @if(Str::startsWith($venta->numero, $venta->serie))
                                    {{ $venta->numero }}
                                @else
                                    {{ $venta->serie }}-{{ $venta->numero }}
                                @endif
                            </td>
                            <td>
                                <div class="date-info">
                                    <div class="date">{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}</div>
                                    <div class="time text-muted">{{ \Carbon\Carbon::parse($venta->fecha)->format('H:i') }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="amount-info">
                                    <div class="amount-pen">S/ {{ number_format($venta->total, 2) }}</div>
                                    <div class="amount-usd text-muted">${{ number_format($venta->total / $tipoCambio, 2) }}</div>
                                </div>
                            </td>
                            <td>
                                <span class="status-badge status-{{ strtolower($venta->xml_estado) }}">
                                    <i class="status-icon fas {{ $venta->xml_estado === 'ACEPTADO' ? 'fa-check-circle' : ($venta->xml_estado === 'PENDIENTE' ? 'fa-clock' : ($venta->xml_estado === 'ANULADO' ? 'fa-times-circle' : 'fa-exclamation-circle')) }}"></i>
                                    {{ $venta->xml_estado }}
                                </span>
                                @if($venta->xml_estado === 'PENDIENTE')
                                    <div class="text-danger small mt-1">Saldo pendiente: <b>S/ {{ number_format($venta->saldo, 2) }}</b></div>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('ventas.show', $venta) }}" class="btn btn-action btn-view" title="Ver Detalle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($venta->xml_estado !== 'ANULADO')
                                    <a href="{{ route('ventas.pdf', $venta) }}" class="btn btn-action btn-pdf" title="Generar PDF" target="_blank">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                    @endif
                                    <!-- Botón de pago -->

                                    @if($venta->xml_estado === 'PENDIENTE')
                                    <button class="btn btn-action btn-pay" title="Registrar Pago" data-bs-toggle="modal" data-bs-target="#modalPago" data-id="{{ $venta->id_venta }}" data-total="{{ $venta->total }}" data-saldo="{{ $venta->saldo ?? $venta->total }}" data-pagos='@json($venta->pagos)'>
                                        <i class="fas fa-credit-card"></i>
                                    </button>
                                    @endif

                                    @if($esCotizacion && $venta->xml_estado === 'PENDIENTE')
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-action btn-convert dropdown-toggle" type="button" data-bs-toggle="dropdown" title="Convertir Cotización">
                                            <i class="fas fa-exchange-alt"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item conversion-link" href="javascript:void(0)" onclick="convertirCotizacion({{ $venta->id_venta }}, 'FACTURA')">
                                                <i class="fas fa-file-invoice text-success me-2"></i>Convertir a Factura
                                            </a></li>
                                            <li><a class="dropdown-item conversion-link" href="javascript:void(0)" onclick="convertirCotizacion({{ $venta->id_venta }}, 'BOLETA')">
                                                <i class="fas fa-receipt text-info me-2"></i>Convertir a Boleta
                                            </a></li>
                                            <li><a class="dropdown-item conversion-link" href="javascript:void(0)" onclick="convertirCotizacion({{ $venta->id_venta }}, 'Ticket')">
                                                <i class="fas fa-ticket-alt text-warning me-2"></i>Convertir a Ticket
                                            </a></li>
                                        </ul>
                                    </div>
                                    @endif
                                    
                                    @if($venta->xml_estado === 'PENDIENTE')
                                    <a href="{{ route('ventas.edit', $venta) }}" class="btn btn-action btn-edit" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endif
                                    
                                    @if(in_array($venta->xml_estado, ['PENDIENTE', 'ENVIADO', 'ACEPTADO']))
                                    <a href="{{ route('ventas.confirm-cancel', $venta) }}" class="btn btn-action btn-delete" title="Anular Venta" onclick="return confirm('¿Estás seguro de anular esta venta?')">
                                        <i class="fas fa-ban"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($ventas->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h4 class="empty-title">No hay ventas registradas</h4>
            <p class="empty-subtitle">Comienza creando tu primera venta</p>
            <a href="{{ route('ventas.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nueva Venta
            </a>
        </div>
        @endif
    </div>
</div>

<style>
/* Modern Design Styles */
:root {
    --primary-color: #667eea;
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-color: #28a745;
    --success-gradient: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    --warning-color: #ffc107;
    --warning-gradient: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    --danger-color: #dc3545;
    --danger-gradient: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    --info-color: #17a2b8;
    --info-gradient: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    --border-radius: 12px;
    --box-shadow: 0 8px 25px -8px rgba(0, 0, 0, 0.1);
    --hover-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.15);
}

.page-icon {
    width: 60px;
    height: 60px;
    background: var(--primary-gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    box-shadow: var(--box-shadow);
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: #2d3748;
    margin: 0;
}

.page-subtitle {
    font-size: 1rem;
    color: #718096;
    margin: 0;
}

.btn-modern {
    border-radius: var(--border-radius);
    padding: 12px 24px;
    font-weight: 600;
    background: var(--primary-gradient);
    border: none;
    box-shadow: var(--box-shadow);
    transition: all 0.3s ease;
    text-decoration: none;
    color: white;
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: var(--hover-shadow);
    color: white;
}

/* Stats Cards */
.stats-card {
    background: white;
    border-radius: var(--border-radius);
    padding: 1.5rem;
    box-shadow: var(--box-shadow);
    transition: all 0.3s ease;
    border: 1px solid rgba(0, 0, 0, 0.05);
    position: relative;
    overflow: hidden;
}

.stats-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--primary-gradient);
}

.stats-card.stats-success::before {
    background: var(--success-gradient);
}

.stats-card.stats-warning::before {
    background: var(--warning-gradient);
}

.stats-card.stats-info::before {
    background: var(--info-gradient);
}

.stats-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--hover-shadow);
}

.stats-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
    background: var(--primary-gradient);
    margin-bottom: 1rem;
}

.stats-card.stats-success .stats-icon {
    background: var(--success-gradient);
}

.stats-card.stats-warning .stats-icon {
    background: var(--warning-gradient);
}

.stats-card.stats-info .stats-icon {
    background: var(--info-gradient);
}

.stats-value {
    font-size: 2rem;
    font-weight: 700;
    color: #2d3748;
    line-height: 1;
}

.stats-label {
    font-size: 0.875rem;
    color: #718096;
    font-weight: 500;
    margin-top: 0.25rem;
}

/* Modern Card */
.modern-card {
    border-radius: var(--border-radius);
    border: none;
    box-shadow: var(--box-shadow);
    overflow: hidden;
}

.modern-card .card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    padding: 1.5rem;
}

.modern-card .card-title {
    font-weight: 600;
    color: #2d3748;
    font-size: 1.25rem;
}

/* Modern Table */
.table-modern {
    margin: 0;
}

.table-header th {
    background: #f8f9fa;
    border: none;
    padding: 1rem 1.25rem;
    font-weight: 600;
    color: #2d3748;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    position: relative;
}

.table-header .sortable {
    cursor: pointer;
    user-select: none;
}

.table-header .sortable:hover {
    background: #e9ecef;
}

.th-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.th-content i {
    opacity: 0.5;
    font-size: 0.75rem;
}

.table-body td {
    padding: 1rem 1.25rem;
    border: none;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    vertical-align: middle;
}

.table-row:hover {
    background: rgba(102, 126, 234, 0.02);
}

.client-info .client-name {
    font-weight: 600;
    color: #2d3748;
    font-size: 0.9rem;
}

.client-info .client-doc {
    font-size: 0.75rem;
    color: #718096;
}

.comprobante-type {
    background: #e2e8f0;
    color: #4a5568;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
}

.date-info .date {
    font-weight: 600;
    color: #2d3748;
    font-size: 0.875rem;
}

.date-info .time {
    font-size: 0.75rem;
    color: #718096;
}

.amount-info .amount-pen {
    font-weight: 700;
    color: #2d3748;
    font-size: 0.9rem;
}

.amount-info .amount-usd {
    font-size: 0.75rem;
    color: #718096;
}

/* Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-aceptado {
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
    border: 1px solid rgba(40, 167, 69, 0.2);
}

.status-pendiente {
    background: rgba(255, 193, 7, 0.1);
    color: #ffc107;
    border: 1px solid rgba(255, 193, 7, 0.2);
}

.status-anulado {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
    border: 1px solid rgba(220, 53, 69, 0.2);
}

.status-rechazado {
    background: rgba(253, 126, 20, 0.1);
    color: #fd7e14;
    border: 1px solid rgba(253, 126, 20, 0.2);
}

.status-icon {
    font-size: 0.875rem;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.btn-action {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    text-decoration: none;
    cursor: pointer;
}

.btn-view {
    background: rgba(23, 162, 184, 0.1);
    color: #17a2b8;
}

.btn-view:hover {
    background: #17a2b8;
    color: white;
    transform: scale(1.1);
}

.btn-pdf {
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
}

.btn-pdf:hover {
    background: #28a745;
    color: white;
    transform: scale(1.1);
}

.btn-edit {
    background: rgba(255, 193, 7, 0.1);
    color: #ffc107;
}

.btn-edit:hover {
    background: #ffc107;
    color: white;
    transform: scale(1.1);
}

.btn-delete {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

.btn-delete:hover {
    background: #dc3545;
    color: white;
    transform: scale(1.1);
}

.btn-convert {
    background: rgba(102, 126, 234, 0.1);
    color: #667eea;
}

.btn-convert:hover {
    background: #667eea;
    color: white;
    transform: scale(1.1);
}

/* Loading states for conversion */
.conversion-link.loading {
    pointer-events: none;
    opacity: 0.7;
    position: relative;
}

.conversion-link.loading::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.8);
    border-radius: 4px;
}

.conversion-link.loading i {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

/* Alert Modern */
.alert-modern {
    border: none;
    border-radius: var(--border-radius);
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: var(--box-shadow);
}

.alert-icon {
    font-size: 1.25rem;
    flex-shrink: 0;
}

.alert-content {
    flex: 1;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-icon {
    font-size: 4rem;
    color: #e2e8f0;
    margin-bottom: 1.5rem;
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.empty-subtitle {
    color: #718096;
    margin-bottom: 2rem;
}

/* Responsive */
@media (max-width: 768px) {
    .page-title {
        font-size: 1.5rem;
    }
    
    .stats-card {
        margin-bottom: 1rem;
    }
    
    .action-buttons {
        justify-content: center;
    }
    
    .btn-action {
        width: 28px;
        height: 28px;
        font-size: 0.75rem;
    }
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.stats-card, .modern-card {
    animation: fadeInUp 0.6s ease forwards;
}

.stats-card:nth-child(1) { animation-delay: 0.1s; }
.stats-card:nth-child(2) { animation-delay: 0.2s; }
.stats-card:nth-child(3) { animation-delay: 0.3s; }
.stats-card:nth-child(4) { animation-delay: 0.4s; }
.modern-card { animation-delay: 0.5s; }
</style>

<script>
// Filter functionality
function filterByStatus(status) {
    const rows = document.querySelectorAll('.table-row');
    rows.forEach(row => {
        if (status === 'all' || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Sort functionality
document.querySelectorAll('.sortable').forEach(header => {
    header.addEventListener('click', function() {
        const sortBy = this.dataset.sort;
        // Implement sorting logic here if needed
        console.log('Sorting by:', sortBy);
    });
});

// Conversion functionality
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

    // Buscar todos los enlaces de conversión para esta venta
    const conversionLinks = document.querySelectorAll(`[onclick*="${idVenta}"]`);
    let targetLink = null;
    conversionLinks.forEach(link => {
        if (link.onclick.toString().includes(tipoDestino)) {
            targetLink = link;
        }
    });
    if (targetLink) {
        const originalHTML = targetLink.innerHTML;
        targetLink.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Convirtiendo...';
        targetLink.classList.add('loading');

        // Si es Factura o Boleta, redirigir como antes
        let url = null;
        if (tipoDestino === 'FACTURA') {
            url = `/ventas/${idVenta}/convertir-factura`;
            window.location.href = url;
            return;
        } else if (tipoDestino === 'BOLETA') {
            url = `/ventas/${idVenta}/convertir-boleta`;
            window.location.href = url;
            return;
        }

        // Si es Ticket, hacer POST AJAX al endpoint convertirCotizacion
        if (tipoDestino === 'Ticket') {
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
                targetLink.innerHTML = originalHTML;
                targetLink.classList.remove('loading');
            });
        } else {
            alert('❌ Tipo de conversión no válido');
            targetLink.innerHTML = originalHTML;
            targetLink.classList.remove('loading');
        }
    }
}

// Fix modal and backdrop layering
#modalPago {
    z-index: 1055 !important; /* Ensure modal is above backdrop */
    display: block; /* Ensure modal is displayed correctly */
    opacity: 1; /* Ensure modal is fully visible */
    pointer-events: auto; /* Allow interaction with modal */
}
.modal-backdrop {
    z-index: 1050 !important; /* Ensure backdrop is below modal */
    opacity: 0.5; /* Make backdrop semi-transparent */
    pointer-events: none; /* Prevent backdrop from blocking interaction */
}

/* Force modal display for debugging */
console.log('Debugging modalPago visibility...');

document.addEventListener('DOMContentLoaded', function () {
    const modalPago = document.getElementById('modalPago');

    if (!modalPago) {
        console.error('Modal element #modalPago not found!');
        return;
    }

    modalPago.addEventListener('show.bs.modal', function (event) {
        console.log('Modal show event triggered.');
        const button = event.relatedTarget;

        if (!button) {
            console.error('Trigger button not found!');
            return;
        }

        const idVenta = button.getAttribute('data-id');
        const saldo = button.getAttribute('data-saldo');

        if (!idVenta || !saldo) {
            console.error('Missing required data attributes on the trigger button.');
            return;
        }

        console.log('Populating modal with data:', { idVenta, saldo });

        document.getElementById('pago_id_venta').value = idVenta;
        document.getElementById('pago_monto').value = saldo;
    });

    // Force modal to show for debugging
    const debugButton = document.createElement('button');
    debugButton.setAttribute('data-bs-toggle', 'modal');
    debugButton.setAttribute('data-bs-target', '#modalPago');
    debugButton.style.display = 'none';
    document.body.appendChild(debugButton);
    debugButton.click();

    console.log('ModalPago debugging complete.');
});
</script>

@endsection