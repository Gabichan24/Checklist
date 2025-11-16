<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';

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
    'sistema',
    'app',
    'nivel',
    'ultima_conexion',        // ⬅️ nueva columna para fecha y hora
    'ultimo_dispositivo',     // ⬅️ nueva columna para el nombre del dispositivo
    'ultimo_navegador',       // ⬅️ nueva columna para navegador y versión
];

    protected $hidden = [
        'password',
    ];

    public function perfil()
    {
        return $this->belongsTo(Perfil::class, 'id_perfil');
    }

    public function vacaciones()
    {
        return $this->hasMany(Vacacion::class, 'id_usuario', 'id_usuario');
    }
}



