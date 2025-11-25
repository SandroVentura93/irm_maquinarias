<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedores';

    protected $primaryKey = 'id_proveedor';

    protected $fillable = [
        'razon_social',
        'tipo_documento',
        'numero_documento',
        'contacto',
        'telefono',
        'correo',
        'direccion',
        'id_ubigeo',
        'activo',
    ];

    /**
     * Relación con Ubigeo
     */
    public function ubigeo()
    {
        return $this->belongsTo(Ubigeo::class, 'id_ubigeo', 'id_ubigeo');
    }

    /**
     * Relación con Compras
     */
    public function compras()
    {
        return $this->hasMany(Compra::class, 'id_proveedor', 'id_proveedor');
    }
}

