@extends('layouts.dashboard')

@section('content')
<div class="container">
    <h1>Gestión de Proveedores</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('proveedores.create') }}" class="btn btn-primary mb-3">Crear Nuevo Proveedor</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Razón Social</th>
                <th>Tipo Documento</th>
                <th>Número Documento</th>
                <th>Contacto</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Dirección</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($proveedores as $proveedor)
                <tr>
                    <td>{{ $proveedor->id_proveedor }}</td>
                    <td>{{ $proveedor->razon_social }}</td>
                    <td>{{ $proveedor->tipo_documento }}</td>
                    <td>{{ $proveedor->numero_documento }}</td>
                    <td>{{ $proveedor->contacto }}</td>
                    <td>{{ $proveedor->telefono }}</td>
                    <td>{{ $proveedor->correo }}</td>
                    <td>{{ $proveedor->direccion }}</td>
                    <td>
                        <a href="{{ route('proveedores.edit', $proveedor->id_proveedor) }}" class="btn btn-warning">Editar</a>
                        <form action="{{ route('proveedores.destroy', $proveedor->id_proveedor) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar este proveedor?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection