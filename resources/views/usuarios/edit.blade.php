@extends('layouts.dashboard')

@section('content')
<style>
    .edit-glass {
        background: rgba(255,255,255,0.13);
        border-radius: 24px;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.25);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,0.18);
        padding: 2.5rem 2rem;
        margin-top: 40px;
        max-width: 520px;
        margin-left: auto;
        margin-right: auto;
    }
    .edit-avatar {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        object-fit: cover;
        box-shadow: 0 2px 16px #00c3ff44;
        border: 3px solid #ff00cc;
        margin-bottom: 18px;
        animation: avatarGlow 2s infinite alternate;
    }
    @keyframes avatarGlow {
        0% { box-shadow: 0 0 12px #00c3ff88; border-color: #00c3ff; }
        100% { box-shadow: 0 0 24px #ff00cc88; border-color: #ff00cc; }
    }
    .edit-title {
        font-size: 2rem;
        font-weight: 800;
        color: #2c5364;
        text-shadow: 0 2px 8px #00c3ff22;
        margin-bottom: 0.5rem;
        text-align: center;
    }
    .form-label {
        font-weight: 700;
        color: #00c3ff;
        margin-bottom: 0.3em;
    }
    .form-control, .form-select {
        border-radius: 12px;
        border: 1px solid #00c3ff44;
        box-shadow: 0 2px 8px #00c3ff11;
        background: linear-gradient(90deg, #f0f8ff 0%, #fff 100%);
        margin-bottom: 1em;
        font-size: 1.1rem;
    }
    .form-check-input {
        accent-color: #ff00cc;
        box-shadow: 0 2px 8px #ff00cc44;
    }
    .btn-futurista {
        background: linear-gradient(90deg, #00c3ff 0%, #ff00cc 100%);
        color: #fff;
        font-weight: 700;
        border: none;
        border-radius: 50px;
        box-shadow: 0 2px 8px #00c3ff44;
        padding: 0.7em 2em;
        font-size: 1.1rem;
        transition: background 0.3s, box-shadow 0.3s;
        margin-top: 1em;
    }
    .btn-futurista:hover {
        background: linear-gradient(90deg, #ff00cc 0%, #00c3ff 100%);
        box-shadow: 0 4px 16px #ff00cc44;
    }
</style>
<div class="container">
    <div class="edit-glass animate__animated animate__fadeIn">
        <div class="d-flex flex-column align-items-center mb-3">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($usuario->nombre) }}&background=ff00cc&color=fff&size=128" alt="Avatar" class="edit-avatar">
            <div class="edit-title"><i class="fas fa-user-edit me-2"></i>Editar Usuario</div>
        </div>
        <form action="{{ route('usuarios.update', $usuario) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-control" value="{{ $usuario->nombre }}" required>
            </div>
            <div class="mb-3">
                <label for="usuario" class="form-label">Usuario</label>
                <input type="text" name="usuario" id="usuario" class="form-control" value="{{ $usuario->usuario }}" required>
            </div>
            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña</label>
                <input type="password" name="contrasena" id="contrasena" class="form-control">
                <small class="form-text text-muted">Dejar en blanco para mantener la contraseña actual.</small>
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" name="correo" id="correo" class="form-control" value="{{ $usuario->correo }}">
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" name="telefono" id="telefono" class="form-control" value="{{ $usuario->telefono }}">
            </div>
            <div class="mb-3">
                <label for="id_rol" class="form-label">Rol</label>
                <select name="id_rol" id="id_rol" class="form-select" required>
                    @foreach ($roles as $rol)
                        <option value="{{ $rol->id_rol }}" {{ $usuario->id_rol == $rol->id_rol ? 'selected' : '' }}>{{ $rol->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group form-check mb-3">
                <input type="checkbox" name="activo" id="activo" class="form-check-input" {{ $usuario->activo ? 'checked' : '' }}>
                <label for="activo" class="form-check-label">Activo</label>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-futurista"><i class="fas fa-save me-2"></i>Guardar Cambios</button>
                <a href="{{ route('usuarios.index') }}" class="btn btn-futurista ms-2"><i class="fas fa-arrow-left me-2"></i>Volver</a>
            </div>
        </form>
    </div>
</div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection