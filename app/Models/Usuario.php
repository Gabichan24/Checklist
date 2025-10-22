<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

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
        'nivel'
    ];

    public function perfil()
    {
        return $this->belongsTo(Perfil::class, 'id_perfil');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'id_sucursal');
    }
    /**
     * Relación con perfil superior
     */
    public function superiorUsuario()
    {
        return $this->belongsTo(Usuario::class, 'superior');
    }
}


