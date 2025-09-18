<?php

use App\Http\Controllers\CategoriaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\CitasController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ClienteArchivoController;
use App\Http\Controllers\CitaController;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/tienda', function () {
    return view('clientes/tienda');
});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // 👉 Vista HTML
    Route::get('/dashboard', [DashboardController::class, 'view'])
        ->name('dashboard');

    // 👉 JSON para la vista (lo consume fetch en el front)
    Route::get('/app/dashboard/data', [DashboardController::class, 'data'])
        ->name('app.dashboard.data');
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
        Route::get('/empleados', fn() => view('menu.empleados'))->name('empleados');

        // JSON
        Route::get('/empleados/lista', [EmpleadoController::class, 'index'])->name('empleados.index');
        Route::post('/empleados',      [EmpleadoController::class, 'store'])->name('empleados.store');
        Route::put('/empleados/{empleado}', [EmpleadoController::class, 'update'])->name('empleados.update');
        Route::delete('/empleados/{empleado}', [EmpleadoController::class, 'destroy'])->name('empleados.destroy');
        Route::put('/empleados/{empleado}/activar', [EmpleadoController::class, 'restore'])
            ->name('empleados.restore');
        Route::get('/empleados/{empleado}/historial', [CitaController::class, 'porEmpleado'])
            ->name('empleados.historial');



        //CLIENTES
        // Vista
        Route::get('/clientes', fn() => view('menu.clientes'))->name('clientes');

        // JSON clientes
        Route::get('/clientes/lista', [ClienteController::class, 'index'])->name('clientes.index');
        Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
        Route::put('/clientes/{cliente}', [ClienteController::class, 'update'])->name('clientes.update');
        Route::delete('/clientes/{cliente}', [ClienteController::class, 'destroy'])->name('clientes.destroy');
        Route::put('/clientes/{cliente}/activar', [ClienteController::class, 'restore'])->name('clientes.restore');

        //Historial por cliente
        Route::get('clientes/{cliente}/citas', [CitaController::class, 'porCliente'])
            ->name('clientes.citas');

        // Archivos de cliente
        Route::get('/clientes/{cliente}/archivos', [ClienteArchivoController::class, 'index'])->name('clientes.files.index');
        Route::post('/clientes/{cliente}/archivos', [ClienteArchivoController::class, 'store'])->name('clientes.files.store');
        Route::delete('/clientes/archivos/{archivo}', [ClienteArchivoController::class, 'destroy'])->name('clientes.files.destroy');
        Route::get('/clientes/archivos/{archivo}/descargar', [ClienteArchivoController::class, 'download'])->name('clientes.files.download');


        // ===== Citas =====
        // ✅ ÚNICA ruta de vista (manda $servicios/$categorias/$empleados/$clientes)
        Route::get('/citas', [CitaController::class, 'index'])->name('citas');


        // JSON
        Route::get('/citas/lista', [CitaController::class, 'lista'])->name('citas.index');
        Route::post('/citas', [CitaController::class, 'store'])->name('citas.store');
        Route::put('/citas/{cita}', [CitaController::class, 'update'])->name('citas.update');
        Route::delete('/citas/{cita}', [CitaController::class, 'destroy'])->name('citas.destroy');
        Route::put('/citas/{cita}/estado', [CitaController::class, 'estado'])->name('citas.estado');
    });



