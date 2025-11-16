<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    use HasFactory;

    protected $table = 'sucursal';
    protected $primaryKey = 'id_sucursal';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'id_zona',
        'identificador',
        'codigo_postal',
        'direccion',
        'latitud',
        'longitud',
        'radio',
        'estatus',
        'zona_horaria',
        'id_area', // guardamos los IDs de áreas
        'maps',    // NUEVO: URL de Google Maps
    ];

    // Relación con Zonas (una sucursal pertenece a una zona)
    public function zona()
    {
        return $this->belongsTo(Zona::class, 'id_zona', 'id_zona');
    }

    /**
     * Obtener las áreas asociadas como colección
     * Convierte el CSV de id_area en un array de objetos Area
     */
    public function getAreasAttribute()
    {
        if (!$this->id_area) {
            return collect();
        }

        $ids = explode(',', $this->id_area);

        return Area::whereIn('id_area', $ids)->get();
    }
}

