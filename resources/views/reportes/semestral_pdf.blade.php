<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte Semestral</title>
    <style>
        @page { margin: 12mm; }
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 0; }
        .header { text-align: center; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; vertical-align: top; }
        th { background: #2563eb; color: #fff; }
        .months-table td { width: 33%; padding: 6px; }
        .month-card { border:1px solid #ddd; border-radius:6px; padding:8px; box-shadow:1px 1px 4px #ccc; box-sizing:border-box; width:100%; font-size:11px; }
        .summary-grid { display:block; width:100%; margin-bottom:10px; }
        .summary-item { display:inline-block; width:32%; box-sizing:border-box; padding:8px; margin:0 1% 8px 0; border-radius:6px; font-size:11px; text-align:center; }
        .products-table td, .products-table th { padding:6px; font-size:11px; }
    </style>
</head>
<body>
    <div class="header">
    <img src="{{ config('app.logo_url') }}" alt="Logo" class="img-fluid" style="height:60px;">
        <h2>Reporte Semestral</h2>
        <p><strong>Empresa:</strong> {{ config('app.name') }}</p>
        <p><strong>Año:</strong> {{ $year }} &nbsp; <strong>Semestre:</strong> {{ $semester == 1 ? 'Enero-Junio' : 'Julio-Diciembre' }}</p>
    </div>
    @php
        // Totales por moneda (fallback to months_data if controller didn't provide sums)
        $totalVentasPen = $sum_ventas_pen ?? collect($months_data)->sum('total_ventas_pen');
        $totalVentasUsd = $sum_ventas_usd ?? collect($months_data)->sum('total_ventas_usd');
        $totalComprasPen = $sum_compras_pen ?? collect($months_data)->sum('total_compras_pen');
        $totalComprasUsd = $sum_compras_usd ?? collect($months_data)->sum('total_compras_usd');
        $totalGananciaPen = $totalVentasPen - $totalComprasPen;
        $totalGananciaUsd = $totalVentasUsd - $totalComprasUsd;
    @endphp

    <div class="summary-grid">
        <div class="summary-item" style="background: #eafaf1;"> <strong>Total Ventas (PEN):</strong><br> S/ {{ number_format($totalVentasPen ?? 0,2) }}</div>
        <div class="summary-item" style="background: #eef6ff;"> <strong>Total Ventas (USD):</strong><br> $ {{ number_format($totalVentasUsd ?? 0,2) }}</div>
        <div class="summary-item" style="background: #fbeaf1;"> <strong>Total Compras (PEN):</strong><br> S/ {{ number_format($totalComprasPen ?? 0,2) }}</div>
        <div class="summary-item" style="background: #fff5ea;"> <strong>Total Compras (USD):</strong><br> $ {{ number_format($totalComprasUsd ?? 0,2) }}</div>
        <div class="summary-item" style="background: #eaf1fb;"> <strong>Ganancia (PEN):</strong><br> S/ {{ number_format($totalGananciaPen ?? 0,2) }}</div>
        <div class="summary-item" style="background: #f0fff4;"> <strong>Ganancia (USD):</strong><br> $ {{ number_format($totalGananciaUsd ?? 0,2) }}</div>
    </div>
    <table class="months-table" style="width:100%; margin-bottom:12px; border:none;">
        <tr>
            @foreach($months_data as $i => $month)
                <td>
                    <div class="month-card">
                        <h4 style="margin:4px 0 6px 0;">{{ $month['name'] }}</h4>
                        <p style="margin:2px 0;"><strong>Ventas (PEN):</strong> S/ {{ number_format($month['total_ventas_pen'] ?? 0,2) }}</p>
                        <p style="margin:2px 0;"><strong>Ventas (USD):</strong> $ {{ number_format($month['total_ventas_usd'] ?? 0,2) }}</p>
                        <p style="margin:2px 0;"><strong>Compras (PEN):</strong> S/ {{ number_format($month['total_compras_pen'] ?? 0,2) }}</p>
                        <p style="margin:2px 0;"><strong>Compras (USD):</strong> $ {{ number_format($month['total_compras_usd'] ?? 0,2) }}</p>
                        <p style="margin:2px 0;"><strong>Ganancia (PEN):</strong> S/ {{ number_format($month['ganancia_pen'] ?? 0,2) }}</p>
                        <p style="margin:2px 0;"><strong>Ganancia (USD):</strong> $ {{ number_format($month['ganancia_usd'] ?? 0,2) }}</p>
                        <p style="margin:2px 0;"><strong>Productos Vendidos:</strong> {{ $month['cantidad_productos_vendidos'] }}</p>
                        <p style="margin:2px 0;"><strong>Productos Comprados:</strong> {{ $month['cantidad_productos_comprados'] }}</p>
                    </div>
                </td>
                @if(($i+1) % 3 == 0 && $i != 5)
                    </tr><tr>
                @endif
            @endforeach
        </tr>
    </table>
    <table class="products-table">
        <thead>
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
</body>
</html>
