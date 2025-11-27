@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-book"></i> Gestión de Bitácoras</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Usuario</th>
                                <th>Acción</th>
                                <th>Descripción</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bitacoras as $bitacora)
                            <tr>
                                <td>{{ $bitacora->id_bitacora }}</td>
                                <td>{{ $bitacora->id_usuario }}</td>
                                <td>{{ $bitacora->accion }}</td>
                                <td>{{ $bitacora->descripcion }}</td>
                                <td>{{ $bitacora->fecha }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection