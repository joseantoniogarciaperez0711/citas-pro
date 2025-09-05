<?php

use App\Http\Controllers\CategoriaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\CitasController;
use App\Http\Controllers\EmpleadoController;




Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


// routes/web.php
use App\Http\Controllers\ProfileBusinessLogoController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/user/business-logo',   [ProfileBusinessLogoController::class, 'update'])->name('profile.business-logo.update');
    Route::delete('/user/business-logo', [ProfileBusinessLogoController::class, 'destroy'])->name('profile.business-logo.destroy');
});


Route::middleware(['auth', 'verified'])
    ->prefix('app')->name('app.')
    ->group(function () {
        Route::get('/',          [CitasController::class, 'dashboard'])->name('dashboard');
        Route::get('/citas',     [CitasController::class, 'citas'])->name('citas');
        Route::get('/clientes',  [CitasController::class, 'clients'])->name('clients');
        Route::get('/ajustes',   [CitasController::class, 'settings'])->name('settings');

        // ✅ VISTA (coincide con resources/views/menu/servicios.blade.php)
        Route::get('/servicios', fn() => view('menu.servicios'))->name('servicios');

        // ✅ JSON servicios (para que Alpine cargue la lista)
        Route::get('/servicios/lista', [ServicioController::class, 'index'])->name('servicios.index');
        Route::post('/servicios',             [ServicioController::class, 'store'])->name('servicios.store');
        Route::put('/servicios/{servicio}',   [ServicioController::class, 'update'])->name('servicios.update');
        Route::delete('/servicios/{servicio}', [ServicioController::class, 'destroy'])->name('servicios.destroy');

        // JSON categorías
        Route::get('/categorias',  [CategoriaController::class, 'index'])->name('categorias.index');
        Route::post('/categorias', [CategoriaController::class, 'store'])->name('categorias.store');
        Route::put('/categorias/{categoria}', [CategoriaController::class, 'update'])->name('categorias.update');
        Route::delete('/categorias/{categoria}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');


        //EMPLEADOS
         // Vista
        Route::get('/empleados', fn () => view('menu.empleados'))->name('empleados');

        // JSON
        Route::get('/empleados/lista', [EmpleadoController::class, 'index'])->name('empleados.index');
        Route::post('/empleados',      [EmpleadoController::class, 'store'])->name('empleados.store');
        Route::put('/empleados/{empleado}', [EmpleadoController::class, 'update'])->name('empleados.update');
        Route::delete('/empleados/{empleado}', [EmpleadoController::class, 'destroy'])->name('empleados.destroy');
        Route::put('/empleados/{empleado}/activar', [EmpleadoController::class, 'restore'])
    ->name('empleados.restore');
    });
