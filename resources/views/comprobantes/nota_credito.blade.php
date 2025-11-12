<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NOTA DE CRÉDITO - {{ $venta->serie }}{{ $venta->numero }}</title>
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
            border-bottom: 2px solid #dc3545;
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
            border: 2px solid #dc3545;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            font-size: 10px;
            color: #dc3545;
            font-weight: bold;
        }
        
        .company-details h2 {
            color: #dc3545;
            margin-bottom: 5px;
            font-size: 18px;
        }
        
        .document-info {
            display: table-cell;
            width: 35%;
            vertical-align: top;
            text-align: center;
            border: 2px solid #dc3545;
            padding: 15px;
            background: #f8f9fa;
        }
        
        .document-info h1 {
            color: #dc3545;
            margin-bottom: 10px;
            font-size: 18px;
        }
        
        .document-number {
            font-size: 16px;
            font-weight: bold;
            color: #dc3545;
            margin-bottom: 10px;
        }
        
        .client-info {
            background: #f8f9fa;
            padding: 15px;
            margin: 20px 0;
            border-left: 4px solid #dc3545;
        }
        
        .client-info h3 {
            color: #dc3545;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .reference-info {
            background: #fff5f5;
            border: 2px solid #dc3545;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        
        .reference-info h4 {
            color: #dc3545;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .reason-box {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        
        .reason-box h4 {
            color: #721c24;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .details-table th {
            background: #dc3545;
            color: white;
            padding: 10px 8px;
            text-align: center;
            font-size: 11px;
            border: 1px solid #c82333;
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
        
        .credit-row {
            background-color: #f8d7da !important;
            font-weight: bold;
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
            background: #dc3545;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }
        
        .legal-info {
            margin-top: 30px;
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 15px;
            border-radius: 5px;
        }
        
        .legal-info h4 {
            color: #721c24;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .legal-info p {
            color: #721c24;
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
            border-left: 4px solid #dc3545;
            font-weight: bold;
            color: #721c24;
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
                <h1>NOTA DE CRÉDITO</h1>
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
                    <p><strong>{{ strlen($cliente->numero_documento) == 11 ? 'Razón Social' : 'Nombre' }}:</strong> {{ $cliente->razon_social ?: $cliente->nombre }}</p>
                    <p><strong>{{ strlen($cliente->numero_documento) == 8 ? 'DNI' : 'RUC' }}:</strong> {{ $cliente->numero_documento }}</p>
                </div>
                <div style="display: table-cell; width: 50%;">
                    <p><strong>Dirección:</strong> {{ $cliente->direccion ?: 'No especificada' }}</p>
                    <p><strong>Email:</strong> {{ $cliente->email ?: 'No especificado' }}</p>
                </div>
            </div>
        </div>

        <!-- Información del documento de referencia -->
        <div class="reference-info">
            <h4>DOCUMENTO QUE SE MODIFICA</h4>
            <div style="display: table; width: 100%;">
                <div style="display: table-cell; width: 50%;">
                    <p><strong>Tipo de Documento:</strong> Factura Electrónica</p>
                    <p><strong>Serie y Número:</strong> F001-00000001</p>
                </div>
                <div style="display: table-cell; width: 50%;">
                    <p><strong>Fecha del Documento:</strong> {{ date('d/m/Y', strtotime($fecha . ' -30 days')) }}</p>
                    <p><strong>Tipo de Nota de Crédito:</strong> Anulación de operación</p>
                </div>
            </div>
        </div>

        <!-- Motivo de la nota de crédito -->
        <div class="reason-box">
            <h4>MOTIVO DE LA NOTA DE CRÉDITO</h4>
            <p style="color: #721c24;"><strong>01 - Anulación de la operación</strong></p>
            <p style="color: #721c24; font-size: 11px;">
                Se emite la presente Nota de Crédito para anular la operación de venta realizada en el documento de referencia,
                debido a solicitud del cliente y previa autorización de gerencia.
            </p>
        </div>

        <!-- Detalle de productos/servicios -->
        <table class="details-table">
            <thead>
                <tr>
                    <th style="width: 8%;">ITEM</th>
                    <th style="width: 45%;">DESCRIPCIÓN</th>
                    <th style="width: 12%;">CANTIDAD</th>
                    <th style="width: 15%;">VALOR UNIT.</th>
                    <th style="width: 20%;">VALOR VENTA</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detalles as $index => $detalle)
                <tr class="credit-row">
                    <td>{{ $index + 1 }}</td>
                    <td style="text-align: left; padding-left: 10px;">
                        <strong>{{ $detalle->producto->descripcion ?? 'Producto no encontrado' }}</strong>
                        @if($detalle->producto->codigo)
                            <br><small>Código: {{ $detalle->producto->codigo }}</small>
                        @endif
                        @if($detalle->producto->numero_parte)
                            <br><small>Número de Parte: {{ $detalle->producto->numero_parte }}</small>
                        @endif
                        <br><small style="color: #dc3545;"><strong>CRÉDITO POR ANULACIÓN</strong></small>
                    </td>
                    <td>-{{ number_format($detalle->cantidad, 2) }}</td>
                    <td>{{ $moneda->simbolo }} -{{ number_format($detalle->precio_unitario, 2) }}</td>
                    <td>{{ $moneda->simbolo }} -{{ number_format($detalle->cantidad * $detalle->precio_unitario * (1 - ($detalle->descuento_porcentaje ?? 0)/100), 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Importe en palabras -->
        <div class="amount-words">
            <strong>SON:</strong> {{ strtoupper($totalEnLetras) }} CON SIGNO NEGATIVO
        </div>

        <!-- Totales -->
        <div class="totals">
            <table>
                <tr>
                    <td class="total-label">SUB TOTAL:</td>
                    <td>{{ $moneda->simbolo }} -{{ number_format($venta->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td class="total-label">IGV (18%):</td>
                    <td>{{ $moneda->simbolo }} -{{ number_format($venta->igv, 2) }}</td>
                </tr>
                <tr class="total-final">
                    <td>TOTAL NOTA DE CRÉDITO:</td>
                    <td>{{ $moneda->simbolo }} -{{ number_format($venta->total, 2) }}</td>
                </tr>
            </table>
        </div>

        <!-- Información legal -->
        <div class="legal-info">
            <h4>INFORMACIÓN LEGAL</h4>
            <p>• Esta Nota de Crédito anula la operación realizada en el documento de referencia</p>
            <p>• El crédito fiscal generado por el documento de referencia queda sin efecto</p>
            <p>• Para efectos tributarios, considere esta nota de crédito junto con el documento original</p>
            <p>• La presente nota de crédito cumple con las disposiciones vigentes de SUNAT</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            @if($venta->vendedor)
            <p><strong>Procesado por:</strong> {{ $venta->vendedor->nombre }}</p>
            @endif
            <p>Estado del Comprobante: {{ $venta->xml_estado }}</p>
            <p>Sistema de Gestión IRM Maquinarias - Generado el {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>