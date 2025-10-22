<?php

use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RegionesExport;
use App\Exports\ZonasExport;
use App\Exports\AreasExport;
use App\Exports\CategoriasExport;
use App\Exports\SucursalesExport;
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
Route::get('/categorias/export/xlsx', [CategoriasController::class, 'exportXlsx'])->name('categorias.export.xlsx');
Route::get('/categorias/export/csv', [CategoriasController::class, 'exportCsv'])->name('categorias.export.csv');

Route::resource('sucursales', SucursalesController::class);
Route::get('/sucursales', [SucursalesController::class, 'index'])->name('sucursales.index');
Route::get('/sucursales/create', [SucursalesController::class, 'create'])->name('sucursales.create');
Route::get('/sucursales/{id}/edit', [SucursalesController::class, 'edit'])->name('sucursales.edit');
Route::put('/sucursales/{id}', [SucursalesController::class, 'update'])->name('sucursales.update');
Route::put('/sucursales/toggle/{id}', [SucursalesController::class, 'toggle'])->name('sucursales.toggle');
Route::get('/sucursales/export/xlsx', [SucursalesController::class, 'exportXlsx'])->name('sucursales.export.xlsx');
Route::get('/sucursales/export/csv', [SucursalesController::class, 'exportCsv'])->name('sucursales.export.csv');
Route::post('/sucursales', [SucursalesController::class, 'store'])->name('sucursales.store');
Route::put('/sucursales/{id}', [SucursalesController::class, 'update'])->name('sucursales.update');

Route::prefix('usuarios')->group(function () {

    // Listado de usuarios
    Route::get('/', [UsuariosController::class, 'index'])->name('usuarios.index');

    // Exportar (opcional)
    Route::get('/export/xlsx', [UsuariosController::class, 'exportXlsx'])->name('usuarios.export.xlsx');
    Route::get('/export/csv', [UsuariosController::class, 'exportCsv'])->name('usuarios.export.csv');

    // Crear usuario
    Route::get('/create', [UsuariosController::class, 'create'])->name('usuarios.create');
    Route::post('/store', [UsuariosController::class, 'store'])->name('usuarios.store');

    // Editar usuario
    Route::get('/{id_usuario}/edit', [UsuariosController::class, 'edit'])->name('usuarios.edit');
    Route::put('/{id_usuario}', [UsuariosController::class, 'update'])->name('usuarios.update');

    // Eliminar usuario
    Route::delete('/{id_usuario}', [UsuariosController::class, 'destroy'])->name('usuarios.destroy');
    Route::get('/api/perfiles', [PerfilesController::class, 'listar'])
    ->name('api.perfiles');
    // Vacaciones de usuarios
    Route::get('/{id}/vacaciones', [UsuariosController::class, 'vacaciones'])->name('usuarios.vacaciones'); // GET vacaciones
    Route::post('/vacaciones/guardar', [UsuariosController::class, 'guardarVacacion'])->name('usuarios.vacaciones.guardar'); // POST guardar
    Route::put('/vacaciones/{id}', [UsuariosController::class, 'actualizarVacacion'])->name('usuarios.vacaciones.actualizar'); // PUT actualizar
    Route::delete('/vacaciones/{id}', [UsuariosController::class, 'destroyVacacion'])->name('usuarios.vacaciones.destroy');
    Route::patch('/vacaciones/{id}/toggle', [UsuariosController::class, 'toggleFinalizada'])->name('usuarios.vacaciones.toggle'); // PATCH toggle
    
    // Activar / Desactivar usuario (estatus)
    Route::put('/toggle/{id_perfiles}', [PerfilesController::class, 'toggle'])->name('perfiles.toggle');
});

  Route::post('/store', [UsuariosController::class, 'store'])->name('usuarios.store');
// Logros
Route::get('/logros', [LogrosController::class, 'index'])->name('logros.index');


// RUTAS PERFIL
Route::resource('perfiles', PerfilesController::class)->except(['show']);
Route::prefix('perfiles')->group(function() {
    Route::get('perfiles', [PerfilesController::class, 'index'])->name('perfiles.index');
    Route::post('perfiles', [PerfilesController::class, 'store'])->name('perfiles.store');
    Route::get('perfiles/{id}/edit', [PerfilesController::class, 'edit'])->name('perfiles.edit');
    Route::put('perfiles/{id}', [PerfilesController::class, 'update'])->name('perfiles.update');
    Route::delete('perfiles/{id}', [PerfilesController::class, 'destroy'])->name('perfiles.destroy');

    // Permisos
    Route::get('/{id}/permisos', [PerfilesController::class, 'permisos'])->name('perfiles.permisos');
    Route::post('/{id}/permisos', [PerfilesController::class, 'guardarPermisos'])->name('perfiles.guardarPermisos');
});