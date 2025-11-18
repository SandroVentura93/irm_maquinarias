<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'id_rol',
        'nombre',
        'usuario',
        'contrasena',
        'correo',
        'telefono',
        'activo',
    ];

    protected $hidden = [
        'contrasena',
        'remember_token',
    ];

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    /**
     * Override the default password field name.
     */
    public function getAuthPassword()
    {
        return $this->contrasena;
    }
}