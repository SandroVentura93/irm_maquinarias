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

    // Flag para controlar el estilo sin separadores (una sola hoja)
    if (isset($singlePage)) {
        $singlePage = (bool)$singlePage;
    } elseif (isset($datos) && array_key_exists('singlePage', $datos)) {
        $singlePage = (bool)$datos['singlePage'];
    } else {
        $singlePage = false;
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

    /* Modo sin separadores: quitar líneas y compactar filas */
    .std-details.no-separators { border-collapse: collapse; margin: 14px 0; }
    .std-details.no-separators th {
        border: none !important;
        padding: 5px 4px;
        font-size: 10px;
        white-space: normal;
        line-height: 1.2;
    }
    .std-details.no-separators td {
        border: none !important;
        padding: 4px 3px;
        line-height: 1.15;
        font-size: 10px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .std-details.no-separators td.txt-left,
    .std-details.no-separators td.txt-left strong {
        white-space: nowrap;
        display: block;
    }
    .std-details.no-separators tr { background: transparent !important; }
    .std-details.no-separators tr:nth-child(even) { background: rgba(44, 90, 160, 0.05) !important; }

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
    // Pre-chequeo: detectar si los subtotales por línea parecen estar en otra moneda
    // comparando la suma de subtotales de líneas vs el subtotal de la venta y el tipo de cambio.
    $sumSubRaw = 0.0;
    foreach (($lineas ?? []) as $d2) {
        $c2 = (float)($d2->cantidad ?? 0);
        $pf2 = isset($d2->precio_final) ? (float)$d2->precio_final : null;
        $sub2 = isset($d2->subtotal) ? (float)$d2->subtotal : null;
        if ($sub2 === null || $sub2 <= 0) {
            if ($pf2 !== null && $pf2 > 0) {
                $sub2 = $pf2 * $c2;
            } else {
                $pu2 = (float)($d2->precio_unitario ?? 0);
                $desc2 = (float)($d2->descuento_porcentaje ?? 0);
                $net2 = $pu2 * (1 - $desc2 / 100.0);
                $sub2 = $net2 * $c2;
            }
        }
        $sumSubRaw += $sub2;
    }
    $convertDirection = null; // 'usd2pen' | 'pen2usd' | null
    if ($tc && $tc > 0 && isset($venta->subtotal) && $sumSubRaw > 0) {
        if ($codigoIso === 'PEN') {
            $ratio = ((float)$venta->subtotal) / $sumSubRaw;
            if ($ratio > 0 && abs($ratio - $tc) / $tc < 0.06) { // tolerancia ~6%
                $convertDirection = 'usd2pen';
            }
        } elseif ($codigoIso === 'USD') {
            $ratio = $sumSubRaw / ((float)$venta->subtotal);
            if ($ratio > 0 && abs($ratio - $tc) / $tc < 0.06) {
                $convertDirection = 'pen2usd';
            }
        }
    }
@endphp

<table class="std-details{{ $singlePage ? ' no-separators' : '' }}">
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
                // Derivar los valores mostrados desde los campos persistidos (robusto frente a cambios de moneda):
                // - Si hay precio_final, usarlo como Valor neto precio unitario.
                // - Si no, pero existe subtotal y cantidad>0, netUnit = subtotal/cantidad.
                // - En último caso, calcular desde precio_unitario y descuento_porcentaje.
                $puRaw = (float)($detalle->precio_unitario ?? 0);
                $descPct = (float)($detalle->descuento_porcentaje ?? 0);
                $netUnit = null;
                if (isset($detalle->precio_final) && (float)$detalle->precio_final > 0) {
                    $netUnit = (float)$detalle->precio_final;
                } elseif (isset($detalle->subtotal) && $cant > 0 && (float)$detalle->subtotal > 0) {
                    $netUnit = (float)$detalle->subtotal / $cant;
                } else {
                    $netUnit = $puRaw * (1 - ($descPct / 100.0));
                }
                // Reconstruir Valor precio unitario a partir del neto y el porcentaje de descuento (cuando aplique)
                if ($descPct > 0 && $descPct < 100) {
                    $pu = $netUnit / (1 - ($descPct / 100.0));
                } else {
                    $pu = $puRaw ?: $netUnit;
                }
                $descUnit = max($pu - $netUnit, 0);
                // Valor venta neto total: preferir 'total' si existe; si no, netUnit * cantidad
                if (isset($detalle->total) && (float)$detalle->total > 0) {
                    $netTotal = (float)$detalle->total;
                } else {
                    $netTotal = $netUnit * $cant;
                }
                // Aplicar conversión dinámica si las líneas aparentan estar en otra moneda que la del comprobante
                if ($convertDirection === 'usd2pen' && $tc > 0) {
                    $pu = round($pu * $tc, 6);
                    $netUnit = round($netUnit * $tc, 6);
                    $descUnit = round($descUnit * $tc, 6);
                    $netTotal = round($netTotal * $tc, 6);
                } elseif ($convertDirection === 'pen2usd' && $tc > 0) {
                    $pu = round($pu / $tc, 6);
                    $netUnit = round($netUnit / $tc, 6);
                    $descUnit = round($descUnit / $tc, 6);
                    $netTotal = round($netTotal / $tc, 6);
                }

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
