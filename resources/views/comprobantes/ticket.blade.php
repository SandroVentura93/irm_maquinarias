<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TICKET DE VENTA - {{ $venta->numero }}</title>
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
            border-bottom: 2px solid #4309c8ff;
            padding-bottom: 15px;
        }
        .header.compact { margin-bottom: 14px; padding-bottom: 10px; }
        
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
            color: #4309c8ff;
            margin-bottom: 5px;
            font-size: 18px;
        }
        .compact-mode .company-details h2 { font-size: 16px; margin-bottom: 4px; }
        .compact-mode .company-details p { font-size: 11px; }
        
        .document-info {
            display: table-cell;
            width: 35%;
            vertical-align: top;
            text-align: center;
            border: 2px solid #4309c8ff;
            padding: 15px;
            background: #f8f9fa;
        }
        .document-info.compact { padding: 10px; }
        
        .document-info h1 {
            color: #4309c8ff;
            margin-bottom: 10px;
            font-size: 20px;
        }
        .document-info.compact h1 { font-size: 16px; margin-bottom: 6px; }
        
        .document-number {
            font-size: 16px;
            font-weight: bold;
            color: rgba(59, 124, 228, 1);
            margin-bottom: 10px;
        }
        .document-info.compact .document-number { font-size: 14px; margin-bottom: 6px; }
        
        .client-info {
            background: #f8f9fa;
            padding: 15px;
            margin: 20px 0;
            border-left: 4px solid rgba(59, 124, 228, 1);
        }
        .client-info.compact { padding: 10px; margin: 14px 0; }
        .client-info.compact h3 { margin-bottom: 6px; }
        .client-info.compact p { margin: 2px 0; }
        
        .client-info h3 {
            color: rgba(59, 124, 228, 1);
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .details-table.compact { margin: 14px 0; }
        
        .details-table th {
            background: rgba(59, 124, 228, 1);
            color: white;
            padding: 10px 8px;
            text-align: center;
            font-size: 11px;
            border: 1px solid #1e7e34;
        }
        .details-table.compact th { padding: 8px 6px; font-size: 10px; }
        
        .details-table td {
            padding: 8px;
            border: 1px solid #dee2e6;
            text-align: center;
            font-size: 11px;
        }
        .details-table.compact td { padding: 6px 5px; font-size: 10px; }
        
        .details-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .totals {
            margin-top: 20px;
            text-align: right;
        }
        .totals.compact { margin-top: 14px; }
        
        .totals table {
            margin-left: auto;
            border-collapse: collapse;
            min-width: 300px;
        }
        
        .totals td {
            padding: 8px 15px;
            border: 1px solid #dee2e6;
        }
        .totals.compact td { padding: 6px 10px; font-size: 11px; }
        
        .totals .total-label {
            background: #f8f9fa;
            font-weight: bold;
            text-align: right;
        }
        
        .totals .total-final {
            background: rgba(59, 124, 228, 1);
            color: white;
            font-weight: bold;
            font-size: 14px;
        }
        .totals.compact .total-final { font-size: 12px; }
        
        .legal-info {
            margin-top: 30px;
            background: #e9f7ef;
            border: 1px solid #c3e6cb;
            padding: 15px;
            border-radius: 5px;
        }
        .legal-info.compact { margin-top: 18px; padding: 10px; }
        .legal-info.compact p { margin-bottom: 3px; }
        
        .legal-info h4 {
            color: #155724;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .legal-info.compact h4 { font-size: 12px; margin-bottom: 6px; }
        
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
        .footer.compact { margin-top: 20px; padding-top: 10px; }
        
        .amount-words {
            background: #f8f9fa;
            padding: 10px;
            margin: 15px 0;
            border-left: 4px solid rgba(59, 124, 228, 1);
            font-weight: bold;
            color: #155724;
        }
        .amount-words.compact { padding: 8px; margin: 10px 0; font-size: 11px; }

        .consumer-notice.compact { margin: 12px 0; }
        .consumer-notice.compact h5 { margin-bottom: 4px; }
        .consumer-notice.compact p { margin: 2px 0 !important; }

        .payment-info.compact { margin-top: 14px; }
        .payment-info.compact h4 { margin-bottom: 6px; }
        .payment-info.compact p { margin: 2px 0; }
        
        .tax-info {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 10px;
            margin: 15px 0;
            border-radius: 5px;
        }
        .tax-info.compact { padding: 8px; margin: 10px 0; font-size: 11px; }
        
        .tax-info h5 {
            color: #856404;
            margin-bottom: 5px;
            font-size: 12px;
        }
    </style>
    </head>
    <body class="{{ $singlePage ? 'compact-mode' : '' }}">
        <div class="container{{ $singlePage ? ' compact' : '' }}">
        <!-- Header -->
            <div class="header{{ $singlePage ? ' compact' : '' }}">
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
            <div class="document-info{{ $singlePage ? ' compact' : '' }}">
                <h1>TICKET DE VENTA</h1>
                <div class="document-number">{{ $venta->numero }}</div>
                <p><strong>Fecha:</strong> {{ date('d/m/Y', strtotime($venta->fecha)) }}</p>
                <p><strong>Moneda:</strong> {{ $moneda->descripcion }} <span style="display:inline-block; padding:2px 6px; background:rgba(59, 124, 228, 1); color:white; border-radius:4px; font-size:10px;">{{ $moneda->codigo_iso ?? 'PEN' }}</span></p>
            </div>
        </div>

        <!-- Información del Cliente -->
        <div class="client-info{{ $singlePage ? ' compact' : '' }}">
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

        @php
            $codigoIso = $moneda->codigo_iso ?? 'PEN';
            $simbolo = $codigoIso === 'USD' ? '$' : 'S/';
        @endphp

        <!-- Detalle estandarizado y totales -->
        @include('comprobantes.partials.detalle_estandar')

        <!-- Aviso al consumidor -->
        <div class="consumer-notice{{ $singlePage ? ' compact' : '' }}">
            <h5>INFORMACIÓN PARA EL CONSUMIDOR</h5>
            <p style="color: #856404; font-size: 11px;">
                Para consultas o reclamos acuda a nuestras oficinas o llame al teléfono indicado.
                Libro de Reclamaciones disponible en nuestras instalaciones.
            </p>
        </div>

        <!-- Importe en palabras -->
        <div class="amount-words{{ $singlePage ? ' compact' : '' }}">
            <strong>SON:</strong> {{ strtoupper($totalEnLetras) }}
        </div>

        <!-- Totales integrados en el bloque estandarizado -->

        <!-- Información de pago -->
        <div class="payment-info{{ $singlePage ? ' compact' : '' }}">
            <h4>INFORMACIÓN DE PAGO</h4>
            <p>• Este ticket no tiene valor tributario para efectos del Impuesto General a las Ventas</p>
            <p>• Conserve este documento para cualquier reclamo posterior</p>
            <p>• En caso de devolución, deberá presentar este comprobante</p>
            <p>• Para garantías, conserve este documento y la factura de compra original</p>
        </div>

        <!-- Footer -->
        <div class="footer{{ $singlePage ? ' compact' : '' }}">
            @if($venta->vendedor)
            <p><strong>Atendido por:</strong> {{ $venta->vendedor->nombre }}</p>
            @endif
            <p>¡Gracias por su compra!</p>
                <p>Sistema de Gestión IRM Maquinarias S.R.L. - Generado el {{ now()->format('d/m/Y H:i:s') }}</p>
                @if(strtoupper($venta->xml_estado) === 'PENDIENTE')
                    <p style="color: #d9534f; font-weight: bold;">Saldo pendiente: {{ $simbolo }} {{ number_format($venta->saldo, 2) }}</p>
                @endif
        </div>
    </div>
</body>
</html>