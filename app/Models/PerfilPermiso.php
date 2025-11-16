<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfilPermiso extends Model
{
    use HasFactory;

    //  Nombre exacto de tu tabla
    protected $table = 'perfilpermiso';

    // Clave primaria correcta
    protected $primaryKey = 'id_perfil_permiso';

    // Desactivar timestamps si no existen en tu tabla
    public $timestamps = false;

    //Campos que se pueden asignar
    protected $fillable = [
        'id_perfil',
        'id_permiso'
    ];
}

