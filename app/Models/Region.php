<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $table = 'region'; // Nombre exacto de tu tabla

    protected $fillable = ['nombre', 'estados', 'region_padre_id', 'estatus'];
    public $timestamps = false; // <- Esto desactiva created_at y updated_at
}
