<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOLETA DE VENTA - {{ $venta->serie }}{{ $venta->numero }}</title>
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
            border-bottom: 2px solid #17a2b8;
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
            border: 2px solid #17a2b8;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            font-size: 10px;
            color: #17a2b8;
            font-weight: bold;
        }
        
        .company-details h2 {
            color: #17a2b8;
            margin-bottom: 5px;
            font-size: 18px;
        }
        
        .document-info {
            display: table-cell;
            width: 35%;
            vertical-align: top;
            text-align: center;
            border: 2px solid #17a2b8;
            padding: 15px;
            background: #f8f9fa;
        }
        
        .document-info h1 {
            color: #17a2b8;
            margin-bottom: 10px;
            font-size: 18px;
        }
        
        .document-number {
            font-size: 16px;
            font-weight: bold;
            color: #17a2b8;
            margin-bottom: 10px;
        }
        
        .client-info {
            background: #f8f9fa;
            padding: 15px;
            margin: 20px 0;
            border-left: 4px solid #17a2b8;
        }
        
        .client-info h3 {
            color: #17a2b8;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .details-table th {
            background: #17a2b8;
            color: white;
            padding: 10px 8px;
            text-align: center;
            font-size: 11px;
            border: 1px solid #138496;
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
            background: #17a2b8;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }
        
        .payment-info {
            margin-top: 30px;
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            padding: 15px;
            border-radius: 5px;
        }
        
        .payment-info h4 {
            color: #0c5460;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .payment-info p {
            color: #0c5460;
            font-size: 11px;
            margin-bottom: 5px;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
            padding-top: 15px;
        }
        
        .amount-words {
            background: #f8f9fa;
            padding: 10px;
            margin: 15px 0;
            border-left: 4px solid #17a2b8;
            font-weight: bold;
            color: #0c5460;
        }
        
        .consumer-notice {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 10px;
            margin: 15px 0;
            border-radius: 5px;
            text-align: center;
        }
        
        .consumer-notice h5 {
            color: #856404;
            margin-bottom: 5px;
            font-size: 12px;
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
                <h1>BOLETA DE VENTA</h1>
                <div class="document-number">{{ $venta->serie }}{{ $venta->numero }}</div>
                <p><strong>Fecha:</strong> {{ date('d/m/Y', strtotime($fecha)) }}</p>
                <p><strong>Moneda:</strong> {{ $moneda->descripcion }}</p>
            </div>
        </div>

        <!-- Información del Cliente -->
        <div class="client-info">
            <h3>CLIENTE</h3>
            <div style="display: table; width: 100%;">
                <div style="display: table-cell; width: 50%;">
                    <p><strong>Nombre:</strong> {{ $cliente->nombre }}</p>
                    <p><strong>{{ strlen($cliente->numero_documento) == 8 ? 'DNI' : 'RUC' }}:</strong> {{ $cliente->numero_documento }}</p>
                </div>
                <div style="display: table-cell; width: 50%;">
                    <p><strong>Dirección:</strong> {{ $cliente->direccion ?: 'No especificada' }}</p>
                    <p><strong>Teléfono:</strong> {{ $cliente->telefono ?: 'No especificado' }}</p>
                </div>
            </div>
        </div>

        <!-- Detalle de productos/servicios -->
        <table class="details-table">
            <thead>
                <tr>
                    <th style="width: 8%;">ITEM</th>
                    <th style="width: 45%;">DESCRIPCIÓN</th>
                    <th style="width: 10%;">CANTIDAD</th>
                    <th style="width: 18%;">PRECIO UNIT. (PEN/USD)</th>
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
                        @if($detalle->descuento_porcentaje > 0)
                            <br><small class="text-success">Descuento: {{ $detalle->descuento_porcentaje }}%</small>
                        @endif
                    </td>
                    <td>{{ number_format($detalle->cantidad, 2) }}</td>
                    <td>
                        S/ {{ number_format($detalle->precio_unitario, 2) }}<br>
                        <small style="color: #666;">${{ number_format($detalle->precio_unitario / $tipoCambio, 2) }}</small>
                    </td>
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

        <!-- Aviso al consumidor -->
        <div class="consumer-notice">
            <h5>INFORMACIÓN PARA EL CONSUMIDOR</h5>
            <p style="color: #856404; font-size: 11px;">
                Para consultas o reclamos acuda a nuestras oficinas o llame al teléfono indicado.
                Libro de Reclamaciones disponible en nuestras instalaciones.
            </p>
        </div>

        <!-- Importe en palabras -->
        <div class="amount-words">
            <strong>SON:</strong> {{ strtoupper($totalEnLetras) }}
        </div>

        <!-- Totales -->
        <div class="totals">
            <table>
                <tr>
                    <td class="total-label">SUB TOTAL:</td>
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
                @if($descuentoTotal > 0)
                <tr>
                    <td class="total-label">DESCUENTO:</td>
                    <td>
                        S/ {{ number_format($descuentoTotal, 2) }}<br>
                        <small style="color: #666;">${{ number_format($descuentoTotal / $tipoCambio, 2) }}</small>
                    </td>
                </tr>
                @endif
                <tr class="total-final">
                    <td>TOTAL A PAGAR:</td>
                    <td>
                        S/ {{ number_format($venta->total, 2) }}<br>
                        <small style="color: #666;">${{ number_format($venta->total / $tipoCambio, 2) }}</small>
                    </td>
                </tr>
            </table>
            <p style="margin-top: 10px; font-size: 10px; color: #666;">
                <strong>Tipo de Cambio:</strong> S/ {{ number_format($tipoCambio, 2) }} por USD
            </p>
        </div>

        <!-- Información de pago -->
        <div class="payment-info">
            <h4>INFORMACIÓN DE PAGO</h4>
            <p>• Esta boleta no tiene valor tributario para efectos del Impuesto General a las Ventas</p>
            <p>• Conserve este documento para cualquier reclamo posterior</p>
            <p>• En caso de devolución, deberá presentar este comprobante</p>
            <p>• Para garantías, conserve este documento y la factura de compra original</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            @if($venta->vendedor)
            <p><strong>Atendido por:</strong> {{ $venta->vendedor->nombre }}</p>
            @endif
            <p>¡Gracias por su compra!</p>
            <p>Sistema de Gestión IRM Maquinarias - Generado el {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>