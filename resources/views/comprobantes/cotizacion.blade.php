<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COTIZACIÓN - {{ $venta->serie }}{{ $venta->numero }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 15px;
        }
        
        .company-info {
            display: table-cell;
            width: 60%;
            vertical-align: top;
        }
        
        .company-logo {
            width: 120px;
            height: 80px;
            background: #f8f9fa;
            border: 2px solid #007bff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            font-size: 10px;
            color: #007bff;
            font-weight: bold;
        }
        
        .company-details h2 {
            color: #007bff;
            margin-bottom: 5px;
            font-size: 18px;
        }
        
        .document-info {
            display: table-cell;
            width: 35%;
            vertical-align: top;
            text-align: center;
            border: 2px solid #007bff;
            padding: 15px;
            background: #f8f9fa;
        }
        
        .document-info h1 {
            color: #007bff;
            margin-bottom: 10px;
            font-size: 20px;
        }
        
        .document-number {
            font-size: 16px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        
        .client-info {
            background: #f8f9fa;
            padding: 15px;
            margin: 20px 0;
            border-left: 4px solid #007bff;
        }
        
        .client-info h3 {
            color: #007bff;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .details-table th {
            background: #007bff;
            color: white;
            padding: 10px 8px;
            text-align: center;
            font-size: 11px;
            border: 1px solid #0056b3;
        }
        
        .details-table td {
            padding: 8px;
            border: 1px solid #dee2e6;
            text-align: center;
            font-size: 11px;
        }
        
        .details-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .totals {
            margin-top: 20px;
            text-align: right;
        }
        
        .totals table {
            margin-left: auto;
            border-collapse: collapse;
            min-width: 300px;
        }
        
        .totals td {
            padding: 8px 15px;
            border: 1px solid #dee2e6;
        }
        
        .totals .total-label {
            background: #f8f9fa;
            font-weight: bold;
            text-align: right;
        }
        
        .totals .total-final {
            background: #007bff;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }
        
        .conditions {
            margin-top: 30px;
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
        }
        
        .conditions h4 {
            color: #856404;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .conditions ul {
            color: #856404;
            font-size: 11px;
            margin-left: 20px;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
            padding-top: 15px;
        }
        
        .validity {
            text-align: center;
            margin: 20px 0;
            padding: 10px;
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <div class="company-logo">
                    LOGO IRM
                </div>
                <div class="company-details">
                    <h2>IRM MAQUINARIAS</h2>
                    <p><strong>RUC:</strong> 20123456789</p>
                    <p><strong>Dirección:</strong> Av. Industrial 123, Lima - Perú</p>
                    <p><strong>Teléfono:</strong> (01) 234-5678</p>
                    <p><strong>Email:</strong> ventas@irmmaquinarias.com</p>
                </div>
            </div>
            <div class="document-info">
                <h1>COTIZACIÓN</h1>
                <div class="document-number">{{ $venta->serie }}{{ $venta->numero }}</div>
                <p><strong>Fecha:</strong> {{ date('d/m/Y', strtotime($fecha)) }}</p>
                <p><strong>Válida hasta:</strong> {{ date('d/m/Y', strtotime($fecha . ' +30 days')) }}</p>
            </div>
        </div>

        <!-- Información del Cliente -->
        <div class="client-info">
            <h3>INFORMACIÓN DEL CLIENTE</h3>
            <div style="display: table; width: 100%;">
                <div style="display: table-cell; width: 50%;">
                    <p><strong>Cliente:</strong> {{ $cliente->nombre }}</p>
                    <p><strong>{{ strlen($cliente->numero_documento) == 8 ? 'DNI' : 'RUC' }}:</strong> {{ $cliente->numero_documento }}</p>
                </div>
                <div style="display: table-cell; width: 50%;">
                    <p><strong>Dirección:</strong> {{ $cliente->direccion ?: 'No especificada' }}</p>
                    <p><strong>Teléfono:</strong> {{ $cliente->telefono ?: 'No especificado' }}</p>
                </div>
            </div>
        </div>

        <!-- Validez de la cotización -->
        <div class="validity">
            Esta cotización es válida por 30 días calendario desde la fecha de emisión
        </div>

        <!-- Detalle de productos/servicios -->
        <table class="details-table">
            <thead>
                <tr>
                    <th style="width: 8%;">ITEM</th>
                    <th style="width: 40%;">DESCRIPCIÓN</th>
                    <th style="width: 8%;">CANT.</th>
                    <th style="width: 17%;">PRECIO UNIT. (PEN/USD)</th>
                    <th style="width: 8%;">DESC. %</th>
                    <th style="width: 19%;">IMPORTE (PEN/USD)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detalles as $index => $detalle)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td style="text-align: left; padding-left: 10px;">
                        <strong>{{ $detalle->producto->descripcion ?? 'Producto no encontrado' }}</strong>
                        @if($detalle->producto->codigo)
                            <br><small>Código: {{ $detalle->producto->codigo }}</small>
                        @endif
                        @if($detalle->producto->numero_parte)
                            <br><small>P/N: {{ $detalle->producto->numero_parte }}</small>
                        @endif
                    </td>
                    <td>{{ number_format($detalle->cantidad, 2) }}</td>
                    <td>
                        S/ {{ number_format($detalle->precio_unitario, 2) }}<br>
                        <small style="color: #666;">${{ number_format($detalle->precio_unitario / $tipoCambio, 2) }}</small>
                    </td>
                    <td>{{ $detalle->descuento_porcentaje ?? 0 }}%</td>
                    <td>
                        @php
                            $importe = $detalle->cantidad * $detalle->precio_unitario * (1 - ($detalle->descuento_porcentaje ?? 0)/100);
                        @endphp
                        S/ {{ number_format($importe, 2) }}<br>
                        <small style="color: #666;">${{ number_format($importe / $tipoCambio, 2) }}</small>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totales -->
        <div class="totals">
            <table>
                <tr>
                    <td class="total-label">SUBTOTAL:</td>
                    <td>
                        S/ {{ number_format($venta->subtotal, 2) }}<br>
                        <small style="color: #666;">${{ number_format($venta->subtotal / $tipoCambio, 2) }}</small>
                    </td>
                </tr>
                <tr>
                    <td class="total-label">IGV (18%):</td>
                    <td>
                        S/ {{ number_format($venta->igv, 2) }}<br>
                        <small style="color: #666;">${{ number_format($venta->igv / $tipoCambio, 2) }}</small>
                    </td>
                </tr>
                <tr class="total-final">
                    <td>TOTAL:</td>
                    <td>
                        S/ {{ number_format($venta->total, 2) }}<br>
                        <small style="color: #666;">${{ number_format($venta->total / $tipoCambio, 2) }}</small>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Condiciones comerciales -->
        <div class="conditions">
            <h4>CONDICIONES COMERCIALES</h4>
            <ul>
                <li><strong>Forma de Pago:</strong> 50% adelanto, 50% contra entrega</li>
                <li><strong>Tiempo de Entrega:</strong> 15 a 30 días hábiles según disponibilidad</li>
                <li><strong>Garantía:</strong> 12 meses por defectos de fabricación</li>
                <li><strong>Validez de Precios:</strong> 30 días calendario</li>
                <li><strong>Moneda:</strong> {{ $moneda->descripcion }}</li>
                <li><strong>Tipo de Cambio:</strong> S/ {{ number_format($tipoCambio, 2) }} por USD</li>
                <li><strong>Incluye:</strong> IGV, instalación básica y capacitación de uso</li>
                <li><strong>No Incluye:</strong> Flete, seguros, obras civiles</li>
            </ul>
        </div>

        <!-- Footer -->
        <div class="footer">
            @if($venta->vendedor)
            <p><strong>Ejecutivo de Ventas:</strong> {{ $venta->vendedor->nombre }}</p>
            @endif
            <p>¡Gracias por su confianza en IRM Maquinarias!</p>
            <p>Sistema de Gestión IRM Maquinarias - Generado el {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>