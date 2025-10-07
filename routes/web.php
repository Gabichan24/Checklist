<?php

use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RegionesExport;
use App\Exports\ZonasExport;
use App\Exports\AreasExport;
use App\Exports\CategoriasExport;
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
Route::resource('regiones', App\Http\Controllers\RegionesController::class);
Route::get('/regiones', [RegionesController::class, 'index'])->name('regiones.index');
Route::get('/regiones/create', [RegionesController::class, 'create'])->name('regiones.create');
Route::post('/regiones', [RegionesController::class, 'store'])->name('regiones.store');
Route::put('/regiones/toggle/{id}', [RegionesController::class, 'toggle'])->name('regiones.toggle');
Route::get('/regiones/export/xlsx', [RegionesController::class, 'exportXlsx'])->name('regiones.export.xlsx');
Route::get('/regiones/export/csv', [RegionesController::class, 'exportCsv'])->name('regiones.export.csv');

Route::resource('zonas', App\Http\Controllers\ZonasController::class);
Route::get('/zonas', [ZonasController::class, 'index'])->name('zonas.index');
Route::get('/zonas/create', [ZonasController::class, 'create'])->name('zonas.create');
Route::post('/zonas', [ZonasController::class, 'store'])->name('zonas.store');
Route::put('/zonas/toggle/{id}', [ZonasController::class, 'toggle'])->name('zonas.toggle');
Route::get('/zonas/export/xlsx', [ZonasController::class, 'exportXlsx'])->name('zonas.export.xlsx');
Route::get('/zonas/export/csv', [ZonasController::class, 'exportCsv'])->name('zonas.export.csv');

Route::resource('areas', AreasController::class);
Route::get('/areas', [AreasController::class, 'index'])->name('areas.index');
Route::get('/areas/create', [AreasController::class, 'create'])->name('areas.create');
Route::post('/areas', [AreasController::class, 'store'])->name('areas.store');
Route::put('/areas/toggle/{id}', [AreasController::class, 'toggle'])->name('areas.toggle');
Route::get('/areas/export/excel', [AreasController::class, 'exportExcel'])->name('areas.export.excel');
Route::get('/areas/export/csv', [AreasController::class, 'exportCsv'])->name('areas.export.csv');

Route::get('/categorias', [CategoriasController::class, 'index'])->name('categorias.index');
Route::get('/categorias/create', [CategoriasController::class, 'create'])->name('categorias.create');
Route::post('/categorias', [CategoriasController::class, 'store'])->name('categorias.store');
Route::get('/categorias/{id}/edit', [CategoriasController::class, 'edit'])->name('categorias.edit');
Route::put('/categorias/{id}', [CategoriasController::class, 'update'])->name('categorias.update');
Route::put('/categorias/toggle/{id}', [CategoriasController::class, 'toggle'])->name('categorias.toggle');

// ✅ Exportaciones
Route::get('/categorias/export/xlsx', [CategoriasController::class, 'exportXlsx'])->name('categorias.export.xlsx');
Route::get('/categorias/export/csv', [CategoriasController::class, 'exportCsv'])->name('categorias.export.csv');


Route::get('/sucursales', [SucursalesController::class, 'index'])->name('sucursales.index');

// Logros
Route::get('/logros', [LogrosController::class, 'index'])->name('logros.index');
