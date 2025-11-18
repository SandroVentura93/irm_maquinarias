@extends('layouts.dashboard')

@section('content')

<style>
    body {
        background: linear-gradient(120deg, #e9edf5 0%, #f5f7fa 100%);
        font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
    }
    .usuarios-grid-header {
        padding: 32px 32px 16px 32px;
        background: transparent;
        border-bottom: 1px solid #e3e6ed;
    }
    .usuarios-grid-header h1 {
        font-size: 2.3rem;
        font-weight: 900;
        color: #22334a;
        margin-bottom: 0;
        letter-spacing: 1.5px;
    }
    .usuarios-grid-header .btn {
        background: #22334a;
        color: #fff;
        font-weight: 700;
        border-radius: 12px;
        border: none;
        padding: 0.7em 1.6em;
        font-size: 1.1rem;
        transition: background 0.2s, box-shadow 0.2s;
        box-shadow: 0 4px 16px #22334a22;
    }
    .usuarios-grid-header .btn:hover {
        background: #1a2533;
        box-shadow: 0 8px 32px #22334a22;
    }
    .usuarios-grid-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
        gap: 48px 36px;
        margin-top: 56px;
        padding-bottom: 40px;
        width: 100%;
        box-sizing: border-box;
    }
    .usuario-grid-card {
        background: #fff;
        border: none;
        border-radius: 24px;
        box-shadow: 0 8px 32px rgba(44,62,80,0.16);
        min-height: 280px;
        min-width: 0;
        padding: 40px 32px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        position: relative;
        transition: box-shadow 0.2s, transform 0.2s;
        overflow-wrap: break-word;
        word-break: break-word;
        z-index: 1;
    }
    .usuario-grid-card:hover {
        box-shadow: 0 16px 48px #22334a33;
        transform: translateY(-4px) scale(1.03);
    }
    .usuario-grid-card .usuario-nombre {
        font-size: 1.35rem;
        font-weight: 800;
        color: #22334a;
        margin-bottom: 0.3em;
    }
    .usuario-grid-card .usuario-rol {
        font-size: 1rem;
        font-weight: 700;
        color: #fff;
        background: #22334a;
        border-radius: 12px;
        padding: 0.4em 1em;
        margin-bottom: 0.7em;
        display: inline-block;
        box-shadow: 0 2px 8px #22334a22;
    }
    .usuario-grid-card .usuario-info {
        font-size: 1.05rem;
        color: #3a4a5d;
        margin-bottom: 0.4em;
        word-break: break-word;
    }
    .usuario-grid-card .usuario-activo {
        font-size: 1rem;
        font-weight: 700;
        color: #fff;
        background: #22334a;
        border-radius: 12px;
        padding: 0.4em 1em;
        margin-bottom: 0.7em;
        display: inline-block;
        box-shadow: 0 2px 8px #22334a22;
    }
    .usuario-grid-card .usuario-activo.no {
        background: #b2bec3;
        color: #fff;
    }
    .usuario-grid-card .usuario-actions {
        margin-top: 1.2em;
        display: flex;
        gap: 16px;
    }
    .usuario-grid-card .btn {
        border-radius: 50%;
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        background: #e3e6ed;
        color: #22334a;
        border: none;
        font-weight: 700;
        transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        box-shadow: 0 2px 8px #22334a11;
    }
    .usuario-grid-card .btn:hover {
        background: #22334a;
        color: #fff;
        box-shadow: 0 4px 16px #22334a22;
    }
</style>
</style>
</style>

<div class="container-fluid animate__animated animate__fadeIn" style="padding-left: 260px; min-height: 100vh; background: linear-gradient(120deg, #e9edf5 0%, #f5f7fa 100%);">
    <div class="usuarios-grid-header d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-users me-2"></i>Usuarios</h1>
        <a href="{{ route('usuarios.create') }}" class="btn">
            <i class="fas fa-user-plus me-1"></i> Crear Usuario
        </a>
    </div>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="usuarios-grid-row">
        @foreach ($usuarios as $usuario)
            <div class="usuario-grid-card">
                <div class="usuario-nombre">{{ $usuario->nombre }}</div>
                <div class="usuario-info"><strong>ID:</strong> {{ $usuario->id_usuario }}</div>
                <div class="usuario-info"><strong>Usuario:</strong> {{ $usuario->usuario }}</div>
                <div class="usuario-info"><strong>Correo:</strong> {{ $usuario->correo }}</div>
                <div class="usuario-info"><strong>Teléfono:</strong> {{ $usuario->telefono }}</div>
                <div class="usuario-rol">{{ $usuario->rol->nombre }}</div>
                <div class="usuario-activo{{ $usuario->activo ? '' : ' no' }}">{{ $usuario->activo ? 'Sí' : 'No' }}</div>
                <div class="usuario-actions">
                    <a href="{{ route('usuarios.show', $usuario) }}" class="btn" title="Ver detalles">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('usuarios.edit', $usuario) }}" class="btn" title="Editar">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn" title="Eliminar" onclick="return confirm('¿Estás seguro?')">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>