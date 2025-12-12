<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GUÍA DE REMISIÓN - {{ $venta->serie }}{{ str_pad($venta->numero_comprobante, 8, '0', STR_PAD_LEFT) }}</title>
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
            border-bottom: 2px solid #6f42c1;
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
            background: #e2d9f3;
            border: 2px solid #6f42c1;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            font-size: 10px;
            color: #6f42c1;
            font-weight: bold;
        }
        
        .company-details h2 {
            color: #6f42c1;
            margin-bottom: 5px;
            font-size: 18px;
        }
        
        .document-info {
            display: table-cell;
            width: 35%;
            vertical-align: top;
            text-align: center;
            border: 2px solid #6f42c1;
            padding: 15px;
            background: #e2d9f3;
        }
        
        .document-info h1 {
            color: #6f42c1;
            margin-bottom: 10px;
            font-size: 18px;
        }
        
        .document-number {
            font-size: 16px;
            font-weight: bold;
            color: #6f42c1;
            margin-bottom: 10px;
        }
        
        .traslado-info {
            background: #e2d9f3;
            border: 1px solid #6f42c1;
            padding: 10px;
            margin: 15px 0;
            border-radius: 5px;
        }
        
        .client-info {
            background: #f8f9fa;
            padding: 15px;
            margin: 20px 0;
            border-left: 4px solid #6f42c1;
        }
        
        .client-info h3 {
            color: #6f42c1;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .details-table th {
            background: #6f42c1;
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
        
        .observaciones {
            background: #f8f9fa;
            padding: 10px;
            border-left: 4px solid #6f42c1;
            margin-bottom: 15px;
        }
        
        .firmas {
            border: 1px solid #6f42c1;
            padding: 15px;
            border-radius: 5px;
        }
        
        .firma-section {
            margin-bottom: 40px;
        }
        
        .firma-line {
            border-top: 1px solid #666;
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            padding-top: 5px;
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
                    <h2>{{ $empresa['razon_social'] ?? 'IRM Maquinarias S.R.L.' }}</h2>
                    <p><strong>RUC:</strong> {{ $empresa['ruc'] ?? '20570639553' }}</p>
                    <p><strong>Dirección:</strong> {{ $empresa['direccion'] ?? 'AV. ATAHUALPA NRO. 725, CAJAMARCA' }}</p>
                    <p><strong>Teléfono:</strong> {{ $empresa['telefono'] ?? '976390506 - 974179198' }}</p>
                    <p><strong>Email:</strong> {{ $empresa['email'] ?? 'ventas@irmmaquinarias.com' }}</p>
                </div>
            </div>
            
            <div class="document-info">
                <h1>{{ $tipoConfig['titulo'] ?? 'GUÍA DE REMISIÓN ELECTRÓNICA' }}</h1>
                <div class="document-number">{{ $venta->serie }}-{{ str_pad($venta->numero_comprobante, 8, '0', STR_PAD_LEFT) }}</div>
                <p><strong>Código SUNAT:</strong> {{ $tipoConfig['codigo_sunat'] ?? '09' }}</p>
            </div>
        </div>

        <!-- Información del traslado -->
        <div class="traslado-info">
            <h4 style="color: #6f42c1; margin-bottom: 8px;">🚚 INFORMACIÓN DEL TRASLADO</h4>
            <div style="display: table; width: 100%;">
                <div style="display: table-row;">
                    <div style="display: table-cell; width: 50%; font-size: 11px; padding: 2px;">
                        <strong>Motivo del traslado:</strong> {{ $datos['motivo_traslado'] ?? 'Venta' }}
                    </div>
                    <div style="display: table-cell; width: 50%; font-size: 11px; padding: 2px;">
                        <strong>Peso bruto total:</strong> {{ $datos['peso_bruto'] ?? '1.00 KG' }}
                    </div>
                </div>
                <div style="display: table-row;">
                    <div style="display: table-cell; width: 50%; font-size: 11px; padding: 2px;">
                        <strong>Modalidad de transporte:</strong> Transporte privado
                    </div>
                    <div style="display: table-cell; width: 50%; font-size: 11px; padding: 2px;">
                        <strong>Fecha de inicio del traslado:</strong> {{ $venta->fecha->format('d/m/Y') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Información del destinatario -->
        <div class="client-info">
            <h3>🏠 INFORMACIÓN DEL DESTINATARIO</h3>
            <p><strong>Destinatario:</strong> {{ $venta->cliente->nombre ?? 'Cliente General' }}</p>
            <p><strong>RUC/DNI:</strong> {{ $venta->cliente->numero_documento ?? 'Sin documento' }}</p>
            <p><strong>Dirección de destino:</strong> {{ $venta->cliente->direccion ?? 'Sin dirección' }}</p>
        </div>

        <!-- Información de origen y transporte -->
        <div style="display: table; width: 100%; margin: 15px 0; background: #f8f9fa; padding: 10px;">
            <div style="display: table-cell; width: 33.33%; padding: 5px;">
                <p><strong>Punto de partida:</strong> {{ $empresa['direccion'] ?? 'Almacén Central' }}</p>
                <p><strong>Transportista:</strong> {{ $empresa['razon_social'] ?? 'IRM Maquinarias S.R.L.' }}</p>
            </div>
            <div style="display: table-cell; width: 33.33%; padding: 5px;">
                <p><strong>Punto de llegada:</strong> {{ $venta->cliente->direccion ?? 'A coordinar' }}</p>
                <p><strong>Fecha de traslado:</strong> {{ $venta->fecha->format('d/m/Y') }}</p>
            </div>
            <div style="display: table-cell; width: 33.33%; padding: 5px;">
                <p><strong>Licencia de conducir:</strong> Q12345678</p>
                <p><strong>Placa del vehículo:</strong> ABC-123</p>
            </div>
        </div>

        <!-- Detalle de bienes a trasladar -->
        <table class="details-table">
            <thead>
                <tr>
                    <th style="width: 8%;">Item</th>
                    <th style="width: 15%;">Código</th>
                    <th style="width: 40%;">Descripción del Bien</th>
                    <th style="width: 8%;">Cantidad</th>
                    <th style="width: 8%;">Unidad</th>
                    <th style="width: 10%;">Peso Unit.</th>
                    <th style="width: 11%;">Peso Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($venta->detalleVenta as $index => $detalle)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-center">{{ $detalle->producto->codigo ?? 'SIN CÓDIGO' }}</td>
                    <td class="description">
                        <strong>{{ $detalle->producto->descripcion }}</strong>
                        @if($detalle->producto->numero_parte)
                            <br><small>N/P: {{ $detalle->producto->numero_parte }}</small>
                        @endif
                        @if($detalle->producto->marca)
                            <br><small>Marca: {{ $detalle->producto->marca->nombre ?? 'Sin marca' }}</small>
                        @endif
                        <br><small style="color: #6f42c1;">📦 Estado: Nuevo | Condición: Óptima</small>
                    </td>
                    <td>{{ number_format($detalle->cantidad, 0) }}</td>
                    <td>UND</td>
                    <td>1.00 KG</td>
                    <td>{{ number_format($detalle->cantidad * 1, 2) }} KG</td>
                </tr>
                @endforeach
                
                <!-- Total de peso -->
                <tr style="background: #f8f9fa; font-weight: bold;">
                    <td colspan="6" class="text-right" style="padding: 8px;">PESO BRUTO TOTAL:</td>
                    <td class="text-center" style="color: #6f42c1; font-weight: bold;">{{ $datos['peso_bruto'] ?? '1.00 KG' }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Observaciones y firmas -->
        <div class="totals">
            <div class="totals-left">
                <div class="observaciones">
                    <h4>OBSERVACIONES DEL TRASLADO:</h4>
                    <p>• Mercadería entregada en perfecto estado</p>
                    <p>• El destinatario debe verificar la mercadería al momento de la entrega</p>
                    <p>• Cualquier reclamo debe realizarse en el momento de la recepción</p>
                    <p>• El transportista no se hace responsable por daños después de la entrega</p>
                    
                    @if($datos['observaciones'] ?? false)
                    <br>
                    <h4>OBSERVACIONES ADICIONALES:</h4>
                    <p>{{ $datos['observaciones'] }}</p>
                    @endif
                </div>
            </div>
            
            <div class="totals-right">
                <div class="firmas">
                    <div style="text-align: center; font-weight: bold; color: #6f42c1; margin-bottom: 15px;">
                        CONFORMIDAD DE ENTREGA
                    </div>
                    
                    <div class="firma-section">
                        <div class="firma-line">
                            Firma y sello del transportista
                        </div>
                    </div>
                    
                    <div class="firma-section">
                        <div class="firma-line">
                            Firma y sello del destinatario
                        </div>
                    </div>
                    
                    <div style="font-size: 10px; color: #666; text-align: center;">
                        Fecha y hora de recepción: ___/___/_______ ___:___
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="color: #6f42c1; font-weight: bold;">GUÍA DE REMISIÓN ELECTRÓNICA - DOCUMENTO OFICIAL SUNAT</p>
            <p>Este documento sustenta el traslado de bienes según el artículo 18° del Reglamento de Comprobantes de Pago</p>
            <p>{{ $empresa['web'] ?? 'www.irmmaquinarias.com' }} | {{ $empresa['email'] ?? 'ventas@irmmaquinarias.com' }} | Teléfono: {{ $empresa['telefono'] ?? '(01) 234-5678' }}</p>
            <p>Fecha y hora de generación: {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>