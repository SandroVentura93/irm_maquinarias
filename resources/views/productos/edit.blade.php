@extends('layouts.dashboard')

@section('content')
<div class="container">
    <h1>Editar Producto</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('productos.update', $producto) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="codigo">Código</label>
            <input type="text" name="codigo" id="codigo" class="form-control" value="{{ old('codigo', $producto->codigo) }}" required>
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <input type="text" name="descripcion" id="descripcion" class="form-control" value="{{ old('descripcion', $producto->descripcion) }}" required>
        </div>
        <div class="form-group">
            <label for="stock_actual">Stock Actual</label>
            <input type="number" name="stock_actual" id="stock_actual" class="form-control" value="{{ old('stock_actual', $producto->stock_actual) }}" required>
        </div>
        <div class="form-group">
            <label for="stock_minimo">Stock Mínimo</label>
            <input type="number" name="stock_minimo" id="stock_minimo" class="form-control" value="{{ old('stock_minimo', $producto->stock_minimo) }}" required>
        </div>
        <div class="form-group">
            <label for="precio_compra">Precio Compra</label>
            <input type="number" step="0.01" name="precio_compra" id="precio_compra" class="form-control" value="{{ old('precio_compra', $producto->precio_compra) }}" required>
        </div>
        <div class="form-group">
            <label for="precio_venta">Precio Venta</label>
            <input type="number" step="0.01" name="precio_venta" id="precio_venta" class="form-control" value="{{ old('precio_venta', $producto->precio_venta) }}" required>
        </div>
        <div class="form-group">
            <label for="id_categoria">Categoría</label>
            <select name="id_categoria" id="id_categoria" class="form-control">
                <option value="">Seleccione una categoría</option>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id_categoria }}" {{ old('id_categoria', $producto->id_categoria) == $categoria->id_categoria ? 'selected' : '' }}>
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="id_marca">Marca</label>
            <select name="id_marca" id="id_marca" class="form-control">
                <option value="">Seleccione una marca</option>
                @foreach ($marcas as $marca)
                    <option value="{{ $marca->id }}" {{ old('id_marca', $producto->id_marca) == $marca->id ? 'selected' : '' }}>
                        {{ $marca->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="id_proveedor">Proveedor</label>
            <select name="id_proveedor" id="id_proveedor" class="form-control">
                <option value="">Seleccione un proveedor</option>
                @foreach ($proveedores as $proveedor)
                    <option value="{{ $proveedor->id_proveedor }}" {{ old('id_proveedor', $producto->id_proveedor) == $proveedor->id_proveedor ? 'selected' : '' }}>
                        {{ $proveedor->razon_social }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="numero_parte">Número de Parte</label>
            <input type="text" name="numero_parte" id="numero_parte" class="form-control" value="{{ old('numero_parte', $producto->numero_parte) }}">
        </div>
        <div class="form-group">
            <label for="modelo">Modelo</label>
            <input type="text" name="modelo" id="modelo" class="form-control" value="{{ old('modelo', $producto->modelo) }}">
        </div>
        <div class="form-group">
            <label for="peso">Peso</label>
            <input type="number" step="0.01" name="peso" id="peso" class="form-control" value="{{ old('peso', $producto->peso) }}">
        </div>
        <div class="form-group">
            <label for="ubicacion">Ubicación</label>
            <input type="text" name="ubicacion" id="ubicacion" class="form-control" value="{{ old('ubicacion', $producto->ubicacion) }}">
        </div>
        <div class="form-group">
            <label for="importado">Importado</label>
            <select name="importado" id="importado" class="form-control">
                <option value="1" {{ old('importado', $producto->importado) == '1' ? 'selected' : '' }}>Sí</option>
                <option value="0" {{ old('importado', $producto->importado) == '0' ? 'selected' : '' }}>No</option>
            </select>
        </div>
        <div class="form-group">
            <label for="activo">Activo</label>
            <select name="activo" id="activo" class="form-control">
                <option value="1" {{ old('activo', $producto->activo) == '1' ? 'selected' : '' }}>Sí</option>
                <option value="0" {{ old('activo', $producto->activo) == '0' ? 'selected' : '' }}>No</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection