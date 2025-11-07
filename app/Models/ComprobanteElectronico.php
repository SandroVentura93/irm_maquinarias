<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprobanteElectronico extends Model
{
    use HasFactory;

    protected $table = 'comprobantes_electronicos';

    protected $primaryKey = 'id_comprobante';

    protected $fillable = [
        'id_venta',
        'id_tipo_comprobante',
        'serie',
        'numero',
        'cliente_ruc',
        'cliente_razon_social',
        'fecha_emision',
        'monto_subtotal',
        'monto_igv',
        'monto_total',
        'moneda_id',
        'xml_nombre',
        'xml_hash',
        'pdf_nombre',
        'estado',
        'respuesta_sunat',
        'qr',
        'usuario_genero'
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'id_venta');
    }

    public function tipoComprobante()
    {
        return $this->belongsTo(TipoComprobante::class, 'id_tipo_comprobante');
    }
}