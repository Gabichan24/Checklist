<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    use HasFactory;

    protected $table = 'tabla_checklist';
    protected $primaryKey = 'id_checklist';
    public $timestamps = false;

    protected $fillable = [
        'nombre_checklist',
        'id_categoria',
        'id_area',
        'puntuacion_total',
        'creado_por',
        'estado',
        'autorizado_por',
        'fecha_autorizacion',
        'id_usuario',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'id_area', 'id_area');
    }
    public function items()
    {
        return $this->hasMany(Item::class, 'id_checklist', 'id_checklist');
    }
    
}
