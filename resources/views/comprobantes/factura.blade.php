<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FACTURA - {{ $venta->serie }}{{ $venta->numero }}</title>
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
            border-bottom: 2px solid #28a745;
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
            border: 2px solid #28a745;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            font-size: 10px;
            color: #28a745;
            font-weight: bold;
        }
        
        .company-details h2 {
            color: #28a745;
            margin-bottom: 5px;
            font-size: 18px;
        }
        
        .document-info {
            display: table-cell;
            width: 35%;
            vertical-align: top;
            text-align: center;
            border: 2px solid #28a745;
            padding: 15px;
            background: #f8f9fa;
        }
        
        .document-info h1 {
            color: #28a745;
            margin-bottom: 10px;
            font-size: 20px;
        }
        
        .document-number {
            font-size: 16px;
            font-weight: bold;
            color: #28a745;
            margin-bottom: 10px;
        }
        
        .client-info {
            background: #f8f9fa;
            padding: 15px;
            margin: 20px 0;
            border-left: 4px solid #28a745;
        }
        
        .client-info h3 {
            color: #28a745;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .details-table th {
            background: #28a745;
            color: white;
            padding: 10px 8px;
            text-align: center;
            font-size: 11px;
            border: 1px solid #1e7e34;
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
            background: #28a745;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }
        
        .legal-info {
            margin-top: 30px;
            background: #e9f7ef;
            border: 1px solid #c3e6cb;
            padding: 15px;
            border-radius: 5px;
        }
        
        .legal-info h4 {
            color: #155724;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .legal-info p {
            color: #155724;
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
            border-left: 4px solid #28a745;
            font-weight: bold;
            color: #155724;
        }
        
        .tax-info {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 10px;
            margin: 15px 0;
            border-radius: 5px;
        }
        
        .tax-info h5 {
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
                <h1>FACTURA</h1>
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
                    <p><strong>RUC:</strong> {{ $cliente->numero_documento }}</p>
                </div>
                <div style="display: table-cell; width: 50%;">
                    <p><strong>Dirección:</strong> {{ $cliente->direccion ?: 'No especificada' }}</p>
                    <p><strong>Email:</strong> {{ $cliente->email ?: 'No especificado' }}</p>
                </div>
            </div>
        </div>

        <!-- Detalle de productos/servicios -->
        <table class="details-table">
            <thead>
                <tr>
                    <th style="width: 8%;">ITEM</th>
                    <th style="width: 45%;">DESCRIPCIÓN</th>
                    <th style="width: 8%;">CANT.</th>
                    <th style="width: 17%;">VALOR UNIT. (PEN/USD)</th>
                    <th style="width: 8%;">DESC. %</th>
                    <th style="width: 19%;">VALOR VENTA (PEN/USD)</th>
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
                            <br><small>Número de Parte: {{ $detalle->producto->numero_parte }}</small>
                        @endif
                        @if($detalle->producto->modelo)
                            <br><small>Modelo: {{ $detalle->producto->modelo }}</small>
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
                            $valorVenta = $detalle->cantidad * $detalle->precio_unitario * (1 - ($detalle->descuento_porcentaje ?? 0)/100);
                        @endphp
                        S/ {{ number_format($valorVenta, 2) }}<br>
                        <small style="color: #666;">${{ number_format($valorVenta / $tipoCambio, 2) }}</small>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Información tributaria -->
        <div class="tax-info">
            <h5>INFORMACIÓN TRIBUTARIA</h5>
            <p><strong>Tipo de Operación:</strong> Venta interna</p>
            <p><strong>Base Imponible:</strong> {{ $moneda->simbolo }} {{ number_format($venta->subtotal, 2) }}</p>
            <p><strong>IGV (18%):</strong> {{ $moneda->simbolo }} {{ number_format($venta->igv, 2) }}</p>
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

        <!-- Información legal -->
        <div class="legal-info">
            <h4>INFORMACIÓN LEGAL</h4>
            <p>• Documento electrónico que cumple con las disposiciones de SUNAT</p>
            <p>• Autorizada mediante Resolución de Intendencia Nacional</p>
            <p>• Los datos del comprador han sido consignados bajo su responsabilidad</p>
            <p>• Para ser considerado crédito fiscal debe cumplir con los requisitos del Art. 18° del T.U.O del IGV</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            @if($venta->vendedor)
            <p><strong>Vendedor:</strong> {{ $venta->vendedor->nombre }}</p>
            @endif
            <p>Estado del Comprobante: {{ $venta->xml_estado }}</p>
            <p>Sistema de Gestión IRM Maquinarias - Generado el {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>