@extends('layouts.dashboard')

@section('content')
<div class="container">
    <h1>Clientes</h1>
    <a href="{{ route('clientes.create') }}" class="btn btn-primary mb-3">Crear Cliente</a>
    <form method="GET" action="{{ route('clientes.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Buscar clientes..." value="{{ request('search') }}">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit">Buscar</button>
            </div>
        </div>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tipo Documento</th>
                <th>Número Documento</th>
                <th>Razón Social</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Clasificación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($clientes as $cliente)
                <tr>
                    <td>{{ $cliente->id_cliente }}</td>
                    <td>{{ $cliente->tipo_documento }}</td>
                    <td>{{ $cliente->numero_documento }}</td>
                    <td>{{ $cliente->razon_social }}</td>
                    <td>{{ $cliente->nombre }}</td>
                    <td>{{ $cliente->telefono }}</td>
                    <td>{{ $cliente->correo }}</td>
                    <td>{{ $cliente->clasificacion }}</td>
                    <td>
                        <a href="{{ route('clientes.edit', $cliente->id_cliente) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('clientes.destroy', $cliente->id_cliente) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection