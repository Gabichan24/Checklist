<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permisoglobal extends Model
{
    use HasFactory;

    // Nombre exacto de la tabla
    protected $table = 'permisoglobal';

    // Nombre de la clave primaria
    protected $primaryKey = 'id_permiso';

    // Desactivar timestamps (si no tienes columnas created_at y updated_at)
    public $timestamps = false;

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'modulo',
        'accion',
    ];
}
