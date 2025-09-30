<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $table = 'region'; // Nombre de tu tabla
    protected $primaryKey = 'id_region'; // Indica la clave primaria real
    protected $fillable = ['nombre', 'estados', 'estatus'];
    public $timestamps = false; // Desactiva created_at y updated_at si no los tienes
}
