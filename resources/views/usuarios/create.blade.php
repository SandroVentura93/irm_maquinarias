@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Crear Usuario</h1>

    <form action="{{ route('usuarios.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="usuario">Usuario</label>
            <input type="text" name="usuario" id="usuario" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="contrasena">Contraseña</label>
            <input type="password" name="contrasena" id="contrasena" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="correo">Correo</label>
            <input type="email" name="correo" id="correo" class="form-control">
        </div>

        <div class="form-group">
            <label for="telefono">Teléfono</label>
            <input type="text" name="telefono" id="telefono" class="form-control">
        </div>

        <div class="form-group">
            <label for="id_rol">Rol</label>
            <select name="id_rol" id="id_rol" class="form-control" required>
                @foreach ($roles as $rol)
                    <option value="{{ $rol->id_rol }}">{{ $rol->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group form-check">
            <input type="checkbox" name="activo" id="activo" class="form-check-input" checked>
            <label for="activo" class="form-check-label">Activo</label>
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection