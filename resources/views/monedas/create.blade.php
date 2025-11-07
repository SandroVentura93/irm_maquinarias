@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Crear Moneda</h1>
    <form action="{{ route('monedas.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control">
        </div>
        <div class="form-group">
            <label for="simbolo">Símbolo</label>
            <input type="text" name="simbolo" id="simbolo" class="form-control">
        </div>
        <div class="form-group">
            <label for="codigo_iso">Código ISO</label>
            <input type="text" name="codigo_iso" id="codigo_iso" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
</div>
@endsection