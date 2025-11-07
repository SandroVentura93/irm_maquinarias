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
}
