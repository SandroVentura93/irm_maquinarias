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
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 2px solid #fd7e14;
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
        
        .document-info {
            display: table-cell;
            width: 35%;
            vertical-align: top;
            text-align: center;
            border: 2px solid #fd7e14;
            padding: 15px;
            background: #fff3cd;
        }
        
        .document-info h1 {
            color: #fd7e14;
            margin-bottom: 10px;
            font-size: 18px;
        }
        
        .document-number {
            font-size: 16px;
            font-weight: bold;
            color: #fd7e14;
            margin-bottom: 10px;
        }
        
        .motivo-nota {
            background: #fff3cd;
            border-left: 4px solid #fd7e14;
            padding: 10px;
            margin: 15px 0;
        }
        
        .client-info {
            background: #f8f9fa;
            padding: 15px;
            margin: 20px 0;
            border-left: 4px solid #fd7e14;
        }
        
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
        
        .details-table th {
            background: #fd7e14;
            color: white;
            padding: 10px;
            text-align: center;
            font-size: 11px;
        }
        
        .details-table td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 11px;
        }
        
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
        
        .totals-left {
            display: table-cell;
            width: 60%;
            vertical-align: top;
            padding-right: 20px;
        }
        
        .totals-right {
            display: table-cell;
            width: 40%;
            vertical-align: top;
        }
        
        .total-letras {
            background: #f8f9fa;
            padding: 10px;
            border: 1px solid #ddd;
            margin-bottom: 15px;
        }
        
        .observaciones {
            background: #f8f9fa;
            padding: 10px;
            border-left: 4px solid #fd7e14;
        }
        
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        
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
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                @include('comprobantes.partials.logo')
                <div class="company-details">
                    <h2>{{ $empresa['razon_social'] ?? 'IRM MAQUINARIAS S.A.C.' }}</h2>
                    <p><strong>RUC:</strong> {{ $empresa['ruc'] ?? '20123456789' }}</p>
                    <p><strong>Dirección:</strong> {{ $empresa['direccion'] ?? 'Av. Industrial 123, Lima, Perú' }}</p>
                    <p><strong>Teléfono:</strong> {{ $empresa['telefono'] ?? '(01) 234-5678' }}</p>
                    <p><strong>Email:</strong> {{ $empresa['email'] ?? 'ventas@irmmaquinarias.com' }}</p>
                </div>
            </div>
            
            <div class="document-info">
                <h1>{{ $tipoConfig['titulo'] ?? 'NOTA DE DÉBITO ELECTRÓNICA' }}</h1>
                <div class="document-number">{{ $venta->numero }}</div>
                <p><strong>Código SUNAT:</strong> {{ $tipoConfig['codigo_sunat'] ?? '08' }}</p>
            </div>
        </div>

        <!-- Motivo de la nota de débito -->
        <div class="motivo-nota">
            <h4>📋 MOTIVO DE LA NOTA DE DÉBITO</h4>
            <p><strong>Código:</strong> 01 - Intereses por mora</p>
            <p><strong>Descripción:</strong> Cargo adicional por servicios</p>
            <p><strong>Documento de referencia:</strong> Factura F001-00000001</p>
        </div>

        <!-- Información del cliente -->
        <div class="client-info">
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
                <p><strong>Moneda:</strong> {{ $venta->moneda == 'PEN' ? 'Soles Peruanos' : 'Dólares Americanos' }}</p>
                <p><strong>Tipo de Cambio:</strong> S/ {{ number_format($venta->tipo_cambio ?? 3.75, 2) }}</p>
            </div>
            <div style="display: table-cell; width: 33.33%; padding: 5px;">
                <p><strong>Usuario:</strong> {{ auth()->user()->name ?? 'Sistema' }}</p>
                <p><strong>Motivo SUNAT:</strong> 01 - Intereses por mora</p>
            </div>
        </div>

        <!-- Detalle de cargos -->
        <table class="details-table">
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
                    <td class="text-right">{{ $venta->moneda }} {{ number_format($detalle->precio_unitario, 2) }}</td>
                    <td class="amount">
                        @php
                            $subtotal_item = $detalle->cantidad * $detalle->precio_unitario * (1 - $detalle->descuento_porcentaje/100);
                        @endphp
                        +{{ $venta->moneda }} {{ number_format($subtotal_item, 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totales -->
        <div class="totals">
            <div class="totals-left">
                <div class="total-letras">
                    <h4>IMPORTE DE LA NOTA EN LETRAS:</h4>
                    <p class="font-bold">{{ $datos['total_en_letras'] ?? 'CIEN CON 00/100 SOLES' }}</p>
                </div>
                
                <div class="observaciones">
                    <h4>INFORMACIÓN SOBRE EL CARGO ADICIONAL:</h4>
                    <p>• Esta Nota de Débito incrementa el monto del documento de referencia</p>
                    <p>• El importe adicional debe ser cancelado junto con la deuda principal</p>
                    <p>• Cargo aplicado según términos y condiciones comerciales</p>
                    <p>• Para consultas contactar con el área de cobranzas</p>
                </div>
            </div>
            
            <div class="totals-right">
                <table class="totals-table">
                    <tr>
                        <td class="label">Operación Gravada:</td>
                        <td class="value">+{{ $venta->moneda }} {{ number_format($datos['base_imponible'] ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label">IGV (18%):</td>
                        <td class="value">+{{ $venta->moneda }} {{ number_format($datos['igv'] ?? 0, 2) }}</td>
                    </tr>
                    <tr class="total">
                        <td class="total">TOTAL NOTA DÉBITO:</td>
                        <td class="total">+{{ $venta->moneda }} {{ number_format($datos['total'] ?? 0, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="color: #fd7e14; font-weight: bold;">NOTA DE DÉBITO ELECTRÓNICA - DOCUMENTO OFICIAL SUNAT</p>
            <p>Este documento incrementa el importe del comprobante de referencia según la normativa tributaria vigente</p>
            <p>{{ $empresa['web'] ?? 'www.irmmaquinarias.com' }} | {{ $empresa['email'] ?? 'ventas@irmmaquinarias.com' }} | Teléfono: {{ $empresa['telefono'] ?? '(01) 234-5678' }}</p>
            <p>Fecha y hora de generación: {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>