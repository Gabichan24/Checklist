<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuario'; // Nombre de la tabla
    protected $primaryKey = 'id_usuario'; // Clave primaria
    public $timestamps = false; // Sin created_at/updated_at
    protected $fillable = [
        'nombre',
        'apellidos',
        'id_perfil',
        'superior',
        'id_sucursal',
        'correo',
        'telefono',        
        'foto',
        'reportes_adicionales',
        'password',
        'estatus',
        'ultima_conexion',
        'sistema',
        'app',
        'nivel',
    ];

    // Indica a Laravel que use 'contraseña' en lugar de 'password'
    public function getAuthPassword()
    {
        return $this->contraseña;
    }

    // Indica a Laravel que use 'correo' en lugar de 'email'
    public function getAuthIdentifierName()
    {
        return 'correo';
    }
}