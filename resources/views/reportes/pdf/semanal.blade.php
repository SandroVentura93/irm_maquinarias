<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Semanal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #14b8a6;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #14b8a6;
            margin: 0;
            font-size: 24px;
        }
        .header .subtitle {
            color: #666;
            margin-top: 5px;
        }
        .info-section {
            margin-bottom: 25px;
            background: #f0fdfa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #14b8a6;
        }
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        .info-label {
            display: table-cell;
            font-weight: bold;
            color: #0f766e;
            width: 40%;
        }
        .info-value {
            display: table-cell;
            color: #333;
        }
        .totals-section {
            margin: 25px 0;
            background: #fffbeb;
            padding: 15px;
            border-radius: 8px;
        }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 10px;
            border-bottom: 1px dashed #fde68a;
        }
        .totals-table .label {
            font-weight: bold;
            color: #666;
            width: 60%;
        }
        .totals-table .value {
            text-align: right;
            font-size: 16px;
            font-weight: bold;
            color: #14b8a6;
        }
        .totals-table tr:last-child td {
            border-bottom: none;
            border-top: 2px solid #f59e0b;
            padding-top: 15px;
            font-size: 18px;
        }
        .totals-table tr:last-child .value {
            color: #059669;
        }
        .products-section {
            margin-top: 30px;
        }
        .section-title {
            background: #14b8a6;
            color: white;
            padding: 10px 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
        }
        table.products {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.products th {
            background: #f0fdfa;
            color: #0f766e;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            border-bottom: 2px solid #14b8a6;
        }
        table.products td {
            padding: 8px 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        table.products tr:nth-child(even) {
            background: #f9fafb;
        }
        table.products tr:hover {
            background: #f0fdfa;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .amount {
            font-weight: bold;
            color: #059669;
        }
        .amount-negative {
            font-weight: bold;
            color: #dc2626;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #999;
            font-size: 10px;
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE SEMANAL</h1>
        <div class="subtitle">Análisis de Ventas y Compras</div>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Año:</span>
            <span class="info-value">{{ $year }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Semana:</span>
            <span class="info-value">{{ $week }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Periodo:</span>
            <span class="info-value">{{ \Carbon\Carbon::parse($fecha_inicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($fecha_fin)->format('d/m/Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Fecha de Generación:</span>
            <span class="info-value">{{ date('d/m/Y H:i:s') }}</span>
        </div>
    </div>

    <div class="totals-section">
        <table class="totals-table">
            <tr>
                <td class="label">Ventas (PEN):</td>
                <td class="value">S/ {{ number_format($total_ventas_pen ?? ($total_ventas ?? 0), 2) }}</td>
            </tr>
            <tr>
                <td class="label">Ventas (USD):</td>
                <td class="value">$ {{ number_format($total_ventas_usd ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td class="label">Compras (PEN):</td>
                <td class="value amount-negative">S/ {{ number_format($total_compras_pen ?? ($total_compras ?? 0), 2) }}</td>
            </tr>
            <tr>
                <td class="label">Compras (USD):</td>
                <td class="value amount-negative">$ {{ number_format($total_compras_usd ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td class="label">Ganancia (PEN):</td>
                <td class="value">S/ {{ number_format(($ganancia_pen ?? (($total_ventas ?? 0) - ($total_compras ?? 0))), 2) }}</td>
            </tr>
            <tr>
                <td class="label">Ganancia (USD):</td>
                <td class="value">$ {{ number_format(($ganancia_usd ?? 0), 2) }}</td>
            </tr>
            <tr>
                <td class="label">Productos Vendidos:</td>
                <td class="value">{{ number_format($cantidad_productos_vendidos) }}</td>
            </tr>
            <tr>
                <td class="label">Productos Comprados:</td>
                <td class="value">{{ number_format($cantidad_productos_comprados) }}</td>
            </tr>
        </table>
    </div>

    @if(count($productos) > 0)
    <div class="products-section">
        <div class="section-title">Detalle por Productos</div>
        <table class="products">
            <thead>
                <tr>
                    <th style="width: 40%;">Producto</th>
                    <th class="text-center" style="width: 12%;">Cant. Vendida</th>
                    <th class="text-right" style="width: 18%;">Total Ventas</th>
                    <th class="text-center" style="width: 12%;">Cant. Comprada</th>
                    <th class="text-right" style="width: 18%;">Total Compras</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productos as $producto)
                <tr>
                    <td>{{ $producto['nombre'] }}</td>
                    <td class="text-center">{{ $producto['cantidad_vendida'] }}</td>
                    <td class="text-right amount">S/ {{ number_format($producto['total_venta'], 2) }}</td>
                    <td class="text-center">{{ $producto['cantidad_comprada'] }}</td>
                    <td class="text-right amount-negative">S/ {{ number_format($producto['total_compra'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="footer">
        Reporte generado automáticamente por el Sistema de Gestión IRM Maquinarias S.R.L.
    </div>
</body>
</html>
