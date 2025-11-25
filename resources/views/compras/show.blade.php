
@extends('layouts.dashboard')

@section('content')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 3px solid #dc2626;
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 0;
    }

    .breadcrumb-custom {
        background: transparent;
        padding: 0;
        margin: 0;
        font-size: 0.875rem;
    }

    .breadcrumb-custom a {
        color: #dc2626;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .breadcrumb-custom a:hover {
        color: #991b1b;
    }

    .btn-back {
        background: #6b7280;
        color: white;
        border: none;
        border-radius: 10px;
        padding: 0.625rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-back:hover {
        background: #4b5563;
        transform: translateY(-2px);
        color: white;
    }

    .id-badge-large {
        background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1.5rem;
        display: inline-block;
        box-shadow: 0 4px 6px rgba(220, 38, 38, 0.3);
    }

    .card-modern {
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        margin-bottom: 2rem;
        background: white;
    }

    .card-header-gradient {
        background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
        color: white;
        padding: 1.5rem;
        border: none;
    }

    .card-header-gradient h5 {
        margin: 0;
        font-weight: 700;
        font-size: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .card-body-custom {
        padding: 2rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .info-item {
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        padding: 1.25rem;
        border-radius: 12px;
        border-left: 4px solid #dc2626;
        transition: all 0.3s ease;
    }

    .info-item:hover {
        transform: translateX(4px);
        box-shadow: 0 4px 8px rgba(220, 38, 38, 0.15);
    }

    .info-label {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-label i {
        color: #dc2626;
    }

    .info-value {
        font-size: 1.125rem;
        color: #111827;
        font-weight: 700;
    }

    .totals-section {
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 2px dashed #fde68a;
    }

    .total-row:last-child {
        border-bottom: none;
        padding-top: 1.5rem;
        margin-top: 1rem;
        border-top: 3px solid #f59e0b;
    }

    .total-label {
        font-size: 1rem;
        font-weight: 600;
        color: #374151;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .total-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #dc2626;
    }

    .total-row:last-child .total-label {
        font-size: 1.25rem;
    }

    .total-row:last-child .total-value {
        font-size: 2rem;
        color: #059669;
    }

    .table-modern {
        margin: 0;
    }

    .table-modern thead {
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
    }

    .table-modern thead th {
        border: none;
        padding: 1rem;
        font-weight: 600;
        color: #991b1b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.875rem;
    }

    .table-modern tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f3f4f6;
    }

    .table-modern tbody tr {
        transition: all 0.2s ease;
    }

    .table-modern tbody tr:hover {
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 50%);
    }

    .table-modern tbody tr:last-child td {
        border-bottom: none;
    }

    .product-name {
        font-weight: 600;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .product-name i {
        color: #dc2626;
    }

    .quantity-badge {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        padding: 0.375rem 0.75rem;
        border-radius: 8px;
        font-weight: 700;
        display: inline-block;
    }

    .price-cell {
        font-weight: 600;
        color: #374151;
    }

    .total-cell {
        font-weight: 700;
        color: #dc2626;
        font-size: 1.05rem;
    }

    .estado-badge {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 700;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .estado-completado {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .estado-pendiente {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    .estado-cancelado {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-state-icon {
        font-size: 4rem;
        color: #d1d5db;
        margin-bottom: 1rem;
    }

    .empty-state-text {
        color: #6b7280;
        font-size: 1.125rem;
    }

    .actions-bar {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .btn-action {
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border: none;
    }

    .btn-edit {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(245, 158, 11, 0.3);
        color: white;
    }

    .btn-delete {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
        color: white;
    }

    .stats-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.25rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        text-align: center;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(220, 38, 38, 0.15);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.75rem;
        font-size: 1.5rem;
    }

    .stat-icon.productos {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
    }

    .stat-icon.cantidad {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }

    .stat-icon.promedio {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .stat-label {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #111827;
    }
</style>

<div class="container-fluid px-4 py-4">
    <div class="page-header">
        <div>
            <nav aria-label="breadcrumb" class="breadcrumb-custom mb-2">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('compras.index') }}"><i class="fas fa-shopping-cart me-1"></i>Compras</a></li>
                    <li class="breadcrumb-item active">Detalle</li>
                </ol>
            </nav>
            <h1 class="page-title">
                <i class="fas fa-file-invoice"></i>
                Detalle de Compra
            </h1>
        </div>
        <a href="{{ route('compras.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i>
            Volver al Listado
        </a>
    </div>

    <!-- ID y Acciones -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <span class="id-badge-large">
            <i class="fas fa-hashtag me-2"></i>{{ $compra->id_compra }}
        </span>
        <div class="actions-bar">
            <a href="{{ route('compras.edit', $compra->id_compra) }}" class="btn-action btn-edit">
                <i class="fas fa-edit"></i>
                Editar Compra
            </a>
            <form action="{{ route('compras.destroy', $compra->id_compra) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar esta compra?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-action btn-delete">
                    <i class="fas fa-trash"></i>
                    Eliminar
                </button>
            </form>
        </div>
    </div>

    <!-- Información General -->
    <div class="card-modern">
        <div class="card-header-gradient">
            <h5>
                <i class="fas fa-info-circle"></i>
                Información de la Compra
            </h5>
        </div>
        <div class="card-body-custom">
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-building"></i>
                        Proveedor
                    </div>
                    <div class="info-value">{{ $compra->proveedor->razon_social ?? '-' }}</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-coins"></i>
                        Moneda
                    </div>
                    <div class="info-value">{{ $compra->moneda->nombre ?? $compra->moneda->descripcion ?? '-' }}</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-calendar-alt"></i>
                        Fecha de Compra
                    </div>
                    <div class="info-value">{{ Carbon\Carbon::parse($compra->fecha)->format('d/m/Y H:i') }}</div>
                </div>
                
                @if(isset($compra->estado))
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-check-circle"></i>
                        Estado
                    </div>
                    <div class="info-value">
                        <span class="estado-badge estado-completado">
                            <i class="fas fa-check"></i>
                            {{ $compra->estado }}
                        </span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Estadísticas de Productos -->
    @php
        $totalProductos = $compra->detalles->count();
        $totalCantidad = $compra->detalles->sum('cantidad');
        $promedioPrecio = $totalProductos > 0 ? $compra->total / $totalCantidad : 0;
    @endphp
    
    <div class="stats-summary">
        <div class="stat-card">
            <div class="stat-icon productos">
                <i class="fas fa-boxes"></i>
            </div>
            <div class="stat-label">Productos Diferentes</div>
            <div class="stat-value">{{ $totalProductos }}</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon cantidad">
                <i class="fas fa-cubes"></i>
            </div>
            <div class="stat-label">Cantidad Total</div>
            <div class="stat-value">{{ $totalCantidad }}</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon promedio">
                <i class="fas fa-calculator"></i>
            </div>
            <div class="stat-label">Precio Promedio</div>
            <div class="stat-value">S/ {{ number_format($promedioPrecio, 2) }}</div>
        </div>
    </div>

    <!-- Productos -->
    <div class="card-modern">
        <div class="card-header-gradient">
            <h5>
                <i class="fas fa-list"></i>
                Productos Comprados
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-end">Precio Unitario</th>
                            <th class="text-end">Subtotal</th>
                            <th class="text-end">IGV (18%)</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($compra->detalles as $detalle)
                        <tr>
                            <td>
                                <div class="product-name">
                                    <i class="fas fa-box"></i>
                                    {{ $detalle->producto->descripcion ?? $detalle->producto->nombre ?? '-' }}
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="quantity-badge">{{ $detalle->cantidad }}</span>
                            </td>
                            <td class="text-end price-cell">S/ {{ number_format($detalle->precio_unitario, 2) }}</td>
                            <td class="text-end price-cell">S/ {{ number_format($detalle->subtotal, 2) }}</td>
                            <td class="text-end price-cell">S/ {{ number_format($detalle->igv, 2) }}</td>
                            <td class="text-end total-cell">S/ {{ number_format($detalle->total, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-box-open"></i>
                                    </div>
                                    <p class="empty-state-text">No hay productos registrados en esta compra</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Totales -->
    <div class="row">
        <div class="col-lg-6 offset-lg-6">
            <div class="totals-section">
                <div class="total-row">
                    <span class="total-label">
                        <i class="fas fa-file-invoice-dollar"></i>
                        Subtotal:
                    </span>
                    <span class="total-value" style="font-size: 1.25rem;">S/ {{ number_format($compra->subtotal, 2) }}</span>
                </div>
                
                <div class="total-row">
                    <span class="total-label">
                        <i class="fas fa-percentage"></i>
                        IGV (18%):
                    </span>
                    <span class="total-value" style="font-size: 1.25rem;">S/ {{ number_format($compra->igv, 2) }}</span>
                </div>
                
                <div class="total-row">
                    <span class="total-label">
                        <i class="fas fa-money-bill-wave"></i>
                        TOTAL:
                    </span>
                    <span class="total-value">S/ {{ number_format($compra->total, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
