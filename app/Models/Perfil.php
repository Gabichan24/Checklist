<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    use HasFactory;

    protected $table = 'perfil';
    protected $primaryKey = 'id_perfil';
    public $timestamps = false;

    protected $fillable = [
        'nombre_perfil',
        'descripcion',
        'superior',
        'nivel_asignacion',
        'asignacion_multiple',
        'estatus',
    ];

    public function permisos()
    {
        return $this->hasMany(PerfilPermiso::class, 'id_perfil', 'id_perfil');
    }
}

