@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header del Cliente -->
    <div class="cliente-detail-header">
        <div class="header-content">
            <div class="header-left">
                <a href="{{ route('clientes.index') }}" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="cliente-avatar-large">
                    <div class="avatar-circle-large">
                        {{ substr($cliente->nombre ?? 'N/A', 0, 2) }}
                    </div>
                </div>
                <div class="header-info">
                    <h1 class="cliente-title">
                        {{ $cliente->nombre }}
                    </h1>
                    <p class="cliente-subtitle">
                        {{ $cliente->tipo_documento }} - {{ $cliente->numero_documento }}
                    </p>
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('clientes.edit', $cliente->id_cliente) }}" class="btn-edit-modern">
                    <i class="fas fa-edit me-2"></i>
                    Editar Cliente
                </a>
                <div class="status-badge {{ $cliente->activo ? 'status-active' : 'status-inactive' }}">
                    @if($cliente->activo)
                        <i class="fas fa-check-circle me-1"></i>
                        Activo
                    @else
                        <i class="fas fa-times-circle me-1"></i>
                        Inactivo
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Información del Cliente -->
        <div class="col-lg-8">
            <div class="info-card">
                <div class="info-card-header">
                    <h3><i class="fas fa-user me-2"></i>Información del Cliente</h3>
                </div>
                <div class="info-card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <label class="info-label">Nombre</label>
                            <div class="info-value">{{ $cliente->nombre }}</div>
                        </div>

                        <div class="info-item">
                            <label class="info-label">Tipo de Documento</label>
                            <div class="info-value">
                                <span class="document-badge">{{ $cliente->tipo_documento }}</span>
                            </div>
                        </div>

                        <div class="info-item">
                            <label class="info-label">Número de Documento</label>
                            <div class="info-value document-number">{{ $cliente->numero_documento }}</div>
                        </div>

                        @if($cliente->telefono)
                        <div class="info-item">
                            <label class="info-label">Teléfono</label>
                            <div class="info-value">
                                <i class="fas fa-phone me-2 text-muted"></i>
                                <a href="tel:{{ $cliente->telefono }}">{{ $cliente->telefono }}</a>
                            </div>
                        </div>
                        @endif

                        @if($cliente->correo)
                        <div class="info-item">
                            <label class="info-label">Correo Electrónico</label>
                            <div class="info-value">
                                <i class="fas fa-envelope me-2 text-muted"></i>
                                <a href="mailto:{{ $cliente->correo }}">{{ $cliente->correo }}</a>
                            </div>
                        </div>
                        @endif

                        @if($cliente->direccion)
                        <div class="info-item full-width">
                            <label class="info-label">Dirección</label>
                            <div class="info-value">
                                <i class="fas fa-map-marker-alt me-2 text-muted"></i>
                                {{ $cliente->direccion }}
                            </div>
                        </div>
                        @endif

                        <div class="info-item">
                            <label class="info-label">Fecha de Registro</label>
                            <div class="info-value">
                                <i class="fas fa-calendar me-2 text-muted"></i>
                                {{ $cliente->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>

                        <div class="info-item">
                            <label class="info-label">Última Actualización</label>
                            <div class="info-value">
                                <i class="fas fa-clock me-2 text-muted"></i>
                                {{ $cliente->updated_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas y Resumen -->
        <div class="col-lg-4">
            <div class="stats-summary">
                <div class="stats-card-small">
                    <div class="stats-icon ventas">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stats-info">
                        <div class="stats-number">{{ $cliente->ventas->count() }}</div>
                        <div class="stats-label">Total Ventas</div>
                    </div>
                </div>

                <div class="stats-card-small">
                    <div class="stats-icon ingresos">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stats-info">
                        <div class="stats-number">S/ {{ number_format($cliente->ventas->sum('total'), 2) }}</div>
                        <div class="stats-label">Ingresos Totales</div>
                    </div>
                </div>

                <div class="stats-card-small">
                    <div class="stats-icon ultima-venta">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="stats-info">
                        @if($cliente->ventas->count() > 0)
                        <div class="stats-number">{{ $cliente->ventas->sortByDesc('created_at')->first()->created_at->diffForHumans() }}</div>
                        <div class="stats-label">Última Venta</div>
                        @else
                        <div class="stats-number">-</div>
                        <div class="stats-label">Sin Ventas</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Historial de Ventas -->
    <div class="ventas-section">
        <div class="ventas-header">
            <h3><i class="fas fa-chart-line me-2"></i>Historial de Ventas</h3>
            @if($cliente->ventas->count() > 0)
            <a href="{{ route('ventas.create') }}?cliente={{ $cliente->id_cliente }}" class="btn-nueva-venta">
                <i class="fas fa-plus me-1"></i>
                Nueva Venta
            </a>
            @endif
        </div>
        
        @if($cliente->ventas->count() > 0)
        <div class="ventas-table-container">
            <table class="ventas-table">
                <thead>
                    <tr>
                        <th>Venta #</th>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cliente->ventas->sortByDesc('created_at')->take(10) as $venta)
                    <tr>
                        <td>
                            <span class="venta-id">#{{ $venta->id_venta ?? $venta->id }}</span>
                        </td>
                        <td>{{ $venta->created_at->format('d/m/Y') }}</td>
                        <td>
                            @if($venta->tipoComprobante)
                            <span class="tipo-badge">{{ $venta->tipoComprobante->descripcion }}</span>
                            @else
                            <span class="tipo-badge">N/A</span>
                            @endif
                        </td>
                        <td>
                            <span class="estado-badge estado-{{ strtolower($venta->xml_estado ?? 'pendiente') }}">
                                {{ $venta->xml_estado ?? 'Pendiente' }}
                            </span>
                        </td>
                        <td class="total-amount">S/ {{ number_format($venta->total, 2) }}</td>
                        <td>
                            <div class="venta-actions">
                                <a href="{{ route('ventas.show', $venta->id_venta ?? $venta->id) }}" class="action-btn-small view">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            @if($cliente->ventas->count() > 10)
            <div class="table-footer">
                <a href="{{ route('ventas.index') }}?cliente={{ $cliente->id_cliente }}" class="ver-todas-ventas">
                    Ver todas las ventas ({{ $cliente->ventas->count() }})
                    <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            @endif
        </div>
        @else
        <div class="empty-ventas">
            <div class="empty-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h4>No hay ventas registradas</h4>
            <p>Este cliente aún no tiene ventas en el sistema.</p>
            <a href="{{ route('ventas.create') }}?cliente={{ $cliente->id_cliente }}" class="btn-primera-venta">
                <i class="fas fa-plus me-2"></i>
                Crear Primera Venta
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Estilos para la vista de detalles del cliente -->
<style>
:root {
    --primary-color: #667eea;
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-color: #28a745;
    --warning-color: #ffc107;
    --danger-color: #dc3545;
    --light-bg: #f8f9fa;
    --dark-text: #2d3748;
    --muted-text: #718096;
    --border-color: #e2e8f0;
    --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.1);
    --border-radius: 12px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Header del Cliente */
.cliente-detail-header {
    background: var(--primary-gradient);
    color: white;
    padding: 2rem;
    border-radius: var(--border-radius);
    margin-bottom: 2rem;
    box-shadow: var(--shadow-md);
}

.header-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.back-btn {
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    transition: var(--transition);
    backdrop-filter: blur(10px);
}

.back-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateX(-5px);
    color: white;
    text-decoration: none;
}

.cliente-avatar-large {
    margin-right: 1rem;
}

.avatar-circle-large {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 700;
    text-transform: uppercase;
    backdrop-filter: blur(10px);
    border: 3px solid rgba(255, 255, 255, 0.3);
}

.cliente-title {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.cliente-subtitle {
    margin: 0;
    opacity: 0.9;
    font-size: 1rem;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.btn-edit-modern {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition);
    border: 2px solid rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(10px);
}

.btn-edit-modern:hover {
    background: white;
    color: var(--primary-color);
    transform: translateY(-2px);
    text-decoration: none;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.875rem;
    backdrop-filter: blur(10px);
}

.status-active {
    background: rgba(40, 167, 69, 0.3);
    color: white;
    border: 2px solid rgba(40, 167, 69, 0.5);
}

.status-inactive {
    background: rgba(220, 53, 69, 0.3);
    color: white;
    border: 2px solid rgba(220, 53, 69, 0.5);
}

/* Tarjeta de Información */
.info-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-color);
    margin-bottom: 2rem;
}

.info-card-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    background: var(--light-bg);
    border-radius: var(--border-radius) var(--border-radius) 0 0;
}

.info-card-header h3 {
    margin: 0;
    color: var(--dark-text);
    font-weight: 600;
}

.info-card-body {
    padding: 1.5rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.info-item.full-width {
    grid-column: 1 / -1;
}

.info-label {
    font-size: 0.875rem;
    color: var(--muted-text);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-value {
    font-size: 1rem;
    color: var(--dark-text);
    font-weight: 500;
}

.info-value a {
    color: var(--primary-color);
    text-decoration: none;
    transition: var(--transition);
}

.info-value a:hover {
    color: #5a67d8;
    text-decoration: underline;
}

.document-badge {
    background: var(--primary-color);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.document-number {
    font-family: 'Courier New', monospace;
    font-weight: 600;
    font-size: 1.1rem;
}

/* Estadísticas Resumen */
.stats-summary {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 2rem;
}

.stats-card-small {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-color);
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: var(--transition);
}

.stats-card-small:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.stats-icon.ventas {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stats-icon.ingresos {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.stats-icon.ultima-venta {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
}

.stats-info {
    flex: 1;
}

.stats-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--dark-text);
    line-height: 1;
}

.stats-label {
    font-size: 0.875rem;
    color: var(--muted-text);
    margin-top: 0.25rem;
}

/* Sección de Ventas */
.ventas-section {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-color);
    overflow: hidden;
}

.ventas-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    background: var(--light-bg);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.ventas-header h3 {
    margin: 0;
    color: var(--dark-text);
    font-weight: 600;
}

.btn-nueva-venta, .btn-primera-venta {
    background: var(--primary-gradient);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition);
    border: none;
    cursor: pointer;
}

.btn-nueva-venta:hover, .btn-primera-venta:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    text-decoration: none;
    color: white;
}

/* Tabla de Ventas */
.ventas-table-container {
    overflow-x: auto;
}

.ventas-table {
    width: 100%;
    border-collapse: collapse;
}

.ventas-table thead th {
    background: var(--light-bg);
    color: var(--dark-text);
    font-weight: 600;
    padding: 1rem;
    text-align: left;
    border-bottom: 2px solid var(--border-color);
    white-space: nowrap;
}

.ventas-table tbody tr {
    transition: var(--transition);
    border-bottom: 1px solid #f1f3f4;
}

.ventas-table tbody tr:hover {
    background: var(--light-bg);
}

.ventas-table tbody td {
    padding: 1rem;
    vertical-align: middle;
}

.venta-id {
    font-family: 'Courier New', monospace;
    font-weight: 600;
    color: var(--primary-color);
}

.tipo-badge, .estado-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.tipo-badge {
    background: var(--light-bg);
    color: var(--muted-text);
}

.estado-aceptado {
    background: var(--success-color);
    color: white;
}

.estado-pendiente {
    background: var(--warning-color);
    color: #8a6d3b;
}

.estado-anulado {
    background: var(--danger-color);
    color: white;
}

.total-amount {
    font-weight: 700;
    color: var(--success-color);
    font-size: 1.1rem;
}

.venta-actions {
    display: flex;
    gap: 0.25rem;
}

.action-btn-small {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 0.75rem;
}

.action-btn-small.view {
    background: #e3f2fd;
    color: #1976d2;
}

.action-btn-small.view:hover {
    background: #1976d2;
    color: white;
    text-decoration: none;
}

.table-footer {
    padding: 1rem 1.5rem;
    text-align: center;
    border-top: 1px solid var(--border-color);
}

.ver-todas-ventas {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition);
}

.ver-todas-ventas:hover {
    color: #5a67d8;
    text-decoration: underline;
}

/* Estado Vacío */
.empty-ventas {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-icon {
    font-size: 4rem;
    color: var(--muted-text);
    margin-bottom: 1rem;
}

.empty-ventas h4 {
    color: var(--dark-text);
    margin-bottom: 0.5rem;
}

.empty-ventas p {
    color: var(--muted-text);
    margin-bottom: 2rem;
}

/* Responsive */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        align-items: stretch;
    }
    
    .header-left {
        justify-content: center;
        text-align: center;
    }
    
    .cliente-title {
        font-size: 1.5rem;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .stats-summary {
        order: -1;
    }
    
    .ventas-header {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
}
</style>

@endsection