<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FACTURA - {{ $venta->numero }}</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }
        
        .company-logo img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
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
                @include('comprobantes.partials.logo')
                <div class="company-details">
                    <h2>{{ $empresa['razon_social'] ?? 'IRM Maquinarias S.R.L.' }}</h2>
                    <p><strong>RUC:</strong> {{ $empresa['ruc'] ?? '20570639553' }}</p>
                    <p><strong>Dirección:</strong> {{ $empresa['direccion'] ?? 'AV. ATAHUALPA NRO. 725, CAJAMARCA' }}</p>
                    <p><strong>Teléfono:</strong> {{ $empresa['telefono'] ?? '976390506 - 974179198' }}</p>
                    <p><strong>Email:</strong> {{ $empresa['email'] ?? 'ventas@irmmaquinarias.com' }}</p>
                </div>
            </div>
            <div class="document-info">
                <h1>FACTURA</h1>
                <!-- <div class="document-number">{{ $venta->serie }}-{{ $venta->numero }}</div> -->
                <div class="document-number">{{ $venta->numero }}</div>
                <p><strong>Fecha:</strong> {{ date('d/m/Y', strtotime($venta->fecha)) }}</p>
                <p><strong>Moneda:</strong> {{ $moneda->descripcion }} <span style="display:inline-block; padding:2px 6px; background:#28a745; color:white; border-radius:4px; font-size:10px;">{{ $moneda->codigo_iso ?? 'PEN' }}</span></p>
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

        @php
            $codigoIso = $moneda->codigo_iso ?? 'PEN';
            $simbolo = $codigoIso === 'USD' ? '$' : 'S/';
        @endphp

        <!-- Detalle estandarizado y totales -->
        @include('comprobantes.partials.detalle_estandar')

        <!-- Importe en palabras -->
        <div class="amount-words">
            <strong>SON:</strong> {{ strtoupper($totalEnLetras) }}
        </div>

        <!-- Totales integrados en el bloque estandarizado -->

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
            @if(strtoupper($venta->xml_estado) === 'PENDIENTE')
                <p style="color: #d9534f; font-weight: bold;">Saldo pendiente: {{ $simbolo }} {{ number_format($venta->saldo, 2) }}</p>
            @endif
            <p>Sistema de Gestión IRM Maquinarias S.R.L. - Generado el {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>