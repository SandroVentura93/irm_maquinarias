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
                    <div class="stats-label">{{ request()->hasAny(['search', 'tipo_comprobante', 'xml_estado', 'fecha_desde', 'fecha_hasta']) ? 'Ventas Filtradas' : 'Total Ventas' }}</div>
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
            <div class="stats-card stats-danger">
                <div class="stats-icon">
                    <i class="fas fa-ban"></i>
                </div>
                <div class="stats-content">
                    <div class="stats-value">{{ $ventas->where('xml_estado', 'ANULADO')->count() }}</div>
                    <div class="stats-label">Anuladas</div>
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

    <!-- Filtros Avanzados -->
    <div class="card modern-card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-filter me-2 text-primary"></i>
                Filtros de Búsqueda
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('ventas.index') }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">
                            <i class="fas fa-search me-1"></i>
                            Buscar
                        </label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="N° Documento, Cliente..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">
                            <i class="fas fa-file-alt me-1"></i>
                            Tipo Comprobante
                        </label>
                        <select name="tipo_comprobante" class="form-select" id="tipoComprobanteFilter">
                            <option value="">Todos</option>
                            <option value="Cotización" {{ request('tipo_comprobante') == 'Cotización' ? 'selected' : '' }}>Cotización</option>
                            <option value="Factura" {{ request('tipo_comprobante') == 'Factura' ? 'selected' : '' }}>Factura</option>
                            <option value="Boleta de Venta" {{ request('tipo_comprobante') == 'Boleta de Venta' ? 'selected' : '' }}>Boleta de Venta</option>
                            <option value="Ticket de Máquina Registradora" {{ request('tipo_comprobante') == 'Ticket de Máquina Registradora' ? 'selected' : '' }}>Ticket</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">
                            <i class="fas fa-info-circle me-1"></i>
                            Estado
                        </label>
                        <select name="xml_estado" class="form-select" id="estadoFilter">
                            <option value="">Todos</option>
                            <option value="ACEPTADO" {{ request('xml_estado') == 'ACEPTADO' ? 'selected' : '' }}>Aceptado</option>
                            <option value="PENDIENTE" {{ request('xml_estado') == 'PENDIENTE' ? 'selected' : '' }}>Pendiente</option>
                            <option value="ANULADO" {{ request('xml_estado') == 'ANULADO' ? 'selected' : '' }}>Anulado</option>
                            <option value="RECHAZADO" {{ request('xml_estado') == 'RECHAZADO' ? 'selected' : '' }}>Rechazado</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">
                            <i class="fas fa-calendar me-1"></i>
                            Fecha Desde
                        </label>
                        <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">
                            <i class="fas fa-calendar me-1"></i>
                            Fecha Hasta
                        </label>
                        <input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                @if(request()->hasAny(['search', 'tipo_comprobante', 'xml_estado', 'fecha_desde', 'fecha_hasta']))
                <div class="mt-3">
                    <a href="{{ route('ventas.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-times me-1"></i>
                        Limpiar Filtros
                    </a>
                </div>
                @endif
            </form>
        </div>
    </div>

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
                                <li><hr class="dropdown-divider"></li>
                                <li class="dropdown-header">Moneda</li>
                                <li><a class="dropdown-item" href="#" onclick="filterByCurrency('all')">Todas</a></li>
                                <li><a class="dropdown-item" href="#" onclick="filterByCurrency('PEN')">Solo PEN</a></li>
                                <li><a class="dropdown-item" href="#" onclick="filterByCurrency('USD')">Solo USD</a></li>
                            </ul>
                        </div>
                        <div class="input-group input-group-sm ms-2" style="width: 200px;">
                            <span class="input-group-text">TC USD</span>
                            <input type="number" step="0.0001" min="0" class="form-control" id="tipoCambioManualLista" placeholder="3.8000">
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
                        <th class="sortable" data-sort="moneda" style="width: 100px;">
                            <div class="th-content">
                                Moneda <i class="fas fa-sort"></i>
                            </div>
                        </th>
                        <th>Estado</th>
                        <th class="text-center" style="width: 200px;">Acciones</th>
                    </tr>
                </thead>
                <tbody class="table-body">
                    @foreach($ventas as $venta)
                        <tr class="table-row" data-status="{{ $venta->xml_estado }}">
                            <td data-label="ID" class="fw-semibold text-primary">#{{ $venta->id_venta }}</td>
                            <td data-label="Cliente">
                                <div class="client-info">
                                    <div class="client-name">{{ $venta->cliente ? $venta->cliente->nombre : 'Sin cliente' }}</div>
                                    <div class="client-doc text-muted">{{ $venta->cliente ? $venta->cliente->numero_documento : '' }}</div>
                                </div>
                            </td>
                            <td data-label="Tipo Comprobante">
                                <span class="comprobante-type">
                                    {{ $venta->tipoComprobante->descripcion ?? 'N/A' }}
                                </span>
                                @php
                                    $esCotizacion = ($venta->id_tipo_comprobante == 8 || 
                                                   (isset($venta->tipoComprobante) && 
                                                    (stripos($venta->tipoComprobante->descripcion, 'cotiz') !== false)) ||
                                                   stripos($venta->serie, 'COT') !== false);
                                @endphp
                                @if($esCotizacion)
                                    <small class="badge bg-info text-white">🔄 Convertible</small>
                                @endif
                            </td>
                            <!-- <td class="fw-semibold">{{ $venta->serie }}-{{ $venta->numero }}</td> -->
                            <td data-label="Número" class="fw-semibold">
                                @if(Str::startsWith($venta->numero, $venta->serie))
                                    {{ $venta->numero }}
                                @else
                                    {{ $venta->serie }}-{{ $venta->numero }}
                                @endif
                            </td>
                            <td data-label="Fecha">
                                <div class="date-info">
                                    <div class="date">{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}</div>
                                    <div class="time text-muted">{{ \Carbon\Carbon::parse($venta->fecha)->format('H:i') }}</div>
                                </div>
                            </td>
                            <td data-label="Total">
                                @php
                                    $codigoMoneda = optional($venta->moneda)->codigo_iso ?? 'PEN';
                                    $simboloMoneda = optional($venta->moneda)->simbolo ?? ($codigoMoneda === 'USD' ? '$' : 'S/');
                                @endphp
                                <div class="amount-info">
                                    <div class="amount-single">{{ $simboloMoneda }} {{ number_format($venta->total, 2) }} <span class="badge bg-light text-dark ms-1">{{ $codigoMoneda }}</span></div>
                                </div>
                            </td>
                            <td data-label="Moneda">
                                @php
                                    $codigoMoneda = $codigoMoneda ?? 'PEN';
                                    $toggleTarget = $codigoMoneda === 'USD' ? 'PEN' : 'USD';
                                    $toggleLabel = $toggleTarget === 'USD' ? 'Cambiar a USD' : 'Cambiar a PEN';
                                    $toggleIcon = $toggleTarget === 'USD' ? 'fa-dollar-sign' : 'fa-money-bill-wave';
                                @endphp
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-secondary">{{ $codigoMoneda }}</span>
                                    <form method="POST" action="{{ route('ventas.cambiar-moneda', $venta->id_venta) }}" onsubmit="return confirm('¿Cambiar moneda de la venta a {{ $toggleTarget }}? Se convertirán los totales usando el tipo de cambio.');">
                                        @csrf
                                        <input type="hidden" name="moneda" value="{{ $toggleTarget }}">
                                        <button type="submit" class="btn btn-sm btn-outline-secondary" title="{{ $toggleLabel }}">
                                            <i class="fas {{ $toggleIcon }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td data-label="Estado">
                                <span class="status-badge status-{{ strtolower($venta->xml_estado) }}">
                                    <i class="status-icon fas {{ $venta->xml_estado === 'ACEPTADO' ? 'fa-check-circle' : ($venta->xml_estado === 'PENDIENTE' ? 'fa-clock' : ($venta->xml_estado === 'ANULADO' ? 'fa-times-circle' : 'fa-exclamation-circle')) }}"></i>
                                    {{ $venta->xml_estado }}
                                </span>
                                @if($venta->xml_estado === 'PENDIENTE')
                                    @php
                                        // Calcular saldo pendiente sin tipo de cambio: solo pagos en misma moneda
                                        $codigoMoneda = optional($venta->moneda)->codigo_iso ?? 'PEN';
                                        $simboloMoneda = optional($venta->moneda)->simbolo ?? ($codigoMoneda === 'USD' ? '$' : 'S/');
                                        $totalPagado = 0;
                                        foreach (($venta->pagos ?? []) as $pago) {
                                            $monPago = strtoupper($pago->moneda ?? 'PEN');
                                            if ($monPago === strtoupper($codigoMoneda)) {
                                                $totalPagado += (float)($pago->monto ?? 0);
                                            }
                                        }
                                        $saldoCalculado = max(((float)($venta->total ?? 0)) - $totalPagado, 0);
                                    @endphp
                                    <div class="text-danger small mt-1">Saldo pendiente: <b>{{ $simboloMoneda }} {{ number_format($saldoCalculado, 2) }}</b> <span class="badge bg-light text-dark ms-1">{{ $codigoMoneda }}</span></div>
                                @endif
                            </td>
                            <td data-label="Acciones">
                                <div class="action-buttons">
                                    <a href="{{ route('ventas.show', $venta) }}" class="btn btn-action btn-view" title="Ver Detalle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($venta->xml_estado !== 'ANULADO')
                                        @if($esCotizacion)
                                            <button type="button" class="btn btn-action btn-pdf" title="Generar PDF" onclick="showCotizacionPdfModal({{ $venta->id_venta }})">
                                                <i class="fas fa-file-pdf"></i>
                                            </button>
                                        @else
                                            <a href="{{ route('ventas.pdf', $venta) }}" class="btn btn-action btn-pdf" title="Generar PDF" target="_blank">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        @endif
                                    @endif
                                    @push('scripts')
                                    <!-- Modal para opciones de impresión de cotización -->
                                    <div class="modal fade" id="cotizacionPdfModal" tabindex="-1" aria-labelledby="cotizacionPdfModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="cotizacionPdfModalLabel">Opciones de impresión de cotización</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-check mb-3">
                                                        <input class="form-check-input" type="checkbox" value="1" id="mostrarCodigoParte" checked>
                                                        <label class="form-check-label" for="mostrarCodigoParte">
                                                            Mostrar <b>número de parte</b> y <b>código</b> de producto en la cotización
                                                        </label>
                                                    </div>
                                                    <div class="alert alert-info small">
                                                        Si desmarcas la opción, la cotización no mostrará el código ni el número de parte de los productos.<br>
                                                        Puedes cambiar esta preferencia cada vez que imprimas.
                                                    </div>
                                                    <div class="d-grid gap-2 mt-3">
                                                        <button type="button" class="btn btn-success" onclick="procederDescargaCotizacion()">
                                                            <i class="fas fa-file-pdf me-1"></i> Descargar PDF
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                    // Evitar redeclaraciones en caso de que el script se incluya varias veces
                                    if (typeof window.cotizacionIdSeleccionada === 'undefined') {
                                        window.cotizacionIdSeleccionada = null;
                                    }

                                    if (typeof window.showCotizacionPdfModal === 'undefined') {
                                        window.showCotizacionPdfModal = function(idCotizacion) {
                                            window.cotizacionIdSeleccionada = idCotizacion;
                                            const modal = new bootstrap.Modal(document.getElementById('cotizacionPdfModal'));
                                            modal.show();
                                        };
                                    }

                                    if (typeof window.procederDescargaCotizacion === 'undefined') {
                                        window.procederDescargaCotizacion = function() {
                                            if (!window.cotizacionIdSeleccionada) return;
                                            const checkbox = document.getElementById('mostrarCodigoParte');
                                            const mostrar = checkbox ? (checkbox.checked ? 1 : 0) : 1;
                                            const url = `/ventas/${window.cotizacionIdSeleccionada}/pdf?mostrar_codigo_parte=${mostrar}`;
                                            // DEBUG: mostrar en la consola el valor y la URL usada
                                            try { console.log('[DEBUG] procederDescargaCotizacion', { id: window.cotizacionIdSeleccionada, mostrar: mostrar, url: url }); } catch(e){}
                                            window.open(url, '_blank');
                                            bootstrap.Modal.getInstance(document.getElementById('cotizacionPdfModal')).hide();
                                        };
                                    }
                                    </script>
                                    @endpush
                                    <!-- Botón de pago -->

                                    @if($venta->xml_estado === 'PENDIENTE')
                                    <a href="{{ route('ventas.pago', $venta) }}" class="btn btn-action btn-pay" title="Registrar Pago">
                                        <i class="fas fa-credit-card"></i>
                                    </a>
                                    @endif

                                    @if($esCotizacion && in_array($venta->xml_estado, ['PENDIENTE', 'ENVIADO']))
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-action btn-convert dropdown-toggle" type="button" data-bs-toggle="dropdown" title="Convertir Cotización">
                                            <i class="fas fa-exchange-alt"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('ventas.convertir-factura', $venta->id_venta) }}" 
                                                   onclick="return confirm('¿Está seguro que desea convertir esta cotización a Factura?\n\nEsta acción:\n• Cambiará el tipo de comprobante\n• Generará una nueva serie y número\n• Descontará el stock de los productos\n• Cambiará el estado a PENDIENTE\n• No se puede deshacer\n\n¿Desea continuar?');">
                                                    <i class="fas fa-file-invoice text-success me-2"></i>Convertir a Factura
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('ventas.convertir-boleta', $venta->id_venta) }}" 
                                                   onclick="return confirm('¿Está seguro que desea convertir esta cotización a Boleta?\n\nEsta acción:\n• Cambiará el tipo de comprobante\n• Generará una nueva serie y número\n• Descontará el stock de los productos\n• Cambiará el estado a PENDIENTE\n• No se puede deshacer\n\n¿Desea continuar?');">
                                                    <i class="fas fa-receipt text-info me-2"></i>Convertir a Boleta
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('ventas.convertir', $venta->id_venta) }}" method="POST" style="display: inline;" 
                                                      onsubmit="return confirm('¿Está seguro que desea convertir esta cotización a Ticket?\n\nEsta acción:\n• Cambiará el tipo de comprobante\n• Generará una nueva serie y número\n• Descontará el stock de los productos\n• Cambiará el estado a PENDIENTE\n• No se puede deshacer\n\n¿Desea continuar?');">
                                                    @csrf
                                                    <input type="hidden" name="tipo_destino" value="Ticket">
                                                    <button type="submit" class="dropdown-item" style="background: none; border: none; padding: 0; width: 100%; text-align: left; cursor: pointer;">
                                                        <i class="fas fa-ticket-alt text-warning me-2"></i>Convertir a Ticket
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                    @endif
                                    
                                    @if($venta->xml_estado === 'PENDIENTE' || ($esCotizacion && $venta->xml_estado !== 'ANULADO'))
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

/* Responsive adjustments for mobile */
@media (max-width: 992px) {
    .page-icon { width:48px; height:48px; font-size:1.15rem }
    .page-title { font-size:1.4rem }
}

@media (max-width: 768px) {
    .container-fluid { padding-left:8px; padding-right:8px }
    .page-icon { width:44px; height:44px; }
    .page-title { font-size:1.15rem }
    .page-subtitle { display:none }
    .btn-modern { width:100%; padding:10px; }
    /* Make header stack */
    .row.mb-4 { display:flex; flex-direction:column; gap:0.5rem }
    .col-lg-4.text-lg-end { text-align: left !important }

    /* Table stacked blocks */
    .table-modern thead { display:none }
    .table-modern, .table-modern tbody, .table-modern tr, .table-modern td { display:block; width:100% }
    .table-modern tr { margin-bottom:1rem; border-bottom:1px solid #eee; padding-bottom:0.5rem }
    .table-modern td { padding:0.5rem 0; display:flex; justify-content:space-between; align-items:center }
    .table-modern td:before { content: attr(data-label); font-weight:600; color:#6b7280; margin-right:0.5rem }
    .action-buttons { justify-content:flex-end }
    .btn-action { width:40px; height:40px }
}

/* Improve value alignment and wrapping on small screens */
@media (max-width: 768px) {
    .table-modern td > * { max-width: 65%; text-align: right; overflow: hidden; text-overflow: ellipsis; white-space: nowrap }
    .table-modern td .client-info, .table-modern td .date-info, .table-modern td .amount-info { text-align: right }
    .table-modern td .client-doc, .table-modern td .time { display: block; font-size: 0.75rem; color: #718096 }
    .table-modern td .amount-info { font-weight: 700 }
    /* Make action buttons wrap to a second line if needed */
    .action-buttons { gap: 0.4rem; justify-content: flex-end; flex-wrap: wrap }
    .action-buttons .btn-action { width:36px; height:36px; padding:0 }
    /* Ensure badges don't overflow */
    .status-badge { white-space: nowrap; }
}

@media (max-width:480px){
    .page-icon { display:flex; align-items:center; justify-content:center }
    .page-title{ font-size:1.05rem }
    .btn-modern{ padding:8px }
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

@endsection