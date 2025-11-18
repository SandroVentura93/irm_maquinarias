@extends('layouts.dashboard')

@section('content')
</style>
<style>
    body {
        background: linear-gradient(120deg, #eaf6fb 0%, #f5f7fa 100%);
    }
    .usuario-glass {
        background: #f5f7fa;
        border-radius: 20px;
        box-shadow: 0 4px 16px 0 rgba(44,62,80,0.10);
        border: 1px solid #c3d1dd;
        padding: 2.5rem 2rem;
        margin-top: 40px;
        max-width: 480px;
        margin-left: auto;
        margin-right: auto;
    }
    .usuario-avatar {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        object-fit: cover;
        box-shadow: 0 2px 12px #c3d1dd;
        border: 3px solid #234567;
        margin-bottom: 18px;
    }
    .usuario-nombre {
        font-size: 2rem;
        font-weight: 800;
        color: #234567;
        margin-bottom: 0.5rem;
        text-align: center;
    }
    .usuario-info {
        font-size: 1.1rem;
        color: #345678;
        margin-bottom: 0.7rem;
        background: #eaf6fb;
        border-radius: 12px;
        padding: 0.5em 1em;
        box-shadow: 0 2px 8px #c3d1dd22;
    }
    .usuario-rol {
        font-size: 1rem;
        font-weight: 700;
        color: #fff;
        background: #234567;
        border-radius: 12px;
        padding: 0.5em 1em;
        box-shadow: 0 2px 8px #c3d1dd22;
        margin-bottom: 0.7rem;
        display: inline-block;
    }
    .usuario-activo {
        font-size: 1rem;
        font-weight: 700;
        color: #fff;
        background: #234567;
        border-radius: 12px;
        padding: 0.5em 1em;
        box-shadow: 0 2px 8px #c3d1dd22;
        margin-bottom: 0.7rem;
        display: inline-block;
    }
    .usuario-activo.no {
        opacity: 0.7;
        background: #b2bec3;
    }
    .btn-frio {
        background: #234567;
        color: #fff;
        font-weight: 700;
        border: none;
        border-radius: 50px;
        box-shadow: 0 2px 8px #c3d1dd44;
        padding: 0.7em 2em;
        font-size: 1.1rem;
        transition: background 0.3s, box-shadow 0.3s;
    }
    .btn-frio:hover {
        background: #123456;
        box-shadow: 0 4px 16px #23456744;
    }
</style>
</style>
<div class="container">
    <h1 class="text-center fw-bold mb-4" style="letter-spacing:2px;color:#234567;"><i class="fas fa-user me-2"></i>Detalles del Usuario</h1>
    <div class="usuario-glass animate__animated animate__fadeIn">
        <div class="d-flex flex-column align-items-center mb-3">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($usuario->nombre) }}&background=234567&color=fff&size=128" alt="Avatar" class="usuario-avatar">
            <div class="usuario-nombre">{{ $usuario->nombre }}</div>
        </div>
        <div class="usuario-info"><strong>Usuario:</strong> {{ $usuario->usuario }}</div>
        <div class="usuario-info"><strong>Correo:</strong> {{ $usuario->correo }}</div>
        <div class="usuario-info"><strong>Teléfono:</strong> {{ $usuario->telefono }}</div>
        <div class="usuario-rol"><i class="fas fa-user-tag me-1"></i> {{ $usuario->rol->nombre }}</div>
        <div class="usuario-activo{{ $usuario->activo ? '' : ' no' }}">
            <i class="fas fa-{{ $usuario->activo ? 'check' : 'times' }}-circle me-1"></i> {{ $usuario->activo ? 'Activo' : 'No activo' }}
        </div>
        <div class="mt-4 text-center">
            <a href="{{ route('usuarios.index') }}" class="btn btn-frio"><i class="fas fa-arrow-left me-2"></i>Volver</a>
        </div>
    </div>
</div>
@endsection