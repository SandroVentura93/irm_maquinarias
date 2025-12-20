<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'ventas';
    protected $primaryKey = 'id_venta';


    protected $fillable = [
        'id_cliente',
        'id_vendedor',
        'id_moneda',
        'tipo_cambio',
        'id_tipo_comprobante',
        'serie',
        'numero',
        'fecha',
        'subtotal',
        'igv',
        'total',
        'saldo',
        'xml_hash',
        'xml_nombre',
        'xml_estado',
        'qr_hash'
    ];
    // Relación con pagos de la venta
    public function pagos()
    {
        return $this->hasMany(PagoVenta::class, 'id_venta');
    }

    protected $casts = [
        'fecha' => 'datetime',
        'subtotal' => 'decimal:2',
        'igv' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    public function vendedor()
    {
        return $this->belongsTo(\App\Models\Usuario::class, 'id_vendedor', 'id_usuario');
    }

    public function tipoComprobante()
    {
        return $this->belongsTo(TipoComprobante::class, 'id_tipo_comprobante');
    }

    public function moneda()
    {
        return $this->belongsTo(Moneda::class, 'id_moneda');
    }

    public function detalleVentas()
    {
        return $this->hasMany(DetalleVenta::class, 'id_venta');
    }

    /**
     * Alias for detalleVentas to keep backward compatibility with callers
     * that use the relation name "detalles".
     */
    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class, 'id_venta');
    }

    public function comprobanteElectronico()
    {
        return $this->hasOne(ComprobanteElectronico::class, 'id_venta');
    }

    /**
     * Accessor para obtener serie_numero concatenando serie y numero
     */
    public function getSerieNumeroAttribute()
    {
        if (!empty($this->serie) && !empty($this->numero)) {
            return $this->serie . '-' . $this->numero;
        }
        return '';
    }

    /**
     * Normaliza etiquetas de moneda a códigos estándar.
     */
    private function normalizeMoneda($m)
    {
        $raw = strtoupper(trim((string) $m));
        if ($raw === '$' || str_contains($raw, 'USD') || str_contains($raw, 'DOLAR') || str_contains($raw, 'DÓLAR')) {
            return 'USD';
        }
        return 'PEN';
    }

    /**
     * Calcula el saldo actual en tiempo real, replicando la lógica de `edit.blade`:
     * - Si la moneda del pago coincide con la de la venta, se suma tal cual.
     * - Si difiere, se convierte usando `tipo_cambio`:
     *   - Venta en USD: pagos en PEN se convierten a USD (monto / tipo_cambio).
     *   - Venta en PEN: pagos en USD se convierten a PEN (monto * tipo_cambio).
     * - Si `tipo_cambio` es 0 o no válido, se suma el monto sin convertir (fallback).
     */
    public function calcularSaldoActual(): float
    {
        $idMonedaVenta = $this->id_moneda; // 1=PEN, 2=USD
        $codigoVenta = ($idMonedaVenta === 2) ? 'USD' : 'PEN';
        $tc = (float) ($this->tipo_cambio ?? 0);

        $totalPagadoConv = 0.0;
        foreach ($this->pagos as $pago) {
            $monto = (float) ($pago->monto ?? 0);
            $monedaPago = $this->normalizeMoneda($pago->moneda ?? 'PEN');

            if ($codigoVenta === 'USD') {
                // Venta en USD: convertir pagos en PEN a USD
                if ($monedaPago === 'PEN' && $tc > 0) {
                    $totalPagadoConv += ($monto / $tc);
                } else {
                    $totalPagadoConv += $monto;
                }
            } else {
                // Venta en PEN: convertir pagos en USD a PEN
                if ($monedaPago === 'USD' && $tc > 0) {
                    $totalPagadoConv += ($monto * $tc);
                } else {
                    $totalPagadoConv += $monto;
                }
            }
        }

        $saldo = max(round(((float) $this->total) - $totalPagadoConv, 2), 0);
        return $saldo;
    }

    /**
     * Accessor: `saldo_calculado` para usar en vistas sin depender del almacenado.
     */
    public function getSaldoCalculadoAttribute(): float
    {
        return $this->calcularSaldoActual();
    }
}