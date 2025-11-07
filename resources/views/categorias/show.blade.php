@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalles de la Categoría</h1>
    <div class="form-group">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" class="form-control" value="{{ $categoria->nombre }}" readonly>
    </div>
    <div class="form-group">
        <label for="descripcion">Descripción</label>
        <textarea id="descripcion" class="form-control" readonly>{{ $categoria->descripcion }}</textarea>
    </div>
    <div class="form-group">
        <label for="activo">Activo</label>
        <input type="text" id="activo" class="form-control" value="{{ $categoria->activo ? 'Sí' : 'No' }}" readonly>
    </div>
    <a href="{{ route('categorias.index') }}" class="btn btn-secondary">Volver</a>
</div>
@endsection