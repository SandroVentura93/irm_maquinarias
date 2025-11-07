@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Proveedor</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('proveedores.update', $proveedor->id_proveedor) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="razon_social">Razón Social</label>
            <input type="text" name="razon_social" id="razon_social" class="form-control" value="{{ old('razon_social', $proveedor->razon_social) }}" required>
        </div>
        <div class="form-group">
            <label for="tipo_documento">Tipo de Documento</label>
            <select name="tipo_documento" id="tipo_documento" class="form-control" required>
                <option value="DNI" {{ $proveedor->tipo_documento == 'DNI' ? 'selected' : '' }}>DNI</option>
                <option value="RUC" {{ $proveedor->tipo_documento == 'RUC' ? 'selected' : '' }}>RUC</option>
                <option value="PASAPORTE" {{ $proveedor->tipo_documento == 'PASAPORTE' ? 'selected' : '' }}>Pasaporte</option>
            </select>
        </div>
        <div class="form-group">
            <label for="numero_documento">Número de Documento</label>
            <input type="text" name="numero_documento" id="numero_documento" class="form-control" value="{{ old('numero_documento', $proveedor->numero_documento) }}" required>
        </div>
        <div class="form-group">
            <label for="contacto">Contacto</label>
            <input type="text" name="contacto" id="contacto" class="form-control" value="{{ old('contacto', $proveedor->contacto) }}">
        </div>
        <div class="form-group">
            <label for="telefono">Teléfono</label>
            <input type="text" name="telefono" id="telefono" class="form-control" value="{{ old('telefono', $proveedor->telefono) }}">
        </div>
        <div class="form-group">
            <label for="correo">Correo</label>
            <input type="email" name="correo" id="correo" class="form-control" value="{{ old('correo', $proveedor->correo) }}">
        </div>
        <div class="form-group">
            <label for="direccion">Dirección</label>
            <input type="text" name="direccion" id="direccion" class="form-control" value="{{ old('direccion', $proveedor->direccion) }}">
        </div>
        <div class="form-group">
            <label for="id_ubigeo">Ubigeo</label>
            <select name="id_ubigeo" id="id_ubigeo" class="form-control">
                @foreach ($ubigeos as $ubigeo)
                    <option value="{{ $ubigeo->id_ubigeo }}" {{ $proveedor->id_ubigeo == $ubigeo->id_ubigeo ? 'selected' : '' }}>
                        {{ $ubigeo->departamento }} - {{ $ubigeo->provincia }} - {{ $ubigeo->distrito }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="activo">Activo</label>
            <select name="activo" id="activo" class="form-control">
                <option value="1" {{ $proveedor->activo ? 'selected' : '' }}>Si</option>
                <option value="0" {{ !$proveedor->activo ? 'selected' : '' }}>No</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Actualizar</button>
    </form>
</div>
@endsection