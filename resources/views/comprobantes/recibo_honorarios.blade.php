<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RECIBO POR HONORARIOS - {{ $venta->serie }}{{ str_pad($venta->numero_comprobante, 8, '0', STR_PAD_LEFT) }}</title>
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
            border-bottom: 2px solid #6c757d;
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
            border: 2px solid #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            font-size: 10px;
            color: #6c757d;
            font-weight: bold;
        }
        
        .company-details h2 {
            color: #6c757d;
            margin-bottom: 5px;
            font-size: 18px;
        }
        
        .document-info {
            display: table-cell;
            width: 35%;
            vertical-align: top;
            text-align: center;
            border: 2px solid #6c757d;
            padding: 15px;
            background: #f8f9fa;
        }
        
        .document-info h1 {
            color: #6c757d;
            margin-bottom: 10px;
            font-size: 18px;
        }
        
        .document-number {
            font-size: 16px;
            font-weight: bold;
            color: #6c757d;
            margin-bottom: 10px;
        }
        
        .retencion-info {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 10px;
            margin: 15px 0;
        }
        
        .client-info {
            background: #f8f9fa;
            padding: 15px;
            margin: 20px 0;
            border-left: 4px solid #6c757d;
        }
        
        .client-info h3 {
            color: #6c757d;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .details-table th {
            background: #6c757d;
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
            border-left: 4px solid #6c757d;
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
            font-weight: bold;
        }
        
        .totals-table .retencion {
            color: #dc3545;
        }
        
        .totals-table .total {
            background: #6c757d;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }
        
        .retencion-box {
            margin-top: 15px;
            padding: 10px;
            background: #f8f9fa;
            border: 1px solid #6c757d;
            border-radius: 5px;
            text-align: center;
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
                <h1>{{ $tipoConfig['titulo'] ?? 'RECIBO POR HONORARIOS' }}</h1>
                <div class="document-number">{{ $venta->serie }}-{{ str_pad($venta->numero_comprobante, 8, '0', STR_PAD_LEFT) }}</div>
                <p><strong>Código SUNAT:</strong> {{ $tipoConfig['codigo_sunat'] ?? '14' }}</p>
            </div>
        </div>

        <!-- Información sobre la retención -->
        <div class="retencion-info">
            <h4 style="color: #856404; margin-bottom: 5px;">💰 INFORMACIÓN SOBRE RETENCIÓN</h4>
            <p style="color: #856404; font-size: 11px;">Este recibo está sujeto a retención del 8% del Impuesto a la Renta según normativa SUNAT para servicios profesionales independientes.</p>
        </div>

        <!-- Información del profesional -->
        <div class="client-info">
            <h3>👨‍💼 INFORMACIÓN DEL PROFESIONAL</h3>
            <p><strong>Profesional:</strong> {{ $venta->cliente->nombre ?? 'Profesional Independiente' }}</p>
            <p><strong>RUC:</strong> {{ $venta->cliente->numero_documento ?? 'Sin RUC' }}</p>
            <p><strong>Dirección:</strong> {{ $venta->cliente->direccion ?? 'Sin dirección' }}</p>
        </div>

        <!-- Información del recibo -->
        <div style="display: table; width: 100%; margin: 15px 0;">
            <div style="display: table-cell; width: 33.33%; padding: 5px;">
                <p><strong>Fecha de Emisión:</strong> {{ $venta->fecha->format('d/m/Y') }}</p>
                <p><strong>Período del Servicio:</strong> {{ $venta->fecha->format('m/Y') }}</p>
            </div>
            <div style="display: table-cell; width: 33.33%; padding: 5px;">
                <p><strong>Moneda:</strong> {{ $venta->moneda == 'PEN' ? 'Soles Peruanos' : 'Dólares Americanos' }}</p>
                <p><strong>Tipo de Servicio:</strong> Servicios Profesionales</p>
            </div>
            <div style="display: table-cell; width: 33.33%; padding: 5px;">
                <p><strong>Condición de Pago:</strong> {{ $datos['condiciones_pago'] ?? 'Contado' }}</p>
                <p><strong>Retención IR:</strong> 8% Aplicada</p>
            </div>
        </div>

        <!-- Detalle de servicios -->
        <table class="details-table">
            <thead>
                <tr>
                    <th style="width: 8%;">Item</th>
                    <th style="width: 50%;">Descripción del Servicio Profesional</th>
                    <th style="width: 10%;">Cantidad</th>
                    <th style="width: 16%;">Valor Unitario</th>
                    <th style="width: 16%;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($venta->detalleVenta as $index => $detalle)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="description">
                        <strong>{{ $detalle->producto->descripcion }}</strong><br>
                        <small style="color: #666;">Servicio profesional independiente</small><br>
                        <small style="color: #6c757d;">👨‍💼 Servicio prestado: Consultoría especializada</small>
                    </td>
                    <td>{{ number_format($detalle->cantidad, 0) }}</td>
                    <td class="text-right">{{ $venta->moneda }} {{ number_format($detalle->precio_unitario, 2) }}</td>
                    <td class="amount">
                        @php
                            $subtotal_item = $detalle->cantidad * $detalle->precio_unitario * (1 - $detalle->descuento_porcentaje/100);
                        @endphp
                        {{ $venta->moneda }} {{ number_format($subtotal_item, 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totales con retención -->
        <div class="totals">
            <div class="totals-left">
                <div class="total-letras">
                    <h4>IMPORTE NETO A PAGAR EN LETRAS:</h4>
                    <p class="font-bold">{{ $datos['total_en_letras'] ?? 'CIEN CON 00/100 SOLES' }}</p>
                </div>
                
                <div class="observaciones">
                    <h4>INFORMACIÓN SOBRE HONORARIOS PROFESIONALES:</h4>
                    <p>• Los honorarios están sujetos a retención del Impuesto a la Renta (8%)</p>
                    <p>• El profesional debe declarar estos ingresos en su declaración anual</p>
                    <p>• La empresa retendrá y pagará el impuesto correspondiente a SUNAT</p>
                    <p>• Este recibo sustenta el gasto para efectos tributarios de la empresa</p>
                    <p>• El profesional debe emitir factura si cuenta con RUC</p>
                </div>
                
                @if($datos['observaciones'] ?? false)
                <div class="observaciones" style="margin-top: 10px;">
                    <h4>OBSERVACIONES ADICIONALES:</h4>
                    <p>{{ $datos['observaciones'] }}</p>
                </div>
                @endif
            </div>
            
            <div class="totals-right">
                <table class="totals-table">
                    <tr>
                        <td class="label">Valor del Servicio:</td>
                        <td class="value">{{ $venta->moneda }} {{ number_format($datos['base_imponible'] ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label">Retención IR (8%):</td>
                        <td class="value retencion">-{{ $venta->moneda }} {{ number_format($datos['retencion'] ?? ($datos['base_imponible'] * 0.08), 2) }}</td>
                    </tr>
                    <tr class="total">
                        <td class="total">NETO A PAGAR:</td>
                        <td class="total">{{ $venta->moneda }} {{ number_format($datos['total'] ?? 0, 2) }}</td>
                    </tr>
                </table>
                
                <!-- Información de retención -->
                <div class="retencion-box">
                    <h4 style="color: #6c757d; margin-bottom: 5px;">CONSTANCIA DE RETENCIÓN</h4>
                    <p style="font-size: 10px; color: #666;">
                        La empresa retendrá el 8% del Impuesto a la Renta<br>
                        Monto retenido: {{ $venta->moneda }} {{ number_format($datos['retencion'] ?? ($datos['base_imponible'] * 0.08), 2) }}<br>
                        Se entregará constancia de retención
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="color: #6c757d; font-weight: bold;">RECIBO POR HONORARIOS - SERVICIOS PROFESIONALES INDEPENDIENTES</p>
            <p>Este documento sustenta el pago por servicios profesionales independientes según normativa tributaria vigente</p>
            <p>{{ $empresa['web'] ?? 'www.irmmaquinarias.com' }} | {{ $empresa['email'] ?? 'ventas@irmmaquinarias.com' }} | Teléfono: {{ $empresa['telefono'] ?? '(01) 234-5678' }}</p>
            <p>Fecha y hora de generación: {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>