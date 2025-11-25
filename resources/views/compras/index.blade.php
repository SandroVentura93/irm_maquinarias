@extends('layouts.dashboard')
@section('content')
<div class="container py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title text-primary">Gestión de Compras</h1>
        <a href="{{ route('compras.create') }}" class="btn btn-modern btn-success">
            <i class="fas fa-plus me-2"></i> Registrar Compra
        </a>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-modern alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Purchases Table -->
    <div class="card modern-card">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0 text-secondary">
                <i class="fas fa-shopping-cart me-2"></i> Lista de Compras
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern align-middle">
                    <thead class="table-header">
                        <tr>
                            <th>ID</th>
                            <th>Proveedor</th>
                            <th>Moneda</th>
                            <th>Fecha</th>
                            <th>Subtotal</th>
                            <th>IGV</th>
                            <th>Total</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($compras as $compra)
                            <tr>
                                <td class="fw-bold text-primary">#{{ $compra->id_compra }}</td>
                                <td>{{ $compra->proveedor->razon_social ?? '-' }}</td>
                                <td>{{ $compra->moneda->nombre ?? '-' }}</td>
                                <td>{{ Carbon\Carbon::parse($compra->fecha)->format('d/m/Y') }}</td>
                                <td>S/ {{ number_format($compra->subtotal, 2) }}</td>
                                <td>S/ {{ number_format($compra->igv, 2) }}</td>
                                <td class="fw-bold">S/ {{ number_format($compra->total, 2) }}</td>
                                <td class="text-center">
                                    <div class="action-buttons d-flex justify-content-center gap-2">
                                        <a href="{{ route('compras.show', $compra->id_compra) }}" class="btn btn-action btn-view" title="Ver Detalle">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('compras.edit', $compra->id_compra) }}" class="btn btn-action btn-edit" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('compras.destroy', $compra->id_compra) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-action btn-delete" onclick="return confirm('¿Está seguro de eliminar esta compra?')" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">No hay compras registradas.</td>
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

<style>
    .page-title {
        font-size: 2rem;
        font-weight: 700;
    }

    .btn-modern {
        border-radius: 12px;
        padding: 10px 20px;
        font-weight: 600;
        background: linear-gradient(135deg, #28a745, #218838);
        color: white;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-modern:hover {
        background: linear-gradient(135deg, #218838, #1e7e34);
        transform: translateY(-2px);
    }

    .modern-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 8px 25px -8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .table-modern {
        margin: 0;
        border-collapse: collapse;
    }

    .table-header th {
        background: #f8f9fa;
        border: none;
        padding: 1rem;
        font-weight: 600;
        color: #495057;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table-modern tbody tr:hover {
        background: rgba(40, 167, 69, 0.1);
    }

    .action-buttons .btn-action {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        font-size: 1rem;
        transition: all 0.2s ease;
    }

    .btn-view {
        background: rgba(23, 162, 184, 0.1);
        color: #17a2b8;
    }

    .btn-view:hover {
        background: #17a2b8;
        color: white;
    }

    .btn-edit {
        background: rgba(255, 193, 7, 0.1);
        color: #ffc107;
    }

    .btn-edit:hover {
        background: #ffc107;
        color: white;
    }

    .btn-delete {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }

    .btn-delete:hover {
        background: #dc3545;
        color: white;
    }
</style>
@endsection
