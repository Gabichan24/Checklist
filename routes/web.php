// routes/web.php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\PerfilesController;
use App\Http\Controllers\RegionesController;
use App\Http\Controllers\ZonasController;
use App\Http\Controllers\AreasController;
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\SucursalesController;
use App\Http\Controllers\LogrosController;

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

// Configuración general
Route::get('/config-general', [ConfigController::class, 'index'])->name('config.general');

// Staff
Route::get('/usuarios', [UsuariosController::class, 'index'])->name('usuarios.index');
Route::get('/perfiles', [PerfilesController::class, 'index'])->name('perfiles.index');

// Estructura
Route::get('/regiones', [RegionesController::class, 'index'])->name('regiones.index');
Route::get('/zonas', [ZonasController::class, 'index'])->name('zonas.index');
Route::get('/areas', [AreasController::class, 'index'])->name('areas.index');
Route::get('/categorias', [CategoriasController::class, 'index'])->name('categorias.index');
Route::get('/sucursales', [SucursalesController::class, 'index'])->name('sucursales.index');

// Logros
Route::get('/logros', [LogrosController::class, 'index'])->name('logros.index');
