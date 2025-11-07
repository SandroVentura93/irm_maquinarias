<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parametro extends Model
{
    use HasFactory;

    protected $table = 'parametros';

    protected $primaryKey = 'id_parametro';

    protected $fillable = [
        'nombre',
        'valor',
        'descripcion',
    ];
}