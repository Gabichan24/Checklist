<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    use HasFactory;

    // Nombre exacto de la tabla
    protected $table = 'perfil'; // ← tu tabla se llama "perfil"

    // Nombre de la clave primaria
    protected $primaryKey = 'id_perfil'; // si tu PK se llama id_perfil

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre_perfil',
        'descripcion',
        'superior',
        'nivel_asignacion',
        'asignacion_multiple',
        'estatus',
    ];

    // Desactivar timestamps si tu tabla no tiene created_at/updated_at
    public $timestamps = false;

    // Relación con permisos asignados (muchos a muchos)
    public function permisos()
    {
        return $this->belongsToMany(
            Permisoglobal::class,
            'id_perfil_permiso',    // nombre de la tabla pivote
            'id_perfil',         // FK de esta tabla en pivote
            'id_permiso'         // FK de la tabla permiso en pivote
        );
    }
}


