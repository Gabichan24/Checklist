<?php

use Illuminate\Support\Facades\Route;
use App\Exports\RegionesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\PerfilesController;
use App\Http\Controllers\RegionesController;
use App\Http\Controllers\ZonasController;
use App\Http\Controllers\AreasController;
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\SucursalesController;
use App\Http\Controllers\LogrosController;

// Ruta para mostrar el formulario de login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// Ruta para procesar el login
Route::post('/login', [LoginController::class, 'login']);

// Ruta para cerrar sesión
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

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
Route::get('/regiones/create', [RegionesController::class, 'create'])->name('regiones.create');
Route::post('/regiones', [RegionesController::class, 'store'])->name('regiones.store');
Route::put('regiones/{id}', [RegionesController::class, 'update'])->name('regiones.update');
Route::put('/regiones/{id}/toggle', [RegionesController::class, 'toggleEstatus'])->name('regiones.toggle');
Route::get('/zonas', [ZonasController::class, 'index'])->name('zonas.index');
Route::get('/zonas/create', [ZonasController::class, 'create'])->name('zonas.create');
Route::get('/areas', [AreasController::class, 'index'])->name('areas.index');
Route::get('/areas/create', [AreasController::class, 'create'])->name('areas.create');
Route::get('/categorias', [CategoriasController::class, 'index'])->name('categorias.index');
Route::get('/sucursales', [SucursalesController::class, 'index'])->name('sucursales.index');

// Logros
Route::get('/logros', [LogrosController::class, 'index'])->name('logros.index');

//xsls
Route::get('/regiones/export/xlsx', [RegionesController::class, 'exportXlsx'])->name('regiones.export.xlsx');

Route::get('/regiones/export/csv', [RegionesController::class, 'exportCsv'])->name('regiones.export.csv');