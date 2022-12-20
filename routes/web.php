<?php

use App\Http\Controllers\DocumentosController;
use App\Http\Controllers\FirmaController;
use App\Http\Controllers\NotificacionesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/storage-link', function () {
    return Artisan::call('storage:link');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/notificaciones-json', [NotificacionesController::class, 'index'])->name('notificaciones.index');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/todas-las-notificaciones', [NotificacionesController::class, 'all'])->name('notificaciones.all');
    # Modulo Documentos - Administrador
    Route::get('/documentos', [DocumentosController::class, 'index'])->name('documentos.index');
    Route::get('/documentos/nuevo-documento', [DocumentosController::class, 'create'])->name('documentos.create');
    Route::post('/documentos/guardar-documento', [DocumentosController::class, 'store'])->name('documentos.store');
    Route::get('/documentos/{id}/firmar-documento', [DocumentosController::class, 'firmar'])->name('documentos.firmar');
    Route::post('/documentos/{id}/completar-firma-documento', [DocumentosController::class, 'completarFirma'])->name('documentos.completar');
    Route::get('/documentos/{id}/asignar-documento', [DocumentosController::class, 'asignar'])->name('documentos.asignar');
    Route::post('/documentos/{id}/asignar-y-notificar', [DocumentosController::class, 'enviarNotificacion'])->name('documentos.notificar');
    Route::delete('/documentos/{id}/eliminar-documento', [DocumentosController::class, 'eliminar'])->name('documentos.eliminar');

    # Modulo Documentos - Operador
    Route::get('/mis-documentos', [FirmaController::class, 'index'])->name('misdocumentos.index');
    Route::get('/mis-documentos/{id}/firmar', [FirmaController::class, 'firmar'])->name('misdocumentos.firmar-documento');
    Route::post('/mis-documentos/{id}/completar-firma', [FirmaController::class, 'completarFirma'])->name('misdocumentos.completar');


    # Modulo Usuarios
    Route::get('/usuarios', [UsersController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/nuevo-usuario', [UsersController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios/guardar-usuario', [UsersController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/{id}/editar-usuario', [UsersController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{id}/actualizar-usuario', [UsersController::class, 'update'])->name('usuarios.update');
    Route::post('/usuarios/{id}/eliminar-usuario', [UsersController::class, 'destroy'])->name('documentos.destroy');
});

require __DIR__.'/auth.php';
