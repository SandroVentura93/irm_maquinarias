
<div style="width:100%; font-family: Arial, Helvetica, sans-serif; background: #f8fafc; padding: 20px;">
    <div style="display:flex; align-items:center; justify-content:center; margin-bottom:20px; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px #e2e8f0; padding: 10px;">
        <div style="flex:0 0 80px;">
            <img src="{{ public_path('images/logo.png') }}" alt="Logo" style="max-width:80px; max-height:80px;">
        </div>
        <div style="flex:1; text-align:center;">
            <h1 style="margin-bottom:0; color:#2563eb; font-size:2.2em;">{{ config('app.name') }}</h1>
            <span style="font-size:16px;">RUC: 20481234567</span><br>
            <span style="font-size:14px;">Av. Ejemplo 123, Lima, Perú</span><br>
            <span style="font-size:14px;">{{ config('app.env') == 'local' ? 'Demo' : 'Producción' }}</span>
            <br>
            <span style="font-size:18px; font-weight:bold; color:#0d9488;">Reporte Mensual de Ventas y Compras</span>
        </div>
    </div>
    <p><strong>Año:</strong> {{ $year }}<br>
    <strong>Mes:</strong> {{ DateTime::createFromFormat('!m', $month)->format('F') }}</p>
    <h4 style="color:#0d9488; margin-top:20px;">Resumen Gráfico Mensual</h4>
    @if(!empty($grafico_path) && file_exists($grafico_path))
        <div style="text-align:center; margin-bottom:30px;">
            <img src="{{ $grafico_path }}" alt="Gráfico Mensual" style="max-width:500px; max-height:300px;">
        </div>
    @endif
    <hr style="border:0; border-top:2px solid #2563eb; margin: 20px 0;">
    <table style="width:100%; border-collapse:collapse; margin-bottom:20px; background:#fff; border-radius:8px; box-shadow:0 1px 4px #e2e8f0;">
        <tr style="background:#2563eb; color:#fff;">
            <th style="border:1px solid #2563eb; padding:8px;">Total Vendido</th>
            <th style="border:1px solid #2563eb; padding:8px;">Total Comprado</th>
            <th style="border:1px solid #2563eb; padding:8px;">Ganancia Neta</th>
            <th style="border:1px solid #2563eb; padding:8px;">Productos Vendidos</th>
            <th style="border:1px solid #2563eb; padding:8px;">Productos Comprados</th>
        </tr>
        <tr style="background:#e0e7ff;">
            <td style="border:1px solid #2563eb; padding:8px;">S/. {{ number_format($total_ventas, 2) }}</td>
            <td style="border:1px solid #2563eb; padding:8px;">S/. {{ number_format($total_compras, 2) }}</td>
            <td style="border:1px solid #2563eb; padding:8px;">S/. {{ number_format($ganancia, 2) }}</td>
            <td style="border:1px solid #2563eb; padding:8px;">{{ $cantidad_productos_vendidos }}</td>
            <td style="border:1px solid #2563eb; padding:8px;">{{ $cantidad_productos_comprados }}</td>
        </tr>
    </table>
    <h4 style="color:#0d9488; margin-top:30px;">Ventas por Producto</h4>
    <table style="width:100%; border-collapse:collapse; margin-bottom:20px; background:#fff; border-radius:8px; box-shadow:0 1px 4px #e2e8f0;">
        <thead>
            <tr style="background:#2563eb; color:#fff;">
                <th style="border:1px solid #2563eb; padding:8px;">Producto</th>
                <th style="border:1px solid #2563eb; padding:8px;">Cantidad</th>
                <th style="border:1px solid #2563eb; padding:8px;">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ventas_por_producto as $venta)
            <tr style="background:#e0e7ff;">
                <td style="border:1px solid #2563eb; padding:8px;">{{ $venta->producto && $venta->producto->descripcion ? $venta->producto->descripcion : 'Producto eliminado' }}</td>
                <td style="border:1px solid #2563eb; padding:8px;">{{ $venta->cantidad }}</td>
                <td style="border:1px solid #2563eb; padding:8px;">S/. {{ number_format($venta->total, 2) }}</td>
            </tr>
            @empty
            <tr><td colspan="3" style="text-align:center;">No se registraron ventas de productos en este rango.</td></tr>
            @endforelse
        </tbody>
    </table>
    <h4 style="color:#0d9488; margin-top:30px;">Compras por Producto</h4>
    <table style="width:100%; border-collapse:collapse; background:#fff; border-radius:8px; box-shadow:0 1px 4px #e2e8f0;">
        <thead>
            <tr style="background:#2563eb; color:#fff;">
                <th style="border:1px solid #2563eb; padding:8px;">Producto</th>
                <th style="border:1px solid #2563eb; padding:8px;">Cantidad</th>
                <th style="border:1px solid #2563eb; padding:8px;">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($compras_por_producto as $compra)
            <tr style="background:#e0e7ff;">
                <td style="border:1px solid #2563eb; padding:8px;">{{ $compra->producto && $compra->producto->descripcion ? $compra->producto->descripcion : 'Producto eliminado' }}</td>
                <td style="border:1px solid #2563eb; padding:8px;">{{ $compra->cantidad }}</td>
                <td style="border:1px solid #2563eb; padding:8px;">S/. {{ number_format($compra->total, 2) }}</td>
            </tr>
            @empty
            <tr><td colspan="3" style="text-align:center;">No se registraron compras de productos en este rango.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
