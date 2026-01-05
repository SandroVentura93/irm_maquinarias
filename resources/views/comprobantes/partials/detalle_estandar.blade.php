@php
    // Resolve currency and symbol from various possible inputs
    $codigoIso = isset($datos['moneda']['iso']) ? strtoupper($datos['moneda']['iso'])
        : (isset($moneda) && is_object($moneda) ? strtoupper($moneda->codigo_iso ?? 'PEN')
        : (is_object($venta->moneda) ? strtoupper($venta->moneda->codigo_iso ?? 'PEN')
        : (is_string($venta->moneda ?? null) ? strtoupper($venta->moneda) : 'PEN')));
    $simbolo = isset($datos['moneda']['simbolo']) ? $datos['moneda']['simbolo'] : ($codigoIso === 'USD' ? '$' : 'S/');

    // Tipo de cambio referencia
    $tc = $venta->tipo_cambio ?? ($tipoCambio ?? null);

    // Fuente de líneas: preferir $detalles; si no, usar relaciones en Venta
    // Compatibles: detalleVentas(), detalles()
    $lineas = isset($detalles)
        ? $detalles
        : (
            isset($venta)
                ? ($venta->detalleVentas ?? ($venta->detalles ?? []))
                : []
          );

    // Mostrar/ocultar código (compatibilidad con controlador y $datos). Respeta valor ya presente.
    if (isset($mostrarCodigoParte)) {
        $mostrarCodigoParte = (bool)$mostrarCodigoParte; // viene desde el controlador o la vista padre
    } elseif (isset($datos) && array_key_exists('mostrarCodigoParte', $datos)) {
        $mostrarCodigoParte = (bool)$datos['mostrarCodigoParte'];
    } else {
        $mostrarCodigoParte = true; // por defecto mostrar
    }

    $totalPeso = 0;
    $baseCalc = 0;
@endphp

<style>
    .std-details { width: 100%; border-collapse: collapse; margin: 20px 0; }
    .std-details th { background: #2c5aa0; color: #fff; padding: 8px 6px; text-align: center; font-size: 11px; border: 1px solid #1f4173; }
    .std-details td { padding: 6px; border: 1px solid #dee2e6; text-align: center; font-size: 11px; }
    .std-details .txt-left { text-align: left; }
    .std-details tr:nth-child(even) { background: #f8f9fa; }

    .std-totals { width: 100%; margin-top: 10px; display: table; }
    .std-totals .right { display: table-cell; width: 45%; vertical-align: top; }
    .std-totals .left { display: table-cell; width: 55%; vertical-align: top; padding-right: 12px; }
    .std-totals table { width: 100%; border-collapse: collapse; }
    .std-totals td { padding: 6px 10px; border: 1px solid #dee2e6; font-size: 11px; }
    .std-totals .label { background: #f8f9fa; font-weight: bold; text-align: right; }
    .std-totals .final { background: #2c5aa0; color: #fff; font-weight: bold; }
    .std-totals .w50 { width: 50%; }
    .std-badge { display:inline-block; padding: 1px 6px; background:#2c5aa0; color:#fff; border-radius: 4px; font-size:10px; }
</style>

@php
    $wDesc = $mostrarCodigoParte ? '34%' : '46%';
@endphp

<table class="std-details">
    <thead>
        <tr>
            <th style="width:6%">ITEM</th>
            @if($mostrarCodigoParte)
                <th style="width:12%">CÓDIGO</th>
            @endif
            <th style="width:{{ $wDesc }}">Descripción</th>
            <th style="width:10%">Marca</th>
            <th style="width:8%">Peso</th>
            <th style="width:8%">Cantidad</th>
            <th style="width:11%">Valor precio unitario</th>
            <th style="width:11%">Descuento unitario</th>
            <th style="width:11%">Valor neto precio unitario</th>
            <th style="width:12%">Valor venta neto total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($lineas as $idx => $detalle)
            @php
                $prod = $detalle->producto ?? null;
                $codigo = $prod->codigo ?? '';
                $pn = $prod->numero_parte ?? '';
                $marca = $prod->marca->nombre ?? ($prod->marca ?? '');
                $peso = (float)($prod->peso ?? 0);
                $cant = (float)($detalle->cantidad ?? 0);
                // Precio unitario DEBE provenir del detalle guardado en la venta.
                // Este valor ya fue convertido por el backend a la moneda del comprobante
                // usando el tipo de cambio manual ingresado.
                $pu = (float)($detalle->precio_unitario ?? 0);
                $descPct = (float)($detalle->descuento_porcentaje ?? 0);
                $descUnit = $pu * $descPct / 100.0;
                $netUnit = $pu - $descUnit;
                // Si existe precio_final en el detalle, preferirlo para exactitud
                if (isset($detalle->precio_final)) {
                    $netUnit = (float)$detalle->precio_final;
                }
                $netTotal = $netUnit * $cant;
                $totalPeso += $peso * $cant;
                $baseCalc += $netTotal;
            @endphp
            <tr>
                <td>{{ $idx + 1 }}</td>
                @if($mostrarCodigoParte)
                    <td>
                        <div>{{ $codigo ?: '-' }}</div>
                    </td>
                @endif
                <td class="txt-left">
                    <strong>{{ $prod->descripcion ?? 'Producto' }}</strong>
                </td>
                <td>{{ $marca ?: '-' }}</td>
                <td>{{ number_format($peso, 2) }} kg</td>
                <td>{{ number_format($cant, 2) }}</td>
                <td>{{ $simbolo }} {{ number_format($pu, 2) }}</td>
                <td>{{ $simbolo }} {{ number_format($descUnit, 2) }}</td>
                <td>{{ $simbolo }} {{ number_format($netUnit, 2) }}</td>
                <td>{{ $simbolo }} {{ number_format($netTotal, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="std-totals">
    <div class="left">
        <table>
            <tr>
                <td class="label w50">TOTAL PESO</td>
                <td>{{ number_format($totalPeso, 2) }} kg</td>
            </tr>
        </table>
    </div>
    <div class="right">
        @php
            // Usar los totales del modelo si existen; si no, calcular desde baseCalc
            $subtotal = isset($venta->subtotal) ? (float)$venta->subtotal : $baseCalc;
            $total = isset($venta->total) ? (float)$venta->total : $baseCalc * 1.18; // fallback con IGV 18%
            $igv = $total - $subtotal;
        @endphp
        <table>
            <tr>
                <td class="label w50">SUBTOTAL</td>
                <td>{{ $simbolo }} {{ number_format($subtotal, 2) }}</td>
            </tr>
            <tr>
                <td class="label">IGV (18%)</td>
                <td>{{ $simbolo }} {{ number_format($igv, 2) }}</td>
            </tr>
            <tr class="final">
                <td class="final">TOTAL {{ $codigoIso }}</td>
                <td class="final">{{ $simbolo }} {{ number_format($total, 2) }}</td>
            </tr>
        </table>
    </div>
</div>
