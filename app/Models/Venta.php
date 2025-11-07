<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'ventas';

    protected $fillable = [
        'id_cliente',
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
}