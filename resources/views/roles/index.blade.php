@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Lista de Roles</h1>
    <a href="{{ route('roles.create') }}" class="btn btn-primary mb-3">Crear Rol</a>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

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
            @foreach ($roles as $rol)
                <tr>
                    <td>{{ $rol->id_rol }}</td>
                    <td>{{ $rol->nombre }}</td>
                    <td>{{ $rol->descripcion }}</td>
                    <td>{{ $rol->activo ? 'Sí' : 'No' }}</td>
                    <td>
                        <a href="{{ route('roles.edit', $rol) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('roles.destroy', $rol) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection