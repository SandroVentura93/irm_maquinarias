<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COTIZACIÓN - {{ $venta->numero }}</title>
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
            border-bottom: 2px solid #007bff;
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
        .compact-mode .company-details h2 { font-size: 16px; margin-bottom: 4px; }
        .compact-mode .company-details p { font-size: 11px; }
        
        .document-info {
            display: table-cell;
            width: 35%;
            vertical-align: top;
            text-align: center;
            border: 2px solid #007bff;
            padding: 15px;
            background: #f8f9fa;
        }
        .document-info.compact { padding: 10px; }
        
        .document-info h1 {
            color: #007bff;
            margin-bottom: 10px;
            font-size: 20px;
        }
        .document-info.compact h1 { font-size: 16px; margin-bottom: 6px; }
        
        .document-number {
            font-size: 16px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        .document-info.compact .document-number { font-size: 14px; margin-bottom: 6px; }
        
        .client-info {
            background: #f8f9fa;
            padding: 15px;
            margin: 20px 0;
            border-left: 4px solid #007bff;
        }
            .client-info.compact { padding: 10px; margin: 14px 0; }
            .client-info.compact h3 { margin-bottom: 6px; }
            .client-info.compact p { margin: 2px 0; }
        
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
        .details-table.compact { margin: 14px 0; }
        
        .details-table th {
            background: #007bff;
            color: white;
            padding: 10px 8px;
            text-align: center;
            font-size: 11px;
            border: 1px solid #0056b3;
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
            background: #007bff;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }
        .totals.compact .total-final { font-size: 12px; }
        
        .conditions {
            margin-top: 30px;
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
        }
        .conditions.compact { margin-top: 18px; padding: 10px; font-size: 11px; }
        
        .conditions h4 {
            color: #856404;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .conditions.compact h4 { font-size: 12px; margin-bottom: 6px; }
        
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
        .footer.compact { margin-top: 20px; padding-top: 10px; }
        
        .validity {
            text-align: center;
            margin: 20px 0;
            padding: 10px;
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            font-weight: bold;
        }
            .validity.compact { margin: 12px 0; padding: 8px; }

        .amount-words {
            margin-top: 20px;
            padding: 12px 15px;
            background: #eef5ff;
            border: 1px solid #cfe2ff;
            border-left: 4px solid #2c5aa0;
        }
        .amount-words h4 {
            color: #2c5aa0;
            margin-bottom: 6px;
            font-size: 13px;
        }
        .amount-words p {
            font-weight: bold;
            font-size: 12px;
        }
        .amount-words.compact h4 { font-size: 12px; margin-bottom: 3px; }
        .amount-words.compact p {
            font-size: 10px;
            white-space: nowrap;
            margin: 0;
            line-height: 1.1;
        }

        /* Bank data styles */
        .bank-data { margin-top: 25px; }
        .bank-data.compact { margin-top: 16px; }
        .bank-data h4 { color: #2c5aa0; margin-bottom: 8px; font-size: 14px; }
        .bank-data.compact h4 { font-size: 12px; margin-bottom: 6px; }
        .bank-table { width: 100%; border-collapse: collapse; }
        .bank-table th { background: #2c5aa0; color: #fff; padding: 8px; border: 1px solid #1f4173; font-size: 11px; text-align: center; }
        .bank-table td { padding: 8px; border: 1px solid #dee2e6; font-size: 11px; text-align: center; }
        .bank-table.compact th {
            padding: 5px 3px;
            font-size: 10px;
        }
        .bank-table.compact td {
            padding: 4px 3px;
            font-size: 9.5px;
            white-space: nowrap;
        }
        .bank-logo { width: 60px; height: 20px; object-fit: contain; }
        .bank-badge { display:inline-block; padding:2px 6px; background:#2c5aa0; color:#fff; border-radius:4px; font-size:10px; }
    </style>
</head>
<body class="{{ $singlePage ? 'compact-mode' : '' }}">
    <div class="container{{ $singlePage ? ' compact' : '' }}">
        <!-- Header -->
        <div class="header{{ $singlePage ? ' compact' : '' }}">
            <div class="company-info">
                @include('comprobantes.partials.logo')
                <div class="company-details">
                    <h2>IRM Maquinarias S.R.L.</h2>
                    <p><strong>RUC:</strong> 20570639553</p>
                    <p><strong>Dirección:</strong> AV. ATAHUALPA NRO. 725, CAJAMARCA</p>
                    <p><strong>Teléfono:</strong> 976390506 - 974179198</p>
                    <p><strong>Email:</strong> ventas@irmmaquinarias.com</p>
                </div>
            </div>
            <div class="document-info{{ $singlePage ? ' compact' : '' }}">
                <h1>COTIZACIÓN</h1>
                <div class="document-number">{{ $venta->numero }}</div>
                <p><strong>Fecha:</strong> {{ date('d/m/Y', strtotime($venta->fecha)) }}</p>
                <p><strong>Válida hasta:</strong> {{ date('d/m/Y', strtotime($venta->fecha . ' +30 days')) }}</p>
            </div>
        </div>

        <!-- Información del Cliente -->
        <div class="client-info{{ $singlePage ? ' compact' : '' }}">
            <h3>INFORMACIÓN DEL CLIENTE</h3>
            <div style="display: table; width: 100%;">
                <div style="display: table-cell; width: 50%;">
                    <p><strong>Cliente:</strong> {{ optional($venta->cliente)->nombre ?? 'Sin cliente' }}</p>
                    @php $doc = optional($venta->cliente)->numero_documento; @endphp
                    <p><strong>{{ strlen($doc ?? '') == 8 ? 'DNI' : 'RUC' }}:</strong> {{ $doc ?? 'N/A' }}</p>
                </div>
                <div style="display: table-cell; width: 50%;">
                    <p><strong>Dirección:</strong> {{ optional($venta->cliente)->direccion ?? 'No especificada' }}</p>
                    <p><strong>Teléfono:</strong> {{ optional($venta->cliente)->telefono ?? 'No especificado' }}</p>
                </div>
            </div>
        </div>

        <!-- Validez de la cotización -->
        <div class="validity{{ $singlePage ? ' compact' : '' }}">
            Esta cotización es válida por 30 días calendario desde la fecha de emisión
        </div>

        @php
            // Determinar moneda y símbolo para toda la vista
            $codigoIso = isset($datos['moneda']['iso']) ? $datos['moneda']['iso']
                : (optional($venta->moneda)->codigo_iso ?? (isset($moneda->codigo_iso) ? $moneda->codigo_iso : 'PEN'));
            $simbolo = isset($datos['moneda']['simbolo']) ? $datos['moneda']['simbolo']
                : ($codigoIso === 'USD' ? '$' : 'S/');
        @endphp

        <!-- Detalle estandarizado y totales -->
        @include('comprobantes.partials.detalle_estandar')

        <!-- Totales integrados en el bloque estandarizado; SON en condiciones -->

        <!-- Importe total en letras -->
        <div class="amount-words{{ $singlePage ? ' compact' : '' }}">
            <!-- <h4>IMPORTE TOTAL EN LETRAS:</h4> -->
            <p>
                {{ $datos['total_en_letras'] 
                    ?? ($totalEnLetras ?? (function() use($venta, $codigoIso) {
                        // Fallback simple: entero y dos decimales /100 + moneda
                        $entero = intval(floor($venta->total ?? 0));
                        $fraccion = max(0, ($venta->total ?? 0) - $entero);
                        $dec = intval(round($fraccion * 100));
                        if ($dec === 100) { $dec = 0; $entero += 1; }
                        $mon = (($codigoIso ?? 'PEN') === 'USD') ? 'DOLARES AMERICANOS' : 'SOLES';
                        return 'SON: ' . $entero . ' CON ' . sprintf('%02d', $dec) . '/100 ' . $mon;
                    })()) 
                }}
            </p>
        </div>

        <!-- Datos Bancarios (reemplaza condiciones comerciales) -->
        <div class="bank-data{{ $singlePage ? ' compact' : '' }}">
            <h4>DATOS BANCARIOS</h4>
            <table class="bank-table{{ $singlePage ? ' compact' : '' }}">
                <thead>
                    <tr>
                        <th>BANCO</th>
                        <th>MONEDA</th>
                        <th>CUENTA</th>
                        <th>CCI</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="bank-badge">BCP</span></td>
                        <td>SOLES</td>
                        <td>245-8089437-0-29</td>
                        <td>00224500808943702997</td>
                    </tr>
                    <tr>
                        <td><span class="bank-badge">BCP</span></td>
                        <td>DOLARES</td>
                        <td>245-9392508-1-72</td>
                        <td>00224500939250817296</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer{{ $singlePage ? ' compact' : '' }}">
            @if($venta->vendedor)
            <p><strong>Ejecutivo de Ventas:</strong> {{ $venta->vendedor->nombre }}</p>
            @endif
            <p>¡Gracias por su confianza en IRM Maquinarias S.R.L.!</p>
            <p>Sistema de Gestión IRM Maquinarias S.R.L. - Generado el {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>