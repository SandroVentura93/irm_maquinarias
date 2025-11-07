@extends('layouts.dashboard')

@section('content')
<div class="container">
    <h1>Gestión de Productos</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('productos.create') }}" class="btn btn-primary mb-3">Nuevo Producto</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Código</th>
                <th>Descripción</th>
                <th>Stock Actual</th>
                <th>Stock Mínimo</th>
                <th>Precio Compra</th>
                <th>Precio Venta</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productos as $producto)
                <tr>
                    <td>{{ $producto->codigo }}</td>
                    <td>{{ $producto->descripcion }}</td>
                    <td>{{ $producto->stock_actual }}</td>
                    <td>{{ $producto->stock_minimo }}</td>
                    <td>{{ $producto->precio_compra }}</td>
                    <td>{{ $producto->precio_venta }}</td>
                    <td>
                        <a href="{{ route('productos.edit', $producto) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('productos.destroy', $producto) }}" method="POST" style="display:inline;">
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