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
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        display: flex;
        align-items: center;
        gap: 1rem;
        margin: 0;
    }

    .page-title i {
        background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .btn-modern {
        border-radius: 12px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
        color: white;
        border: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 6px -1px rgba(220, 38, 38, 0.3);
    }

    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 12px -1px rgba(220, 38, 38, 0.4);
        color: white;
    }

    .alert-modern {
        border-radius: 12px;
        border: none;
        padding: 1rem 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .modern-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        overflow: hidden;
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
        transform: scale(1.01);
    }

    .table-modern tbody tr:last-child td {
        border-bottom: none;
    }

    .id-badge {
        background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 700;
        display: inline-block;
        box-shadow: 0 2px 4px rgba(220, 38, 38, 0.2);
    }

    .moneda-badge {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.875rem;
        display: inline-block;
    }

    .fecha-badge {
        background: #f3f4f6;
        color: #374151;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }

    .proveedor-name {
        font-weight: 600;
        color: #111827;
    }

    .amount-cell {
        font-weight: 600;
        color: #374151;
    }

    .total-cell {
        font-weight: 700;
        color: #dc2626;
        font-size: 1.1rem;
    }

    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-action {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        font-size: 1rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-view {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
    }

    .btn-edit {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(245, 158, 11, 0.3);
    }

    .btn-delete {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
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
        margin: 0;
    }

    .pagination {
        margin: 0;
    }

    .pagination .page-link {
        color: #dc2626;
        border: 1px solid #fee2e2;
        border-radius: 8px;
        margin: 0 0.25rem;
        padding: 0.5rem 0.75rem;
        transition: all 0.3s ease;
    }

    .pagination .page-link:hover {
        background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
        color: white;
        border-color: #dc2626;
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
        border-color: #dc2626;
    }

    .card-footer {
        background: #fafafa;
        border-top: 1px solid #f3f4f6;
        padding: 1rem;
    }

    .stats-row {
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
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(220, 38, 38, 0.15);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.75rem;
        font-size: 1.5rem;
    }

    .stat-icon.compras {
        background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
        color: white;
    }

    .stat-icon.total {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    .stat-icon.mes {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
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
        <h1 class="page-title">
            <i class="fas fa-shopping-cart"></i>
            Gestión de Compras
        </h1>
        <a href="{{ route('compras.create') }}" class="btn btn-modern">
            <i class="fas fa-plus"></i>
            Nueva Compra
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-modern alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Estadísticas -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon compras">
                <i class="fas fa-file-invoice"></i>
            </div>
            <div class="stat-label">Total Compras</div>
            <div class="stat-value">{{ $compras->total() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon total">
                <i class="fas fa-coins"></i>
            </div>
            <div class="stat-label">Monto Total (S/)</div>
            <div class="stat-value">S/ {{ number_format(($monto_total_pen ?? 0), 2) }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon total">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-label">Monto Total (USD)</div>
            <div class="stat-value">$ {{ number_format(($monto_total_usd ?? 0), 2) }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon mes">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-label">Compras del Mes</div>
            <div class="stat-value">{{ $compras->where('fecha', '>=', now()->startOfMonth())->count() }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon mes">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-label">Compras en PEN</div>
            <div class="stat-value">{{ $compras_count_pen ?? 0 }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon mes">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-label">Compras en USD</div>
            <div class="stat-value">{{ $compras_count_usd ?? 0 }}</div>
        </div>
    </div>

    <div class="modern-card">
        <div class="card-header-gradient">
            <h5>
                <i class="fas fa-list"></i>
                Listado de Compras
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Proveedor</th>
                            <th>Moneda</th>
                            <th>Fecha</th>
                            <th class="text-end">Subtotal</th>
                            <th class="text-end">IGV</th>
                            <th class="text-end">Total</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($compras as $compra)
                            <tr>
                                <td>
                                    <span class="id-badge">#{{ $compra->id_compra }}</span>
                                </td>
                                <td>
                                    <div class="proveedor-name">
                                        <i class="fas fa-building me-2" style="color: #dc2626;"></i>
                                        {{ $compra->proveedor->razon_social ?? '-' }}
                                    </div>
                                </td>
                                <td>
                                    <span class="moneda-badge">
                                        <i class="fas fa-coins me-1"></i>
                                        {{ $compra->moneda->nombre ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fecha-badge">
                                        <i class="fas fa-calendar"></i>
                                        {{ Carbon\Carbon::parse($compra->fecha)->format('d/m/Y') }}
                                    </span>
                                </td>
                                @php
                                    $simbolo = $compra->moneda->simbolo ?? 'S/';
                                    $icono = ($compra->moneda->codigo_iso ?? 'PEN') === 'USD' ? 'fas fa-dollar-sign' : 'fas fa-money-bill-wave';
                                @endphp
                                <td class="text-end amount-cell">
                                    <i class="{{ $icono }} me-1"></i> {{ $simbolo }} {{ number_format($compra->subtotal, 2) }}
                                </td>
                                <td class="text-end amount-cell">
                                    <i class="{{ $icono }} me-1"></i> {{ $simbolo }} {{ number_format($compra->igv, 2) }}
                                </td>
                                <td class="text-end total-cell">
                                    <i class="{{ $icono }} me-1"></i> {{ $simbolo }} {{ number_format($compra->total, 2) }}
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('compras.show', $compra->id_compra) }}" 
                                           class="btn-action btn-view" 
                                           title="Ver Detalle"
                                           data-bs-toggle="tooltip">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('compras.edit', $compra->id_compra) }}" 
                                           class="btn-action btn-edit" 
                                           title="Editar"
                                           data-bs-toggle="tooltip">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('compras.destroy', $compra->id_compra) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('¿Está seguro de eliminar esta compra?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn-action btn-delete" 
                                                    title="Eliminar"
                                                    data-bs-toggle="tooltip">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-inbox"></i>
                                        </div>
                                        <p class="empty-state-text">No hay compras registradas</p>
                                        <a href="{{ route('compras.create') }}" class="btn btn-modern mt-3">
                                            <i class="fas fa-plus me-2"></i>
                                            Registrar Primera Compra
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($compras->hasPages())
            <div class="card-footer d-flex justify-content-center">
                {{ $compras->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
</div>

<script>
    // Inicializar tooltips de Bootstrap
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>

@endsection
