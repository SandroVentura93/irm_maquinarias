@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalles de la Marca</h1>
    <div class="form-group">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" class="form-control" value="{{ $marca->nombre }}" readonly>
    </div>
    <div class="form-group">
        <label for="descripcion">Descripción</label>
        <textarea id="descripcion" class="form-control" readonly>{{ $marca->descripcion }}</textarea>
    </div>
    <div class="form-group">
        <label for="activo">Activo</label>
        <input type="text" id="activo" class="form-control" value="{{ $marca->activo ? 'Sí' : 'No' }}" readonly>
    </div>
    <a href="{{ route('marcas.index') }}" class="btn btn-secondary">Volver</a>
</div>
@endsection