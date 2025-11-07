@extends('layouts.dashboard')

@section('content')
<div class="container">
    <h1>Monedas</h1>
    <a href="{{ route('monedas.create') }}" class="btn btn-primary mb-3">Crear Moneda</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Símbolo</th>
                <th>Código ISO</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($monedas as $moneda)
                <tr>
                    <td>{{ $moneda->id_moneda }}</td>
                    <td>{{ $moneda->nombre }}</td>
                    <td>{{ $moneda->simbolo }}</td>
                    <td>{{ $moneda->codigo_iso }}</td>
                    <td>
                        <a href="{{ route('monedas.edit', $moneda->id_moneda) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('monedas.destroy', $moneda->id_moneda) }}" method="POST" style="display:inline;">
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