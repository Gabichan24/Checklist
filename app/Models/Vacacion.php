<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Vacacion extends Model
{
    use HasFactory;

    protected $table = 'vacaciones';
    protected $primaryKey = 'id_vacacion';

    protected $fillable = [
        'id_usuario',
        'fecha_inicio',
        'fecha_fin',
        'descripcion',
        'finalizada',
    ];

    // Relación con Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}
