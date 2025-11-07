@extends('layouts.dashboard')

@section('content')
<div class="container">
    <h1>Editar Cliente</h1>
    <form action="{{ route('clientes.update', $cliente->id_cliente) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="tipo_documento">Tipo Documento</label>
            <select name="tipo_documento" id="tipo_documento" class="form-control">
                <option value="DNI" {{ $cliente->tipo_documento == 'DNI' ? 'selected' : '' }}>DNI</option>
                <option value="RUC" {{ $cliente->tipo_documento == 'RUC' ? 'selected' : '' }}>RUC</option>
                <option value="PASAPORTE" {{ $cliente->tipo_documento == 'PASAPORTE' ? 'selected' : '' }}>PASAPORTE</option>
            </select>
        </div>
        <div class="form-group">
            <label for="numero_documento">Número Documento</label>
            <input type="text" name="numero_documento" id="numero_documento" class="form-control" value="{{ $cliente->numero_documento }}">
        </div>
        <div class="form-group">
            <label for="razon_social">Razón Social</label>
            <input type="text" name="razon_social" id="razon_social" class="form-control" value="{{ $cliente->razon_social }}">
        </div>
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ $cliente->nombre }}">
        </div>
        <div class="form-group">
            <label for="direccion">Dirección</label>
            <input type="text" name="direccion" id="direccion" class="form-control" value="{{ $cliente->direccion }}">
        </div>
        <div class="form-group">
            <label for="telefono">Teléfono</label>
            <input type="text" name="telefono" id="telefono" class="form-control" value="{{ $cliente->telefono }}">
        </div>
        <div class="form-group">
            <label for="correo">Correo</label>
            <input type="email" name="correo" id="correo" class="form-control" value="{{ $cliente->correo }}">
        </div>
        <div class="form-group">
            <label for="activo">Activo</label>
            <select name="activo" id="activo" class="form-control">
                <option value="1" {{ $cliente->activo ? 'selected' : '' }}>Sí</option>
                <option value="0" {{ !$cliente->activo ? 'selected' : '' }}>No</option>
            </select>
        </div>
        <div class="form-group">
            <label for="id_ubigeo">Ubigeo</label>
            <select name="id_ubigeo" id="id_ubigeo" class="form-control">
                <option value="">Seleccione un ubigeo</option>
                @foreach ($ubigeos as $ubigeo)
                    <option value="{{ $ubigeo->id_ubigeo }}" {{ $cliente->id_ubigeo == $ubigeo->id_ubigeo ? 'selected' : '' }}>
                        {{ $ubigeo->departamento }} - {{ $ubigeo->provincia }} - {{ $ubigeo->distrito }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>
</div>
@endsection