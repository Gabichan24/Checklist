<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $table = 'empresa';
    protected $primaryKey = 'id_empresa';
    public $timestamps = false;

    protected $fillable = [
        'nombre_comercial',
        'razon_social',
        'rfc',
        'direccion',
        'codigo_postal',
        'correo',
        'telefono',
        'logo',
        'tolerancia',
        'tiempo_max_respuesta',
        'horario_notificaciones',
        'hora_ini'
    ];
}
