@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Crear Nuevo Proveedor</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('proveedores.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="razon_social">Razón Social</label>
            <input type="text" name="razon_social" id="razon_social" class="form-control" value="{{ old('razon_social') }}" required>
        </div>
        <div class="form-group">
            <label for="tipo_documento">Tipo de Documento</label>
            <select name="tipo_documento" id="tipo_documento" class="form-control" required>
                <option value="">Seleccione</option>
                <option value="DNI">DNI</option>
                <option value="RUC">RUC</option>
                <option value="PASAPORTE">Pasaporte</option>
            </select>
        </div>
        <div class="form-group">
            <label for="numero_documento">Número de Documento</label>
            <input type="text" name="numero_documento" id="numero_documento" class="form-control" value="{{ old('numero_documento') }}" required>
        </div>
        <div class="form-group">
            <label for="contacto">Contacto</label>
            <input type="text" name="contacto" id="contacto" class="form-control" value="{{ old('contacto') }}">
        </div>
        <div class="form-group">
            <label for="telefono">Teléfono</label>
            <input type="text" name="telefono" id="telefono" class="form-control" value="{{ old('telefono') }}">
        </div>
        <div class="form-group">
            <label for="correo">Correo</label>
            <input type="email" name="correo" id="correo" class="form-control" value="{{ old('correo') }}">
        </div>
        <div class="form-group">
            <label for="direccion">Dirección</label>
            <input type="text" name="direccion" id="direccion" class="form-control" value="{{ old('direccion') }}">
        </div>
        <div class="form-group">
            <label for="id_ubigeo">Ubigeo</label>
            <select name="id_ubigeo" id="id_ubigeo" class="form-control">
                <option value="">Seleccione un Ubigeo</option>
                @foreach ($ubigeos as $ubigeo)
                    <option value="{{ $ubigeo->id_ubigeo }}">{{ $ubigeo->departamento }} - {{ $ubigeo->provincia }} - {{ $ubigeo->distrito }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="activo">Activo</label>
            <select name="activo" id="activo" class="form-control">
                <option value="1" selected>Sí</option>
                <option value="0">No</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Guardar</button>
    </form>

    <h2>Lista de Proveedores</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Razón Social</th>
                <th>Tipo Documento</th>
                <th>Número Documento</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($proveedores as $proveedor)
                <tr>
                    <td>{{ $proveedor->id_proveedor }}</td>
                    <td>{{ $proveedor->razon_social }}</td>
                    <td>{{ $proveedor->tipo_documento }}</td>
                    <td>{{ $proveedor->numero_documento }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection