@extends('layouts.dashboard')
@section('content')
<div class="container">
    <h2 class="mb-4">Reporte Trimestral</h2>
    <form method="GET" action="{{ route('trimestral') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <label for="year" class="form-label">Año</label>
            <select name="year" id="year" class="form-select">
                @for ($y = date('Y')-5; $y <= date('Y'); $y++)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-3">
            <label for="quarter" class="form-label">Trimestre</label>
            <select name="quarter" id="quarter" class="form-select">
                @for ($q = 1; $q <= 4; $q++)
                    <option value="{{ $q }}" {{ $quarter == $q ? 'selected' : '' }}>Trimestre {{ $q }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-3 align-self-end">
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </div>
    </form>
    @if(isset($months_data))
    <div class="mb-4" style="display: flex; flex-direction: row; gap: 24px; justify-content: center;">
        @foreach($months_data as $month)
        <div class="card shadow-sm" style="min-width:220px; max-width:260px; flex:1;">
            <div class="card-body">
                <h5 class="card-title">{{ $month['name'] }}</h5>
                <p class="mb-1"><strong>Ventas:</strong> S/ {{ number_format($month['total_ventas'],2) }}</p>
                <p class="mb-1"><strong>Compras:</strong> S/ {{ number_format($month['total_compras'],2) }}</p>
                <p class="mb-1"><strong>Ganancia:</strong> S/ {{ number_format($month['ganancia'],2) }}</p>
                <p class="mb-1"><strong>Productos Vendidos:</strong> {{ $month['cantidad_productos_vendidos'] }}</p>
                <p class="mb-1"><strong>Productos Comprados:</strong> {{ $month['cantidad_productos_comprados'] }}</p>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mb-4">
        <div style="width:100%; display:flex; justify-content:center;">
            <canvas id="graficoTrimestral" width="600" height="300"></canvas>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
        window.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('graficoTrimestral').getContext('2d');
            var labels = [
                @foreach($months_data as $month)
                    '{{ $month['name'] }}',
                @endforeach
            ];
            var ventas = [
                @foreach($months_data as $month)
                    {{ $month['total_ventas'] }},
                @endforeach
            ];
            var compras = [
                @foreach($months_data as $month)
                    {{ $month['total_compras'] }},
                @endforeach
            ];
            var ganancias = [
                @foreach($months_data as $month)
                    {{ $month['ganancia'] }},
                @endforeach
            ];
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Ventas',
                            data: ventas,
                            backgroundColor: '#43cea2',
                        },
                        {
                            label: 'Compras',
                            data: compras,
                            backgroundColor: '#ee0979',
                        },
                        {
                            label: 'Ganancia',
                            data: ganancias,
                            backgroundColor: '#0072ff',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Resumen Trimestral'
                        }
                    }
                }
            });
        });
        </script>
        <div class="row justify-content-center mt-4">
            @php
                $totalVentas = collect($months_data)->sum('total_ventas');
                $totalCompras = collect($months_data)->sum('total_compras');
                $totalGanancia = collect($months_data)->sum('ganancia');
            @endphp
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="mb-3">Totales del Trimestre</h5>
                        <p><strong>Total Ventas:</strong> S/ {{ number_format($totalVentas,2) }}</p>
                        <p><strong>Total Compras:</strong> S/ {{ number_format($totalCompras,2) }}</p>
                        <p><strong>Ganancia Total:</strong> S/ {{ number_format($totalGanancia,2) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-4">
        <a href="{{ route('trimestral.pdf', ['year' => $year, 'quarter' => $quarter]) }}" class="btn btn-danger me-2">Exportar PDF</a>
        <a href="{{ route('trimestral.excel', ['year' => $year, 'quarter' => $quarter]) }}" class="btn btn-success">Exportar Excel</a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Mes</th>
                    <th>Producto</th>
                    <th>Cantidad Vendida</th>
                    <th>Total Ventas</th>
                    <th>Cantidad Comprada</th>
                    <th>Total Compras</th>
                </tr>
            </thead>
            <tbody>
                @foreach($months_data as $month)
                    @foreach($month['productos'] as $producto)
                    <tr>
                        <td>{{ $month['name'] }}</td>
                        <td>{{ $producto['nombre'] }}</td>
                        <td>{{ $producto['cantidad_vendida'] }}</td>
                        <td>S/ {{ number_format($producto['total_venta'],2) }}</td>
                        <td>{{ $producto['cantidad_comprada'] }}</td>
                        <td>S/ {{ number_format($producto['total_compra'],2) }}</td>
                    </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
