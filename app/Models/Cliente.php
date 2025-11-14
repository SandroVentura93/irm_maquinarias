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
        'nombre',
        'direccion',
        'id_ubigeo',
        'telefono',
        'correo',
        'activo',
        'tipo_cliente'
    ];

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'id_cliente');
    }

    /**
     * Obtiene el nombre completo del cliente (nombre o razón social)
     */
    public function getNombreCompletoAttribute()
    {
        return $this->nombre;
    }

    /**
     * Obtiene el tipo de cliente basado en el documento
     */
    public function getTipoDocumentoAttribute()
    {
        $longitud = strlen($this->numero_documento);
        if ($longitud == 8) {
            return 'DNI';
        } elseif ($longitud == 11) {
            return 'RUC';
        }
        return 'OTRO';
    }
}
