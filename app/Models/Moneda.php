<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Moneda extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_moneda';

    protected $fillable = [
        'nombre',
        'simbolo',
        'codigo_iso'
    ];

    public $timestamps = false;
}
