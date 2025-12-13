@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header con Gradiente -->
    <div class="page-header-modern">
        <div class="header-content">
            <div class="header-left">
                <a href="{{ route('proveedores.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="header-info">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <h1 class="header-title mb-0">
                            <i class="fas fa-building me-3"></i>
                            {{ $proveedor->razon_social }}
                        </h1>
                        @if($proveedor->activo ?? true)
                            <span class="status-badge status-active">
                                <i class="fas fa-check-circle"></i> Activo
                            </span>
                        @else
                            <span class="status-badge status-inactive">
                                <i class="fas fa-times-circle"></i> Inactivo
                            </span>
                        @endif
                    </div>
                    <p class="header-subtitle">Vista detallada del proveedor</p>
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('proveedores.edit', $proveedor->id_proveedor) }}" class="btn-action-header">
                    <i class="fas fa-edit me-2"></i>
                    Editar
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Columna Principal -->
        <div class="col-lg-8">
            <!-- Información General -->
            <div class="card-modern">
                <div class="card-header-gradient">
                    <i class="fas fa-info-circle"></i>
                    <span>Información General</span>
                </div>
                <div class="card-body-modern">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-hashtag"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">ID del Proveedor</div>
                                <div class="info-value">#{{ $proveedor->id_proveedor }}</div>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Razón Social</div>
                                <div class="info-value">{{ $proveedor->razon_social }}</div>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Tipo de Documento</div>
                                <div class="info-value">{{ $proveedor->tipo_documento }}</div>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Número de Documento</div>
                                <div class="info-value">{{ $proveedor->numero_documento }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información de Contacto -->
            <div class="card-modern">
                <div class="card-header-gradient">
                    <i class="fas fa-address-book"></i>
                    <span>Datos de Contacto</span>
                </div>
                <div class="card-body-modern">
                    <div class="info-grid">
                        @if($proveedor->contacto)
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Persona de Contacto</div>
                                <div class="info-value">{{ $proveedor->contacto }}</div>
                            </div>
                        </div>
                        @endif

                        @if($proveedor->telefono)
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Teléfono</div>
                                <div class="info-value">
                                    <a href="tel:{{ $proveedor->telefono }}" class="contact-link">
                                        {{ $proveedor->telefono }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($proveedor->correo)
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Correo Electrónico</div>
                                <div class="info-value">
                                    <a href="mailto:{{ $proveedor->correo }}" class="contact-link">
                                        {{ $proveedor->correo }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(empty($proveedor->contacto) && empty($proveedor->telefono) && empty($proveedor->correo))
                        <div class="empty-message">
                            <i class="fas fa-info-circle"></i>
                            <p>No hay información de contacto registrada</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Ubicación -->
            <div class="card-modern">
                <div class="card-header-gradient">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Ubicación</span>
                </div>
                <div class="card-body-modern">
                    <div class="info-grid">
                        @if($proveedor->direccion)
                        <div class="info-item full-width">
                            <div class="info-icon">
                                <i class="fas fa-map-marked-alt"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Dirección</div>
                                <div class="info-value">{{ $proveedor->direccion }}</div>
                            </div>
                        </div>
                        @endif

                        @if($proveedor->ubigeo)
                        <div class="info-item full-width">
                            <div class="info-icon">
                                <i class="fas fa-map-pin"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Ubigeo</div>
                                <div class="info-value">
                                    {{ $proveedor->ubigeo->departamento }} - 
                                    {{ $proveedor->ubigeo->provincia }} - 
                                    {{ $proveedor->ubigeo->distrito }}
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(empty($proveedor->direccion) && empty($proveedor->ubigeo))
                        <div class="empty-message">
                            <i class="fas fa-info-circle"></i>
                            <p>No hay información de ubicación registrada</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Compras Recientes -->
            <div class="card-modern">
                <div class="card-header-gradient">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Compras Recientes (Últimas 5)</span>
                </div>
                <div class="card-body-modern">
                    @php
                        $comprasRecientes = \App\Models\Compra::where('id_proveedor', $proveedor->id_proveedor)
                            ->orderBy('fecha', 'desc')
                            ->take(5)
                            ->get();
                    @endphp

                    @if($comprasRecientes->count() > 0)
                    <div class="table-responsive">
                        <table class="table-detail">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Comprobante</th>
                                    <th>Estado</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($comprasRecientes as $compra)
                                <tr>
                                    <td>
                                        <span class="date-badge">
                                            <i class="fas fa-calendar"></i>
                                            {{ \Carbon\Carbon::parse($compra->fecha)->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="comprobante-badge">
                                            {{ $compra->tipo_comprobante }} - {{ $compra->serie }}-{{ $compra->numero }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($compra->estado == 'completado')
                                            <span class="badge-status badge-success-sm">
                                                <i class="fas fa-check-circle"></i> Completado
                                            </span>
                                        @elseif($compra->estado == 'pendiente')
                                            <span class="badge-status badge-warning-sm">
                                                <i class="fas fa-clock"></i> Pendiente
                                            </span>
                                        @else
                                            <span class="badge-status badge-danger-sm">
                                                <i class="fas fa-times-circle"></i> Cancelado
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <span class="total-value">S/ {{ number_format($compra->total, 2) }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="empty-message">
                        <i class="fas fa-shopping-cart"></i>
                        <p>No hay compras registradas con este proveedor</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Columna Lateral -->
        <div class="col-lg-4">
            <!-- Acciones Rápidas -->
            <div class="card-modern">
                <div class="card-header-gradient">
                    <i class="fas fa-bolt"></i>
                    <span>Acciones Rápidas</span>
                </div>
                <div class="card-body-modern">
                    <div class="quick-actions">
                        <a href="{{ route('proveedores.edit', $proveedor->id_proveedor) }}" class="quick-action-btn btn-edit">
                            <i class="fas fa-edit"></i>
                            <span>Editar Proveedor</span>
                        </a>
                        @if($proveedor->telefono)
                        <a href="tel:{{ $proveedor->telefono }}" class="quick-action-btn btn-call">
                            <i class="fas fa-phone"></i>
                            <span>Llamar</span>
                        </a>
                        @endif
                        @if($proveedor->correo)
                        <a href="mailto:{{ $proveedor->correo }}" class="quick-action-btn btn-email">
                            <i class="fas fa-envelope"></i>
                            <span>Enviar Email</span>
                        </a>
                        @endif
                        @if(auth()->check() && auth()->user()->id_rol === 1)
                        <form action="{{ route('proveedores.destroy', $proveedor->id_proveedor) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este proveedor?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="quick-action-btn btn-delete">
                                <i class="fas fa-trash-alt"></i>
                                <span>Eliminar</span>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="card-modern">
                <div class="card-header-gradient">
                    <i class="fas fa-chart-line"></i>
                    <span>Estadísticas</span>
                </div>
                <div class="card-body-modern">
                    @php
                        $totalCompras = \App\Models\Compra::where('id_proveedor', $proveedor->id_proveedor)->count();
                        $montoTotal = \App\Models\Compra::where('id_proveedor', $proveedor->id_proveedor)->sum('total');
                        $compraPromedio = $totalCompras > 0 ? $montoTotal / $totalCompras : 0;
                        $ultimaCompra = \App\Models\Compra::where('id_proveedor', $proveedor->id_proveedor)
                            ->orderBy('fecha', 'desc')
                            ->first();
                    @endphp

                    <div class="stats-list">
                        <div class="stat-item-detail">
                            <div class="stat-icon-circle stat-primary">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="stat-details">
                                <div class="stat-value">{{ $totalCompras }}</div>
                                <div class="stat-label">Total de Compras</div>
                            </div>
                        </div>

                        <div class="stat-item-detail">
                            <div class="stat-icon-circle stat-success">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="stat-details">
                                <div class="stat-value">S/ {{ number_format($montoTotal, 2) }}</div>
                                <div class="stat-label">Monto Total</div>
                            </div>
                        </div>

                        <div class="stat-item-detail">
                            <div class="stat-icon-circle stat-info">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div class="stat-details">
                                <div class="stat-value">S/ {{ number_format($compraPromedio, 2) }}</div>
                                <div class="stat-label">Compra Promedio</div>
                            </div>
                        </div>

                        @if($ultimaCompra)
                        <div class="stat-item-detail">
                            <div class="stat-icon-circle stat-warning">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="stat-details">
                                <div class="stat-value">{{ \Carbon\Carbon::parse($ultimaCompra->fecha)->format('d/m/Y') }}</div>
                                <div class="stat-label">Última Compra</div>
                                <div class="stat-extra">{{ \Carbon\Carbon::parse($ultimaCompra->fecha)->diffForHumans() }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Timeline de Auditoría -->
            <div class="card-modern">
                <div class="card-header-gradient">
                    <i class="fas fa-history"></i>
                    <span>Historial</span>
                </div>
                <div class="card-body-modern">
                    <div class="audit-timeline">
                        @if($proveedor->created_at)
                        <div class="audit-item">
                            <div class="audit-icon audit-icon-success">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="audit-content">
                                <div class="audit-label">Fecha de Registro</div>
                                <div class="audit-value">{{ $proveedor->created_at->format('d/m/Y H:i') }}</div>
                                <div class="audit-time">{{ $proveedor->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        @endif

                        @if($proveedor->updated_at && $proveedor->created_at != $proveedor->updated_at)
                        <div class="audit-item">
                            <div class="audit-icon audit-icon-info">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div class="audit-content">
                                <div class="audit-label">Última Actualización</div>
                                <div class="audit-value">{{ $proveedor->updated_at->format('d/m/Y H:i') }}</div>
                                <div class="audit-time">{{ $proveedor->updated_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Header Moderno */
    .page-header-modern {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(240, 147, 251, 0.3);
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 20px;
        flex: 1;
    }

    .btn-back {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        font-size: 18px;
        flex-shrink: 0;
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateX(-5px);
        color: white;
    }

    .header-info {
        color: white;
        flex: 1;
    }

    .header-title {
        font-size: 28px;
        font-weight: 700;
        color: white;
        display: flex;
        align-items: center;
    }

    .header-subtitle {
        margin: 5px 0 0 0;
        font-size: 14px;
        opacity: 0.9;
    }

    .status-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        backdrop-filter: blur(10px);
    }

    .status-active {
        background: rgba(40, 167, 69, 0.9);
        color: white;
    }

    .status-inactive {
        background: rgba(220, 53, 69, 0.9);
        color: white;
    }

    .header-actions {
        display: flex;
        gap: 10px;
    }

    .btn-action-header {
        background: white;
        color: #f5576c;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .btn-action-header:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        color: #f5576c;
    }

    /* Tarjetas Modernas */
    .card-modern {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        margin-bottom: 30px;
    }

    .card-header-gradient {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        padding: 20px 25px;
        color: white;
        font-weight: 600;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-body-modern {
        padding: 30px;
    }

    /* Grid de Información */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
    }

    .info-item {
        display: flex;
        gap: 15px;
        align-items: flex-start;
        padding: 20px;
        background: #f7fafc;
        border-radius: 12px;
        border-left: 4px solid #f5576c;
    }

    .info-item.full-width {
        grid-column: 1 / -1;
    }

    .info-icon {
        width: 45px;
        height: 45px;
        background: rgba(245, 87, 108, 0.1);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #f5576c;
        font-size: 20px;
        flex-shrink: 0;
    }

    .info-content {
        flex: 1;
    }

    .info-label {
        font-size: 12px;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
        font-weight: 600;
    }

    .info-value {
        font-size: 16px;
        font-weight: 700;
        color: #2d3748;
    }

    .contact-link {
        color: #f5576c;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .contact-link:hover {
        color: #e04554;
        text-decoration: underline;
    }

    /* Mensajes Vacíos */
    .empty-message {
        text-align: center;
        padding: 40px 20px;
        color: #a0aec0;
    }

    .empty-message i {
        font-size: 48px;
        margin-bottom: 15px;
        opacity: 0.5;
    }

    .empty-message p {
        margin: 0;
        font-size: 14px;
    }

    /* Tabla de Detalles */
    .table-detail {
        width: 100%;
        border-collapse: collapse;
    }

    .table-detail thead {
        background: #f7fafc;
    }

    .table-detail thead th {
        padding: 15px;
        text-align: left;
        font-size: 12px;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        border-bottom: 2px solid #e2e8f0;
    }

    .table-detail tbody td {
        padding: 15px;
        border-bottom: 1px solid #e2e8f0;
        font-size: 14px;
        color: #2d3748;
    }

    .table-detail tbody tr:hover {
        background: #f7fafc;
    }

    .date-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: #ffe6f0;
        color: #f5576c;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
    }

    .comprobante-badge {
        display: inline-block;
        padding: 6px 12px;
        background: #e6f3ff;
        color: #4299e1;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-success-sm {
        background: rgba(40, 167, 69, 0.1);
        color: #28a745;
    }

    .badge-warning-sm {
        background: rgba(255, 193, 7, 0.1);
        color: #ffc107;
    }

    .badge-danger-sm {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }

    .total-value {
        font-size: 16px;
        font-weight: 700;
        color: #48bb78;
    }

    /* Acciones Rápidas */
    .quick-actions {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .quick-action-btn {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 18px;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        width: 100%;
    }

    .btn-edit {
        background: #4299e1;
        color: white;
    }

    .btn-edit:hover {
        background: #3182ce;
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(66, 153, 225, 0.3);
    }

    .btn-call {
        background: #48bb78;
        color: white;
    }

    .btn-call:hover {
        background: #38a169;
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(72, 187, 120, 0.3);
    }

    .btn-email {
        background: #9f7aea;
        color: white;
    }

    .btn-email:hover {
        background: #805ad5;
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(159, 122, 234, 0.3);
    }

    .btn-delete {
        background: #fc8181;
        color: white;
    }

    .btn-delete:hover {
        background: #f56565;
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(245, 101, 101, 0.3);
    }

    /* Estadísticas */
    .stats-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .stat-item-detail {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 18px;
        background: #f7fafc;
        border-radius: 12px;
    }

    .stat-icon-circle {
        width: 55px;
        height: 55px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .stat-primary {
        background: rgba(245, 87, 108, 0.1);
        color: #f5576c;
    }

    .stat-success {
        background: rgba(72, 187, 120, 0.1);
        color: #48bb78;
    }

    .stat-info {
        background: rgba(66, 153, 225, 0.1);
        color: #4299e1;
    }

    .stat-warning {
        background: rgba(237, 137, 54, 0.1);
        color: #ed8936;
    }

    .stat-details {
        flex: 1;
    }

    .stat-value {
        font-size: 20px;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 12px;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-extra {
        font-size: 11px;
        color: #a0aec0;
        margin-top: 4px;
    }

    /* Timeline de Auditoría */
    .audit-timeline {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .audit-item {
        display: flex;
        gap: 15px;
        align-items: flex-start;
    }

    .audit-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 16px;
    }

    .audit-icon-success {
        background: rgba(72, 187, 120, 0.1);
        color: #48bb78;
    }

    .audit-icon-info {
        background: rgba(66, 153, 225, 0.1);
        color: #4299e1;
    }

    .audit-content {
        flex: 1;
    }

    .audit-label {
        font-size: 12px;
        color: #a0aec0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .audit-value {
        font-size: 14px;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 4px;
    }

    .audit-time {
        font-size: 12px;
        color: #718096;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header-modern {
            padding: 20px;
        }

        .header-content {
            flex-direction: column;
        }

        .header-title {
            font-size: 22px;
        }

        .header-actions {
            width: 100%;
        }

        .btn-action-header {
            width: 100%;
            justify-content: center;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .card-body-modern {
            padding: 20px;
        }
    }
</style>
@endsection
