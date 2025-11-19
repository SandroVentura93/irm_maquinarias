
@extends('layouts.dashboard')
@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Gestión de Compras</h2>
        <a href="{{ route('compras.create') }}" class="btn btn-success">Registrar Compra</a>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Proveedor</th>
                    <th>Moneda</th>
                    <th>Fecha</th>
                    <th>Subtotal</th>
                    <th>IGV</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($compras as $compra)
                    <tr>
                        <td>{{ $compra->id_compra }}</td>
                        <td>{{ $compra->proveedor->razon_social ?? '-' }}</td>
                        <td>{{ $compra->moneda->nombre ?? '-' }}</td>
                        <td>{{ $compra->fecha }}</td>
                        <td>{{ number_format($compra->subtotal, 2) }}</td>
                        <td>{{ number_format($compra->igv, 2) }}</td>
                        <td>{{ number_format($compra->total, 2) }}</td>
                        <td>
                            <a href="{{ route('compras.show', $compra->id_compra) }}" class="btn btn-info btn-sm" title="Ver"><i class="bi bi-eye"></i> Ver</a>
                            <a href="{{ route('compras.edit', $compra->id_compra) }}" class="btn btn-warning btn-sm" title="Editar"><i class="bi bi-pencil"></i> Editar</a>
                            <form action="{{ route('compras.destroy', $compra->id_compra) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar esta compra?')" title="Eliminar"><i class="bi bi-trash"></i> Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No hay compras registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-3">
            {{ $compras->links() }}
        </div>
    </div>
</div>
@endsection
