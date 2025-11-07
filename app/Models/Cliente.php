<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $primaryKey = 'id_cliente';

    protected $fillable = [
        'tipo_documento',
        'numero_documento',
        'razon_social',
        'nombre',
        'direccion',
        'id_ubigeo',
        'telefono',
        'correo',
        'activo',
        'tipo_cliente'
    ];

    public function compras()
    {
        return $this->hasMany(Compra::class, 'id_cliente');
    }
}
