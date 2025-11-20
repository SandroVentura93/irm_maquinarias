<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte Trimestral</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .metrics { display: flex; justify-content: space-around; margin-bottom: 20px; }
        .card { border: 1px solid #ddd; border-radius: 8px; padding: 12px; width: 30%; box-shadow: 1px 1px 6px #ccc; }
        .chart { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background: #f5f5f5; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ config('app.logo_url') }}" alt="Logo" height="60">
        <h2 style="margin-bottom: 5px;">Reporte Trimestral</h2>
        <p style="margin-bottom: 2px;"><strong>Empresa:</strong> {{ config('app.name') }}</p>
        <p style="margin-bottom: 2px;"><strong>Año:</strong> {{ $year }} &nbsp; <strong>Trimestre:</strong> {{ $quarter }}</p>
    </div>
    @php
        $totalVentas = collect($months_data)->sum('total_ventas');
        $totalCompras = collect($months_data)->sum('total_compras');
        $totalGanancia = collect($months_data)->sum('ganancia');
    @endphp
    <div style="margin-bottom: 20px; display: flex; justify-content: center; gap: 24px;">
        <div style="flex:1; padding: 16px; background: #eafaf1; border-radius: 8px; font-size: 1.1em; text-align:center; box-shadow: 1px 1px 6px #ccc;">
            <strong>Total Ventas:</strong><br> S/ {{ number_format($totalVentas,2) }}
        </div>
        <div style="flex:1; padding: 16px; background: #fbeaf1; border-radius: 8px; font-size: 1.1em; text-align:center; box-shadow: 1px 1px 6px #ccc;">
            <strong>Total Compras:</strong><br> S/ {{ number_format($totalCompras,2) }}
        </div>
        <div style="flex:1; padding: 16px; background: #eaf1fb; border-radius: 8px; font-size: 1.1em; text-align:center; box-shadow: 1px 1px 6px #ccc;">
            <strong>Ganancia Total:</strong><br> S/ {{ number_format($totalGanancia,2) }}
        </div>
    </div>
    <table style="width:100%; margin-bottom:20px; border:none;">
        <tr>
            @foreach($months_data as $month)
            <td style="vertical-align:top; padding:0 8px;">
                <div class="card" style="border:1px solid #ddd; border-radius:8px; padding:12px; min-width:160px; max-width:220px; box-shadow:1px 1px 6px #ccc;">
                    <h4>{{ $month['name'] }}</h4>
                    <p><strong>Ventas:</strong> S/ {{ number_format($month['total_ventas'],2) }}</p>
                    <p><strong>Compras:</strong> S/ {{ number_format($month['total_compras'],2) }}</p>
                    <p><strong>Ganancia:</strong> S/ {{ number_format($month['ganancia'],2) }}</p>
                    <p><strong>Productos Vendidos:</strong> {{ $month['cantidad_productos_vendidos'] }}</p>
                    <p><strong>Productos Comprados:</strong> {{ $month['cantidad_productos_comprados'] }}</p>
                </div>
            </td>
            @endforeach
        </tr>
    </table>
    <table>
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
