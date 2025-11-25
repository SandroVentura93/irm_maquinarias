@extends('layouts.dashboard')

@section('content')
<div class="container">
    <h1>Gestión de Marcas</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('marcas.create') }}" class="btn btn-primary mb-3">
        <i class="fas fa-plus"></i> Crear Nueva Marca
    </a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Activo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($marcas as $marca)
                <tr>
                    <td>{{ $marca->id_marca }}</td>
                    <td>{{ $marca->nombre }}</td>
                    <td>{{ $marca->descripcion }}</td>
                    <td>{{ $marca->activo ? 'Sí' : 'No' }}</td>
                    <td>
                        <a href="{{ route('marcas.edit', $marca->id_marca) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <form action="{{ route('marcas.destroy', $marca->id_marca) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta marca?')">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

<style>
    .table {
        border-collapse: separate;
        border-spacing: 0 0.5rem;
    }

    .table thead th {
        background-color: #007bff;
        color: #fff;
        font-weight: bold;
        text-transform: uppercase;
        border: none;
        padding: 10px;
    }

    .table tbody tr {
        background-color: #f8f9fa;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    .table tbody tr td {
        border-top: none;
        padding: 10px;
        vertical-align: middle;
    }

    .table tbody tr td:first-child {
        border-top-left-radius: 8px;
        border-bottom-left-radius: 8px;
    }

    .table tbody tr td:last-child {
        border-top-right-radius: 8px;
        border-bottom-right-radius: 8px;
    }

    .btn-primary {
        background-color: #28a745;
        border: none;
        color: #fff;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #218838;
    }

    .btn-warning {
        background-color: #ffc107;
        border: none;
        color: #fff;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .btn-warning:hover {
        background-color: #e0a800;
    }

    .btn-danger {
        background-color: #dc3545;
        border: none;
        color: #fff;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    h1 {
        font-size: 2rem;
        font-weight: bold;
        color: #343a40;
        text-align: center;
        margin-bottom: 20px;
    }
</style>