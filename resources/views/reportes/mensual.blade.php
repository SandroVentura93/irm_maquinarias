@extends('layouts.dashboard')
@section('content')
<div class="container py-4">
    <h2>Reporte Mensual</h2>
    <form method="GET" class="mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label for="year" class="form-label">Año</label>
                <input type="number" name="year" id="year" class="form-control" value="{{ request('year', date('Y')) }}" min="2020" max="{{ date('Y') }}" required>
            </div>
            <div class="col-md-4">
                <label for="month" class="form-label">Mes</label>
                <select name="month" id="month" class="form-control" required>
                    @for($m=1; $m<=12; $m++)
                        <option value="{{ sprintf('%02d', $m) }}" {{ request('month', date('m')) == sprintf('%02d', $m) ? 'selected' : '' }}>{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">Ver Reporte</button>
            </div>
        </div>
    </form>
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
                    <h5 class="card-title text-center mb-3" style="color:#0d9488; font-weight:600; font-size:1.2em;">Resumen Gráfico Mensual</h5>
                    <canvas id="graficoMensual" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-12 text-start">
            <a href="/mensual/pdf?year={{ request('year') }}&month={{ request('month') }}" class="btn btn-danger me-2" style="font-weight:bold; box-shadow:0 2px 8px #e2e8f0;">
                <i class="fas fa-file-pdf"></i> Exportar PDF
            </a>
            <a href="/mensual/excel?year={{ request('year') }}&month={{ request('month') }}" class="btn btn-success" style="font-weight:bold; box-shadow:0 2px 8px #e2e8f0;">
                <i class="fas fa-file-excel"></i> Exportar Excel
            </a>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('graficoMensual').getContext('2d');
const graficoMensual = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Ventas', 'Compras', 'Ganancia'],
        datasets: [{
            label: 'Reporte Mensual',
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
            legend: { display: false }
        }
    }
});
</script>
@endsection
