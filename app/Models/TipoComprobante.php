<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoComprobante extends Model
{
    use HasFactory;

    protected $table = 'tipo_comprobantes';

    protected $primaryKey = 'id_tipo_comprobante';

    public $timestamps = false;

    protected $fillable = [
        'codigo_sunat',
        'descripcion'
    ];
}