@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Moneda</h1>
    <form action="{{ route('monedas.update', $moneda->id_moneda) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ $moneda->nombre }}">
        </div>
        <div class="form-group">
            <label for="simbolo">Símbolo</label>
            <input type="text" name="simbolo" id="simbolo" class="form-control" value="{{ $moneda->simbolo }}">
        </div>
        <div class="form-group">
            <label for="codigo_iso">Código ISO</label>
            <input type="text" name="codigo_iso" id="codigo_iso" class="form-control" value="{{ $moneda->codigo_iso }}">
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>
</div>
@endsection