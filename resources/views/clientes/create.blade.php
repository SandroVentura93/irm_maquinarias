@extends('layouts.dashboard')

@section('content')
<div class="container">
    <h1>Crear Cliente</h1>
    <form action="{{ route('clientes.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="tipo_documento">Tipo Documento</label>
            <select name="tipo_documento" id="tipo_documento" class="form-control">
                <option value="DNI">DNI</option>
                <option value="RUC">RUC</option>
                <option value="PASAPORTE">PASAPORTE</option>
            </select>
        </div>
        <div class="form-group">
            <label for="numero_documento">Número Documento</label>
            <input type="text" name="numero_documento" id="numero_documento" class="form-control">
        </div>
        <div class="form-group">
            <label for="razon_social">Razón Social</label>
            <input type="text" name="razon_social" id="razon_social" class="form-control">
        </div>
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control">
        </div>
        <div class="form-group">
            <label for="direccion">Dirección</label>
            <input type="text" name="direccion" id="direccion" class="form-control">
        </div>
        <div class="form-group">
            <label for="telefono">Teléfono</label>
            <input type="text" name="telefono" id="telefono" class="form-control">
        </div>
        <div class="form-group">
            <label for="correo">Correo</label>
            <input type="email" name="correo" id="correo" class="form-control">
        </div>
        <div class="form-group">
            <label for="activo">Activo</label>
            <select name="activo" id="activo" class="form-control">
                <option value="1">Sí</option>
                <option value="0">No</option>
            </select>
        </div>
        <div class="form-group">
            <label for="id_ubigeo">Ubigeo</label>
            <select name="id_ubigeo" id="id_ubigeo" class="form-control">
                <option value="">Seleccione un ubigeo</option>
                @foreach ($ubigeos as $ubigeo)
                    <option value="{{ $ubigeo->id_ubigeo }}">
                        {{ $ubigeo->departamento }} - {{ $ubigeo->provincia }} - {{ $ubigeo->distrito }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
</div>
@endsection