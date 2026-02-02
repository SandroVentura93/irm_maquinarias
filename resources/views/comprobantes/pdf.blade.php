<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tipoComprobante->descripcion ?? 'COMPROBANTE' }} - {{ $venta->numero }}</title>
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
        body.compact-mode { font-size: 11px; line-height: 1.25; }
        body.compact-mode p,
        body.compact-mode li { margin: 2px 0; }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .container.compact { padding: 14px; }
        
        .header {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .header.compact { margin-bottom: 14px; }
        
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
        .document-type.compact { padding: 10px; margin-bottom: 8px; }
        
        .document-type h2 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        .document-type.compact h2 { font-size: 16px; }
        
        .document-number {
            font-size: 16px;
            font-weight: bold;
        }
        
        .document-details {
            background: #f8f9fa;
            padding: 10px;
            border: 1px solid #ddd;
        }
        .document-details.compact { padding: 8px; }
        
        .client-section {
            margin-bottom: 20px;
        }
        .client-section.compact { margin-bottom: 14px; }
        
        .section-title {
            background: #e9ecef;
            padding: 8px 10px;
            font-weight: bold;
            border: 1px solid #ddd;
            border-bottom: none;
        }
        .section-title.compact { padding: 6px 8px; font-size: 11px; }
        
        .client-info {
            border: 1px solid #ddd;
            padding: 10px;
            background: white;
        }
        .client-info.compact { padding: 8px; }
        .client-info.compact .client-row { margin-bottom: 3px; }
        
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
        .items-table.compact { margin-bottom: 14px; }
        
        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .items-table.compact th,
        .items-table.compact td { padding: 6px; font-size: 11px; }
        
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
        .totals-left.compact { padding-right: 12px; }
        
        .totals-right {
            display: table-cell;
            width: 40%;
            vertical-align: top;
        }
        .totals-right.compact { padding-left: 6px; }
        
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        .totals-table.compact td { padding: 6px 10px; font-size: 11px; }
        
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
        .amount-words.compact { margin-top: 6px; padding: 8px; font-size: 11px; }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .footer.compact { margin-top: 20px; }
        
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
        .qr-code.compact { width: 100px; height: 100px; font-size: 9px; }
        
        @media print {
            body { print-color-adjust: exact; }
            .container { padding: 0; }
        }
    </style>
    </head>
    <body class="{{ $singlePage ? 'compact-mode' : '' }}">
        <div class="container{{ $singlePage ? ' compact' : '' }}">
        <!-- Header -->
        <div class="header{{ $singlePage ? ' compact' : '' }}">
            <div class="company-info">
                @include('comprobantes.partials.logo')
                <div class="company-name">
                    IRM Maquinarias S.R.L.
                </div>
                <div class="company-details">
                    <strong>RUC:</strong> 20570639553<br>
                    <strong>Dirección:</strong> AV. ATAHUALPA NRO. 725, CAJAMARCA<br>
                    <strong>Teléfono:</strong> 976390506 - 974179198<br>
                    <strong>Email:</strong> ventas@irmmaquinarias.com
                </div>
            </div>
            
            <div class="document-info">
                <div class="document-type{{ $singlePage ? ' compact' : '' }}">
                    <h2>{{ strtoupper($tipoComprobante->descripcion ?? 'COMPROBANTE') }}</h2>
                    <div class="document-number">{{ $venta->numero }}</div>
                </div>
                <div class="document-details{{ $singlePage ? ' compact' : '' }}">
                    <strong>Fecha de Emisión:</strong><br>
                    {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i:s') }}<br><br>
                    <strong>Moneda:</strong><br>
                    {{ $moneda->descripcion ?: 'Soles' }} <span style="display:inline-block; padding:2px 6px; background:#2c5aa0; color:white; border-radius:4px; font-size:10px;">{{ $moneda->codigo_iso ?? 'PEN' }}</span>
                </div>
            </div>
        </div>

        @php
            $codigoIso = $moneda->codigo_iso ?? 'PEN';
            $simbolo = $codigoIso === 'USD' ? '$' : 'S/';
        @endphp

        <!-- Cliente -->
        <div class="client-section{{ $singlePage ? ' compact' : '' }}">
            <div class="section-title{{ $singlePage ? ' compact' : '' }}">DATOS DEL CLIENTE</div>
            <div class="client-info{{ $singlePage ? ' compact' : '' }}">
                <div class="client-row">
                    <div class="client-label">Razón Social:</div>
                    <div class="client-value">{{ $venta->cliente->nombre }}</div>
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
        <table class="items-table{{ $singlePage ? ' compact' : '' }}">
            <thead>
                <tr>
                    <th width="8%">Item</th>
                    <th width="12%">Código</th>
                    <th width="30%">Descripción</th>
                    <th width="8%">Cant.</th>
                    <th width="15%">P. Unit.</th>
                    <th width="7%">Desc. %</th>
                    <th width="10%">P. Final</th>
                    <th width="10%">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detalles as $index => $detalle)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $detalle->producto->codigo }}</td>
                    <td>{{ $detalle->producto->descripcion }}</td>
                    <td class="text-center">{{ number_format($detalle->cantidad, 0) }}</td>
                    <td class="text-right">
                        {{ $simbolo }} {{ number_format($detalle->precio_unitario, 2) }}
                    </td>
                    <td class="text-center">{{ number_format($detalle->descuento_porcentaje, 1) }}%</td>
                    <td class="text-right">{{ $simbolo }} {{ number_format($detalle->precio_final, 2) }}</td>
                    <td class="text-right">
                        {{ $simbolo }} {{ number_format($detalle->total, 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totales -->
        <div class="totals-section{{ $singlePage ? ' compact' : '' }}">
            <div class="totals-left{{ $singlePage ? ' compact' : '' }}">
                <div class="amount-words{{ $singlePage ? ' compact' : '' }}">
                    <strong>SON:</strong> {{ strtoupper($totalEnLetras) }}
                </div>
                
                @if($venta->qr_hash)
                <div style="margin-top: 20px;">
                    <div class="qr-code{{ $singlePage ? ' compact' : '' }}">
                        CÓDIGO QR<br>
                        (Pendiente)
                    </div>
                </div>
                @endif
            </div>
            
            <div class="totals-right{{ $singlePage ? ' compact' : '' }}">
                <table class="totals-table{{ $singlePage ? ' compact' : '' }}">
                    <tr>
                        <td class="label">Sub Total:</td>
                        <td class="value">{{ $simbolo }} {{ number_format($venta->subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label">IGV (18%):</td>
                        <td class="value">{{ $simbolo }} {{ number_format(($venta->total - $venta->subtotal), 2) }}</td>
                    </tr>
                    @if($descuentoTotal > 0)
                    <tr>
                        <td class="label">Descuento Total:</td>
                        <td class="value">- {{ $simbolo }} {{ number_format($descuentoTotal, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="total-final">
                        <td class="label">TOTAL:</td>
                        <td class="value">{{ $simbolo }} {{ number_format($venta->total, 2) }}</td>
                    </tr>
                </table>
                <p style="margin-top: 10px; font-size: 10px; color: #666;">
                    <strong>Tipo de Cambio (referencial):</strong> S/ {{ number_format($venta->tipo_cambio ?? $tipoCambio ?? 0, 2) }} por USD
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer{{ $singlePage ? ' compact' : '' }}">
            @if($venta->vendedor)
            <p><strong>Vendedor:</strong> {{ $venta->vendedor->nombre }}</p>
            @endif
            <p>Estado del Comprobante: {{ $venta->xml_estado }}</p>
            @if(strtoupper($venta->xml_estado) === 'PENDIENTE')
                <p style="color: #d9534f; font-weight: bold;">Saldo pendiente: {{ $simbolo }} {{ number_format($venta->saldo, 2) }}</p>
            @endif
            <p>Sistema de Gestión IRM Maquinarias S.R.L. - Generado el {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>