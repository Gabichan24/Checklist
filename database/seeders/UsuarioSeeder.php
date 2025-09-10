<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('usuario')->insert([
            'nombre' => 'Admin',
            'apellidos' => 'Principal',
            'id_perfil' => 1,
            'superior' => null,
            'id_sucursal' => 1,
            'correo' => 'admin@gmail.com',
            'telefono' => '1234567890',
            'foto' => null,
            'reportes_adicionales' => null,
            'password' => Hash::make('admi123'),
            'estatus' => 1,
            'ultima_conexion' => now(),
            'sistema' => 1,
            'app' => 1,
            'nivel' => 1,
        ]);
    }
}
