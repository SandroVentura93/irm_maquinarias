@extends('layouts.dashboard')

@section('content')
<div class="container">
    <h1>Lista de Categorías</h1>
    <a href="{{ route('categorias.create') }}" class="btn btn-primary">Crear Nueva Categoría</a>
    <table class="table mt-3">
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
            @foreach ($categorias as $categoria)
                <tr>
                    <td>{{ $categoria->id_categoria }}</td>
                    <td>{{ $categoria->nombre }}</td>
                    <td>{{ $categoria->descripcion }}</td>
                    <td>{{ $categoria->activo ? 'Sí' : 'No' }}</td>
                    <td>
                        <a href="{{ route('categorias.edit', $categoria->id_categoria) }}" class="btn btn-warning">Editar</a>
                        <form action="{{ route('categorias.destroy', $categoria->id_categoria) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection