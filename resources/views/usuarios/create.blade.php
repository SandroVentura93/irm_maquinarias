@extends('layouts.dashboard')


@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&display=swap');
    body {
        font-family: 'Montserrat', Arial, sans-serif;
        background: linear-gradient(120deg, #e9edf5 0%, #f5f7fa 100%);
    }
    .usuario-create-card {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 8px 32px rgba(44,62,80,0.16);
        max-width: 600px;
        margin: 40px auto;
        padding: 48px 36px 36px 36px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .usuario-create-title {
        font-size: 2rem;
        font-weight: 900;
        color: #22334a;
        margin-bottom: 1em;
        letter-spacing: 1px;
        text-align: center;
    }
    .usuario-create-form label {
        font-weight: 700;
        color: #22334a;
        margin-bottom: 0.2em;
    }
    .usuario-create-form .form-control, .usuario-create-form .form-select {
        border-radius: 12px;
        border: 1px solid #e3e6ed;
        box-shadow: 0 2px 8px #22334a11;
        background: #f5f7fa;
        margin-bottom: 1.2em;
        font-size: 1.1rem;
    }
    .usuario-create-form .form-check-input {
        accent-color: #22334a;
        box-shadow: 0 2px 8px #22334a22;
    }
    .usuario-create-form .btn {
        border-radius: 12px;
        padding: 0.7em 2em;
        font-size: 1.1rem;
        font-weight: 700;
        margin-right: 0.5em;
        background: #22334a;
        color: #fff;
        border: none;
        box-shadow: 0 4px 16px #22334a22;
        transition: background 0.2s, box-shadow 0.2s;
    }
    .usuario-create-form .btn:hover {
        background: #1a2533;
        box-shadow: 0 8px 32px #22334a22;
    }
    .usuario-create-form .btn-secondary {
        background: #e3e6ed;
        color: #22334a;
    }
    .usuario-create-form .btn-secondary:hover {
        background: #22334a;
        color: #fff;
    }
</style>

<div class="usuario-create-card animate__animated animate__fadeIn">
    <div class="usuario-create-title">Crear Usuario</div>
    <form action="{{ route('usuarios.store') }}" method="POST" class="usuario-create-form w-100">
        @csrf
        <div class="mb-3">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="usuario">Usuario</label>
            <input type="text" name="usuario" id="usuario" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="contrasena">Contraseña</label>
            <input type="password" name="contrasena" id="contrasena" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="correo">Correo</label>
            <input type="email" name="correo" id="correo" class="form-control">
        </div>
        <div class="mb-3">
            <label for="telefono">Teléfono</label>
            <input type="text" name="telefono" id="telefono" class="form-control">
        </div>
        <div class="mb-3">
            <label for="id_rol">Rol</label>
            <select name="id_rol" id="id_rol" class="form-control" required>
                @foreach ($roles as $rol)
                    <option value="{{ $rol->id_rol }}">{{ $rol->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group form-check mb-3">
            <input type="checkbox" name="activo" id="activo" class="form-check-input" checked>
            <label for="activo" class="form-check-label">Activo</label>
        </div>
        <div class="text-center mt-3">
            <button type="submit" class="btn">Guardar</button>
            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection