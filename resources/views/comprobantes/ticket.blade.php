<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TICKET - {{ $venta->numero }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', monospace;
            font-size: 9px;
            line-height: 1.3;
            color: #333;
            max-width: 8cm;
            margin: 0 auto;
            padding: 0.3cm;
        }
        
        .ticket-header {
            text-align: center;
            border-bottom: 1px dashed #666;
            padding-bottom: 8px;
            margin-bottom: 8px;
        }
        
        .company-logo {
            width: 60px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 5px auto;
        }
        
        .company-logo img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        
        .empresa-nombre {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        
        .empresa-info {
            font-size: 8px;
            margin-bottom: 1px;
        }
        
        .ticket-tipo {
            background: #20c997;
            color: white;
            padding: 5px;
            margin: 8px 0;
            text-align: center;
            font-weight: bold;
            font-size: 10px;
        }
        
        .ticket-numero {
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .cliente-info {
            margin-bottom: 8px;
            font-size: 8px;
        }
        
        .separador {
            border-top: 1px dashed #666;
            margin: 8px 0;
        }
        
        .productos {
            margin-bottom: 8px;
        }
        
        .producto-item {
            margin-bottom: 5px;
            font-size: 8px;
        }
        
        .producto-nombre {
            font-weight: bold;
            margin-bottom: 1px;
        }
        
        .producto-detalle {
            display: flex;
            justify-content: space-between;
        }
        
        .totales {
            border-top: 1px dashed #666;
            padding-top: 5px;
            font-size: 9px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }
        
        .total-final {
            border-top: 1px solid #333;
            padding-top: 3px;
            margin-top: 3px;
            font-weight: bold;
            font-size: 10px;
        }
        
        .footer {
            text-align: center;
            margin-top: 10px;
            font-size: 7px;
            color: #666;
        }
        
        .agradecimiento {
            border-top: 1px dashed #666;
            padding-top: 5px;
            margin-top: 8px;
            text-align: center;
            font-size: 8px;
        }
    </style>
</head>
<body>
    <!-- Header del ticket -->
    <div class="ticket-header">
        @include('comprobantes.partials.logo')
        <div class="empresa-nombre">{{ $empresa['razon_social'] ?? 'IRM MAQUINARIAS S.A.C.' }}</div>
        <div class="empresa-info">RUC: {{ $empresa['ruc'] ?? '20123456789' }}</div>
        <div class="empresa-info">{{ $empresa['direccion'] ?? 'Av. Industrial 123, Lima, Perú' }}</div>
        <div class="empresa-info">Tel: {{ $empresa['telefono'] ?? '(01) 234-5678' }}</div>
    </div>

    <!-- Tipo y número de comprobante -->
    <div class="ticket-tipo">{{ $tipoConfig['titulo'] ?? 'TICKET DE MÁQUINA REGISTRADORA' }}</div>
    <div class="ticket-numero">{{ $venta->numero }}</div>

    <!-- Información básica -->
    <div class="cliente-info">
        <div>Fecha: {{ $venta->fecha->format('d/m/Y H:i:s') }}</div>
        <div>Cliente: {{ $venta->cliente->nombre ?? 'Cliente General' }}</div>
        @if($venta->cliente->numero_documento)
        <div>Doc: {{ $venta->cliente->numero_documento }}</div>
        @endif
        <div>Cajero: {{ auth()->user()->name ?? 'Sistema' }}</div>
    </div>

    <!-- Línea separadora -->
    <div class="separador"></div>

    <!-- Productos -->
    <div class="productos">
        @php
            $codigoIso = optional($venta->moneda)->codigo_iso ?? (is_string($venta->moneda) ? $venta->moneda : 'PEN');
            $simbolo = $codigoIso === 'USD' ? '$' : 'S/';
        @endphp
        @foreach($venta->detalleVenta as $detalle)
        <div class="producto-item">
            <div class="producto-nombre">{{ $detalle->producto->descripcion }}</div>
            <div class="producto-detalle">
                <span>{{ $detalle->cantidad }} x {{ $simbolo }} {{ number_format($detalle->precio_unitario, 2) }}</span>
                <span>{{ $simbolo }} {{ number_format($detalle->cantidad * $detalle->precio_unitario * (1 - $detalle->descuento_porcentaje/100), 2) }}</span>
            </div>
            @if($detalle->descuento_porcentaje > 0)
            <div style="font-size: 7px; color: #666;">Desc: {{ $detalle->descuento_porcentaje }}%</div>
            @endif
        </div>
        @endforeach
    </div>

    <!-- Totales -->
    <div class="totales">
        <div class="total-row">
            <span>Subtotal:</span>
            <span>{{ $simbolo }} {{ number_format($venta->subtotal, 2) }}</span>
        </div>
        <div class="total-row">
            <span>IGV (18%):</span>
            <span>{{ $simbolo }} {{ number_format(($venta->total - $venta->subtotal), 2) }}</span>
        </div>
        <div class="total-row total-final">
            <span>TOTAL:</span>
            <span>{{ $simbolo }} {{ number_format($venta->total, 2) }}</span>
        </div>
    </div>

    <!-- Agradecimiento -->
    <div class="agradecimiento">
        <div>¡Gracias por su compra!</div>
        <div>{{ $empresa['web'] ?? 'www.irmmaquinarias.com' }}</div>
        <div>Conserve su ticket</div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="separador"></div>
        <div>Ticket generado: {{ now()->format('d/m/Y H:i:s') }}</div>
        <div>{{ $tipoConfig['subtitulo'] ?? 'Comprobante emitido por máquina registradora' }}</div>
    </div>
</body>
</html>