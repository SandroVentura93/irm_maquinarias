<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_producto';

    protected $fillable = [
        'id_categoria',
        'id_marca',
        'id_proveedor',
        'codigo',
        'numero_parte',
        'descripcion',
        'modelo',
        'peso',
        'ubicacion',
        'stock_actual',
        'stock_minimo',
        'precio_compra',
        'precio_venta',
        'importado',
        'activo',
    ];
}