@extends('layouts.dashboard')
@section('content')
<div class="container py-4">
    <h2>Reporte Diario</h2>
    <form method="GET" class="mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label for="fecha" class="form-label">Selecciona el día</label>
                <input type="date" name="fecha" id="fecha" class="form-control" value="{{ request('fecha', date('Y-m-d')) }}" required>
            </div>
            <div class="col-md-3">
                <label for="hora_inicio" class="form-label">Hora inicio</label>
                <input type="time" name="hora_inicio" id="hora_inicio" class="form-control" value="{{ request('hora_inicio', '00:00') }}" required>
            </div>
            <div class="col-md-3">
                <label for="hora_fin" class="form-label">Hora fin</label>
                <input type="time" name="hora_fin" id="hora_fin" class="form-control" value="{{ request('hora_fin', '23:59') }}" required>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Ver Reporte</button>
            </div>
        </div>
    </form>
    <!-- ...contenido del reporte... -->
    <div class="row mt-4">
        <div class="col-md-12 text-start">
            <a href="{{ route('reportes.diario.pdf', request()->all()) }}" class="btn btn-danger me-2" target="_blank" style="font-weight:bold; box-shadow:0 2px 8px #e2e8f0;">
                <i class="fas fa-file-pdf"></i> Exportar PDF
            </a>
            <a href="{{ route('reportes.diario.excel', request()->all()) }}" class="btn btn-success" target="_blank" style="font-weight:bold; box-shadow:0 2px 8px #e2e8f0;">
                <i class="fas fa-file-excel"></i> Exportar Excel
            </a>
        </div>
    </div>
    <div class="row align-items-center mt-4">
        <div class="col-md-6">
            <div class="d-flex flex-column gap-4">
                <div class="card shadow-sm border-0" style="border-radius:18px; background:#fff;">
                    <div class="card-body text-center py-4">
                        <h5 class="card-title mb-2" style="color:#2563eb; font-weight:600; font-size:1.2em;">Total Vendido</h5>
                        <h3 style="color:#22c55e; font-weight:bold; font-size:2em; letter-spacing:1px;">S/. {{ number_format($total_ventas, 2) }}</h3>
                    </div>
                </div>
                <div class="card shadow-sm border-0" style="border-radius:18px; background:#fff;">
                    <div class="card-body text-center py-4">
                        <h5 class="card-title mb-2" style="color:#2563eb; font-weight:600; font-size:1.2em;">Total Comprado</h5>
                        <h3 style="color:#ef4444; font-weight:bold; font-size:2em; letter-spacing:1px;">S/. {{ number_format($total_compras, 2) }}</h3>
                    </div>
                </div>
                <div class="card shadow-sm border-0" style="border-radius:18px; background:#fff;">
                    <div class="card-body text-center py-4">
                        <h5 class="card-title mb-2" style="color:#2563eb; font-weight:600; font-size:1.2em;">Productos Vendidos</h5>
                        <h3 style="color:#0ea5e9; font-weight:bold; font-size:2em; letter-spacing:1px;">{{ $cantidad_productos_vendidos }}</h3>
                    </div>
                </div>
                <div class="card shadow-sm border-0" style="border-radius:18px; background:#fff;">
                    <div class="card-body text-center py-4">
                        <h5 class="card-title mb-2" style="color:#2563eb; font-weight:600; font-size:1.2em;">Productos Comprados</h5>
                        <h3 style="color:#eab308; font-weight:bold; font-size:2em; letter-spacing:1px;">{{ $cantidad_productos_comprados }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0" style="border-radius:18px; background:#fff;">
                <div class="card-body py-4">
                    <h5 class="card-title text-center mb-3" style="color:#0d9488; font-weight:600; font-size:1.2em;">Resumen Gráfico Diario</h5>
                    <canvas id="graficoDiario" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('graficoDiario').getContext('2d');
const graficoDiario = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Ventas', 'Compras', 'Ganancia'],
        datasets: [{
            label: 'Reporte Diario',
            data: [{{ $total_ventas }}, {{ $total_compras }}, {{ $ganancia }}],
            backgroundColor: [
                'linear-gradient(90deg, #43cea2 0%, #185a9d 100%)',
                'linear-gradient(90deg, #ff6a00 0%, #ee0979 100%)',
                'linear-gradient(90deg, #00c6ff 0%, #0072ff 100%)'
            ],
            borderColor: [
                '#185a9d',
                '#ee0979',
                '#0072ff'
            ],
            borderWidth: 2,
            borderRadius: 8,
            hoverBackgroundColor: [
                'rgba(67,206,162,0.9)',
                'rgba(255,106,0,0.9)',
                'rgba(0,198,255,0.9)'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true,
                position: 'top',
                labels: {
                    font: {
                        size: 16,
                        family: 'Inter, Arial, sans-serif',
                        weight: 'bold'
                    }
                }
            },
            title: {
                display: true,
                text: 'Resumen Diario de Ventas y Compras',
                font: {
                    size: 18,
                    family: 'Inter, Arial, sans-serif',
                    weight: 'bold'
                }
            },
            tooltip: {
                enabled: true,
                backgroundColor: '#222',
                titleColor: '#fff',
                bodyColor: '#fff',
                borderColor: '#43cea2',
                borderWidth: 2,
                padding: 12
            }
        },
        animation: {
            duration: 1200,
            easing: 'easeOutBounce'
        },
        scales: {
            x: {
                grid: {
                    display: false
                }
            },
            y: {
                beginAtZero: true,
                grid: {
                    color: '#eee'
                }
            }
        }
    }
});
</script>
@endsection
