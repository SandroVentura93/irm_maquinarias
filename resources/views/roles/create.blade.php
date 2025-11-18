@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Crear Rol</h1>

    <form action="{{ route('roles.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control"></textarea>
        </div>

        <div class="form-group form-check">
            <input type="checkbox" name="activo" id="activo" class="form-check-input" checked>
            <label for="activo" class="form-check-label">Activo</label>
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection