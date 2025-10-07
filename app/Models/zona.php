<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zona extends Model
{
    protected $table = 'zona'; // Nombre real de la tabla
    protected $primaryKey = 'id_zona'; // Clave primaria
    protected $fillable = [
    'nombre',
    'id_region',
    'estatus', // Texto: 'Activo' o 'Inactivo'
];
    public $timestamps = false; // Si no usas created_at / updated_at

    // Relación con región
    public function region()
    {
        return $this->belongsTo(Region::class, 'id_region', 'id_region');
    }
}
