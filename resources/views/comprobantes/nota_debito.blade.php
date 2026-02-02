<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NOTA DE DÉBITO - {{ $venta->numero }}</title>
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
            border-bottom: 2px solid #fd7e14;
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
            background: #fff3cd;
            border: 2px solid #fd7e14;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            font-size: 10px;
            color: #fd7e14;
            font-weight: bold;
        }
        
        .company-details h2 {
            color: #fd7e14;
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
            border: 2px solid #fd7e14;
            padding: 15px;
            background: #fff3cd;
        }
        .document-info.compact { padding: 10px; }
        
        .document-info h1 {
            color: #fd7e14;
            margin-bottom: 10px;
            font-size: 18px;
        }
        .document-info.compact h1 { font-size: 16px; margin-bottom: 6px; }
        
        .document-number {
            font-size: 16px;
            font-weight: bold;
            color: #fd7e14;
            margin-bottom: 10px;
        }
        .document-info.compact .document-number { font-size: 14px; margin-bottom: 6px; }
        
        .motivo-nota {
            background: #fff3cd;
            border-left: 4px solid #fd7e14;
            padding: 10px;
            margin: 15px 0;
        }
        .motivo-nota.compact { padding: 8px; margin: 12px 0; }
        .motivo-nota.compact h4 { margin-bottom: 6px; }
        .motivo-nota.compact p { margin: 2px 0; }
        
        .client-info {
            background: #f8f9fa;
            padding: 15px;
            margin: 20px 0;
            border-left: 4px solid #fd7e14;
        }
        .client-info.compact { padding: 10px; margin: 14px 0; }
        .client-info.compact h3 { margin-bottom: 6px; }
        .client-info.compact p { margin: 2px 0; }
        
        .client-info h3 {
            color: #fd7e14;
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
            background: #fd7e14;
            color: white;
            padding: 10px;
            text-align: center;
            font-size: 11px;
        }
        .details-table.compact th { padding: 8px 6px; font-size: 10px; }
        
        .details-table td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 11px;
        }
        .details-table.compact td { padding: 6px 5px; font-size: 10px; }
        
        .details-table .description {
            text-align: left;
        }
        
        .details-table .amount {
            text-align: right;
            font-weight: bold;
            color: #fd7e14;
        }
        
        .totals {
            display: table;
            width: 100%;
            margin-top: 20px;
        }
        .totals.compact { margin-top: 16px; }
        
        .totals-left {
            display: table-cell;
            width: 60%;
            vertical-align: top;
            padding-right: 20px;
        }
        .totals-left.compact { padding-right: 12px; }
        
        .totals-right {
            display: table-cell;
            width: 40%;
            vertical-align: top;
        }
        .totals-right.compact { padding-left: 6px; }
        
        .total-letras {
            background: #f8f9fa;
            padding: 10px;
            border: 1px solid #ddd;
            margin-bottom: 15px;
        }
        .total-letras.compact { padding: 8px; margin-bottom: 10px; }
        
        .observaciones {
            background: #f8f9fa;
            padding: 10px;
            border-left: 4px solid #fd7e14;
        }
        .observaciones.compact { padding: 8px; }
        .observaciones.compact p { margin: 2px 0; }
        
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        .totals-table.compact td { padding: 6px 10px; font-size: 11px; }
        
        .totals-table td {
            padding: 8px 12px;
            border: 1px solid #ddd;
        }
        
        .totals-table .label {
            background: #f8f9fa;
            font-weight: bold;
            text-align: right;
        }
        
        .totals-table .value {
            text-align: right;
            color: #fd7e14;
            font-weight: bold;
        }
        
        .totals-table .total {
            background: #fd7e14;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }
        .totals-table.compact .total { font-size: 12px; }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        .footer.compact { margin-top: 20px; padding-top: 10px; }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
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
                <h1>{{ $tipoConfig['titulo'] ?? 'NOTA DE DÉBITO ELECTRÓNICA' }}</h1>
                <div class="document-number">{{ $venta->numero }}</div>
                <p><strong>Código SUNAT:</strong> {{ $tipoConfig['codigo_sunat'] ?? '08' }}</p>
            </div>
        </div>

        <!-- Motivo de la nota de débito -->
        <div class="motivo-nota{{ $singlePage ? ' compact' : '' }}">
            <h4>📋 MOTIVO DE LA NOTA DE DÉBITO</h4>
            <p><strong>Código:</strong> 01 - Intereses por mora</p>
            <p><strong>Descripción:</strong> Cargo adicional por servicios</p>
            <p><strong>Documento de referencia:</strong> Factura F001-00000001</p>
        </div>

        <!-- Información del cliente -->
        <div class="client-info{{ $singlePage ? ' compact' : '' }}">
            <h3>INFORMACIÓN DEL CLIENTE</h3>
            <p><strong>Razón Social/Nombre:</strong> {{ $venta->cliente->nombre ?? 'Cliente General' }}</p>
            <p><strong>RUC/DNI:</strong> {{ $venta->cliente->numero_documento ?? 'Sin documento' }}</p>
            <p><strong>Dirección:</strong> {{ $venta->cliente->direccion ?? 'Sin dirección' }}</p>
        </div>

        <!-- Información de la nota -->
        <div style="display: table; width: 100%; margin: 15px 0;">
            <div style="display: table-cell; width: 33.33%; padding: 5px;">
                <p><strong>Fecha de Emisión:</strong> {{ $venta->fecha->format('d/m/Y') }}</p>
                <p><strong>Hora:</strong> {{ $venta->fecha->format('H:i:s') }}</p>
            </div>
            <div style="display: table-cell; width: 33.33%; padding: 5px;">
                @php
                    $codigoIso = is_object($venta->moneda) ? ($venta->moneda->codigo_iso ?? 'PEN') : ($venta->moneda ?? 'PEN');
                    $simbolo = $codigoIso === 'USD' ? '$' : 'S/';
                @endphp
                <p><strong>Moneda:</strong> {{ $codigoIso === 'USD' ? 'Dólares Americanos' : 'Soles Peruanos' }} <span style="display:inline-block; padding:2px 6px; background:#2c5aa0; color:white; border-radius:4px; font-size:10px;">{{ $codigoIso }}</span></p>
                    <p><strong>Tipo de Cambio (referencial):</strong> S/ {{ number_format($venta->tipo_cambio ?? $tipoCambio ?? 0, 2) }} por USD</p>
            </div>
            <div style="display: table-cell; width: 33.33%; padding: 5px;">
                <p><strong>Usuario:</strong> {{ auth()->user()->name ?? 'Sistema' }}</p>
                <p><strong>Motivo SUNAT:</strong> 01 - Intereses por mora</p>
            </div>
        </div>

        <!-- Detalle de cargos -->
        <table class="details-table{{ $singlePage ? ' compact' : '' }}">
            <thead>
                <tr>
                    <th style="width: 8%;">Item</th>
                    <th style="width: 40%;">Descripción del Cargo</th>
                    <th style="width: 10%;">Cantidad</th>
                    <th style="width: 15%;">Precio Unitario</th>
                    <th style="width: 15%;">Importe</th>
                </tr>
            </thead>
            <tbody>
                @foreach($venta->detalleVenta as $index => $detalle)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="description">
                        <strong>Cargo adicional: {{ $detalle->producto->descripcion }}</strong><br>
                        <small style="color: #fd7e14;">💰 CARGO ADICIONAL: Por servicios extras</small>
                    </td>
                    <td style="color: #fd7e14; font-weight: bold;">+{{ number_format($detalle->cantidad, 2) }}</td>
                    <td class="text-right">{{ $simbolo }} {{ number_format($detalle->precio_unitario, 2) }}</td>
                    <td class="amount">
                        @php
                            $subtotal_item = $detalle->cantidad * $detalle->precio_unitario * (1 - $detalle->descuento_porcentaje/100);
                        @endphp
                        +{{ $simbolo }} {{ number_format($subtotal_item, 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totales -->
        <div class="totals{{ $singlePage ? ' compact' : '' }}">
            <div class="totals-left{{ $singlePage ? ' compact' : '' }}">
                <div class="total-letras{{ $singlePage ? ' compact' : '' }}">
                    <h4>IMPORTE DE LA NOTA EN LETRAS:</h4>
                    <p class="font-bold">{{ $datos['total_en_letras'] ?? 'CIEN CON 00/100 SOLES' }}</p>
                </div>
                
                <div class="observaciones{{ $singlePage ? ' compact' : '' }}">
                    <h4>INFORMACIÓN SOBRE EL CARGO ADICIONAL:</h4>
                    <p>• Esta Nota de Débito incrementa el monto del documento de referencia</p>
                    <p>• El importe adicional debe ser cancelado junto con la deuda principal</p>
                    <p>• Cargo aplicado según términos y condiciones comerciales</p>
                    <p>• Para consultas contactar con el área de cobranzas</p>
                </div>
            </div>
            
            <div class="totals-right{{ $singlePage ? ' compact' : '' }}">
                <table class="totals-table{{ $singlePage ? ' compact' : '' }}">
                    <tr>
                        <td class="label">Operación Gravada:</td>
                        <td class="value">+{{ $simbolo }} {{ number_format($datos['base_imponible'] ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label">IGV (18%):</td>
                        <td class="value">+{{ $simbolo }} {{ number_format($datos['igv'] ?? 0, 2) }}</td>
                    </tr>
                    <tr class="total">
                        <td class="total">TOTAL NOTA DÉBITO:</td>
                        <td class="total">+{{ $simbolo }} {{ number_format($datos['total'] ?? 0, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer{{ $singlePage ? ' compact' : '' }}">
            <p style="color: #fd7e14; font-weight: bold;">NOTA DE DÉBITO ELECTRÓNICA - DOCUMENTO OFICIAL SUNAT</p>
            <p>Este documento incrementa el importe del comprobante de referencia según la normativa tributaria vigente</p>
            <p>{{ $empresa['web'] ?? 'www.irmmaquinarias.com' }} | {{ $empresa['email'] ?? 'ventas@irmmaquinarias.com' }} | Teléfono: {{ $empresa['telefono'] ?? '(01) 234-5678' }}</p>
            <p>Fecha y hora de generación: {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>