<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfilPermiso extends Model
{
    use HasFactory;

    protected $table = 'perfilpermiso';
    protected $primaryKey = 'id_perfil_permiso';
    public $timestamps = false;

    protected $fillable = [
        'id_perfil',
        'id_permiso',
    ];

    public function perfil()
    {
        return $this->belongsTo(Perfil::class, 'id_perfil', 'id_perfil');
    }
}
