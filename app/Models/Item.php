<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    // Nombre de la tabla
    protected $table = 'items';

    // Primary Key
    protected $primaryKey = 'id_item';

    // Para que no use created_at / updated_at
    public $timestamps = false;

    // Campos que se pueden llenar
    protected $fillable = [
        'id_checklist',
        'nombre_item',
        'puntuacion',
        'plan_accion',
        'obligatorio',
        'tipo_evidencias',
        'tipo_item'
    ];

    /* =======================================
       RELACIÓN CON CHECKLIST
    ======================================= */
    public function checklist()
    {
        return $this->belongsTo(Checklist::class, 'id_checklist', 'id_checklist');
    }
}
