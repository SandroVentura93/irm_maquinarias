<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tipoComprobante->descripcion ?? 'COMPROBANTE' }} - {{ $venta->serie }}-{{ $venta->numero }}</title>
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
        }
        
        .company-info {
            display: table-cell;
            width: 60%;
            vertical-align: top;
        }
        
        .company-logo {
            width: 120px;
            height: 80px;
            background: #f0f0f0;
            border: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            font-size: 10px;
            color: #666;
        }
        
        .company-name {
            font-size: 16px;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 5px;
        }
        
        .company-details {
            font-size: 10px;
            color: #666;
            line-height: 1.3;
        }
        
        .document-info {
            display: table-cell;
            width: 40%;
            vertical-align: top;
            text-align: right;
        }
        
        .document-type {
            background: #2c5aa0;
            color: white;
            padding: 15px;
            text-align: center;
            margin-bottom: 10px;
        }
        
        .document-type h2 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        
        .document-number {
            font-size: 16px;
            font-weight: bold;
        }
        
        .document-details {
            background: #f8f9fa;
            padding: 10px;
            border: 1px solid #ddd;
        }
        
        .client-section {
            margin-bottom: 20px;
        }
        
        .section-title {
            background: #e9ecef;
            padding: 8px 10px;
            font-weight: bold;
            border: 1px solid #ddd;
            border-bottom: none;
        }
        
        .client-info {
            border: 1px solid #ddd;
            padding: 10px;
            background: white;
        }
        
        .client-row {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }
        
        .client-label {
            display: table-cell;
            width: 25%;
            font-weight: bold;
        }
        
        .client-value {
            display: table-cell;
            width: 75%;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        .items-table th {
            background: #f8f9fa;
            font-weight: bold;
            text-align: center;
        }
        
        .items-table .text-center {
            text-align: center;
        }
        
        .items-table .text-right {
            text-align: right;
        }
        
        .totals-section {
            display: table;
            width: 100%;
        }
        
        .totals-left {
            display: table-cell;
            width: 60%;
            vertical-align: top;
        }
        
        .totals-right {
            display: table-cell;
            width: 40%;
            vertical-align: top;
        }
        
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .totals-table td {
            padding: 5px 10px;
            border: 1px solid #ddd;
        }
        
        .totals-table .label {
            background: #f8f9fa;
            font-weight: bold;
            text-align: right;
        }
        
        .totals-table .value {
            text-align: right;
            font-weight: bold;
        }
        
        .total-final {
            background: #2c5aa0 !important;
            color: white !important;
        }
        
        .amount-words {
            margin-top: 10px;
            padding: 10px;
            background: #f8f9fa;
            border: 1px solid #ddd;
            font-style: italic;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        
        .qr-code {
            width: 120px;
            height: 120px;
            border: 1px solid #ddd;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #666;
        }
        
        @media print {
            body { print-color-adjust: exact; }
            .container { padding: 0; }
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
                <div class="company-name">
                    IRM MAQUINARIAS S.A.C.
                </div>
                <div class="company-details">
                    <strong>RUC:</strong> 20123456789<br>
                    <strong>Dirección:</strong> Av. Industrial 123, Lima - Perú<br>
                    <strong>Teléfono:</strong> (01) 234-5678<br>
                    <strong>Email:</strong> ventas@irmmaquinarias.com
                </div>
            </div>
            
            <div class="document-info">
                <div class="document-type">
                    <h2>{{ strtoupper($tipoComprobante->descripcion ?? 'COMPROBANTE') }}</h2>
                    <div class="document-number">{{ $venta->serie }}-{{ $venta->numero }}</div>
                </div>
                <div class="document-details">
                    <strong>Fecha de Emisión:</strong><br>
                    {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i:s') }}<br><br>
                    <strong>Moneda:</strong><br>
                    {{ $moneda->descripcion ?: 'Soles' }}
                </div>
            </div>
        </div>

        <!-- Cliente -->
        <div class="client-section">
            <div class="section-title">DATOS DEL CLIENTE</div>
            <div class="client-info">
                <div class="client-row">
                    <div class="client-label">Razón Social:</div>
                    <div class="client-value">{{ $venta->cliente->razon_social ?: $venta->cliente->nombre }}</div>
                </div>
                <div class="client-row">
                    <div class="client-label">{{ $venta->cliente->tipo_documento }}:</div>
                    <div class="client-value">{{ $venta->cliente->numero_documento }}</div>
                </div>
                @if($venta->cliente->direccion)
                <div class="client-row">
                    <div class="client-label">Dirección:</div>
                    <div class="client-value">{{ $venta->cliente->direccion }}</div>
                </div>
                @endif
                @if($venta->cliente->telefono)
                <div class="client-row">
                    <div class="client-label">Teléfono:</div>
                    <div class="client-value">{{ $venta->cliente->telefono }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Detalle de Productos -->
        <table class="items-table">
            <thead>
                <tr>
                    <th width="8%">Item</th>
                    <th width="12%">Código</th>
                    <th width="35%">Descripción</th>
                    <th width="8%">Cant.</th>
                    <th width="12%">P. Unit.</th>
                    <th width="8%">Desc. %</th>
                    <th width="12%">P. Final</th>
                    <th width="12%">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detalles as $index => $detalle)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $detalle->producto->codigo }}</td>
                    <td>{{ $detalle->producto->descripcion }}</td>
                    <td class="text-center">{{ number_format($detalle->cantidad, 0) }}</td>
                    <td class="text-right">S/ {{ number_format($detalle->precio_unitario, 2) }}</td>
                    <td class="text-center">{{ number_format($detalle->descuento_porcentaje, 1) }}%</td>
                    <td class="text-right">S/ {{ number_format($detalle->precio_final, 2) }}</td>
                    <td class="text-right">S/ {{ number_format($detalle->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totales -->
        <div class="totals-section">
            <div class="totals-left">
                <div class="amount-words">
                    <strong>Son:</strong> {{ $totalEnLetras }}
                </div>
                
                @if($venta->qr_hash)
                <div style="margin-top: 20px;">
                    <div class="qr-code">
                        CÓDIGO QR<br>
                        (Pendiente)
                    </div>
                </div>
                @endif
            </div>
            
            <div class="totals-right">
                <table class="totals-table">
                    <tr>
                        <td class="label">Sub Total:</td>
                        <td class="value">S/ {{ number_format($venta->subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label">IGV (18%):</td>
                        <td class="value">S/ {{ number_format($venta->igv, 2) }}</td>
                    </tr>
                    @if($descuentoTotal > 0)
                    <tr>
                        <td class="label">Descuento Total:</td>
                        <td class="value">- S/ {{ number_format($descuentoTotal, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="total-final">
                        <td class="label">TOTAL:</td>
                        <td class="value">S/ {{ number_format($venta->total, 2) }}</td>
                    </tr>
                </table>
            </div>
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