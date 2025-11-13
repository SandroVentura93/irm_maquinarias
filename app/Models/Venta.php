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
        'id_tipo_comprobante',
        'serie',
        'numero',
        'fecha',
        'subtotal',
        'igv',
        'total',
        'xml_hash',
        'xml_nombre',
        'xml_estado',
        'qr_hash'
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
}