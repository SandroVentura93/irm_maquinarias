@extends('layouts.dashboard')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Clientes</h5>
                <p class="card-text">{{ $clientes }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Productos</h5>
                <p class="card-text">{{ $productos }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Monedas</h5>
                <p class="card-text">{{ $monedas }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Alertas de Stock</h5>
                <p class="card-text">{{ $alertas }}</p>
            </div>
        </div>
    </div>
</div>
@endsection