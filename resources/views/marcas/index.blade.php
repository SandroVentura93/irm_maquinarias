@extends('layouts.dashboard')

@section('content')
<div class="container">
    <h1>Gestión de Marcas</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('marcas.create') }}" class="btn btn-primary mb-3">Crear Nueva Marca</a>

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
                        <a href="{{ route('marcas.edit', $marca->id_marca) }}" class="btn btn-warning">Editar</a>
                        <form action="{{ route('marcas.destroy', $marca->id_marca) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta marca?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection