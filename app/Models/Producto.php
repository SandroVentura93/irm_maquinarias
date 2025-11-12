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

    // Relaciones
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class, 'id_marca', 'id_marca');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor', 'id_proveedor');
    }

    // Scopes útiles
    public function scopeActivos($query)
    {
        return $query->where('activo', 1);
    }

    public function scopeConStock($query)
    {
        return $query->where('stock_actual', '>', 0);
    }

    public function scopeBajoStock($query)
    {
        return $query->whereColumn('stock_actual', '<=', 'stock_minimo');
    }
}