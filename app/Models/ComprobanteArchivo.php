<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprobanteArchivo extends Model
{
    use HasFactory;

    protected $table = 'comprobante_archivos';

    protected $primaryKey = 'id_archivo';

    protected $fillable = [
        'id_comprobante',
        'tipo',
        'nombre_archivo',
        'ruta',
        'tamanio_bytes'
    ];

    public function comprobante()
    {
        return $this->belongsTo(ComprobanteElectronico::class, 'id_comprobante');
    }
}