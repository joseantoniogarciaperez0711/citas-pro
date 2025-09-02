<?php

use Illuminate\Support\Facades\Route;

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


use App\Http\Controllers\CitasController;

Route::middleware(['auth', 'verified'])->prefix('app')->name('app.')->group(function () {
    Route::get('/',            [CitasController::class, 'dashboard'])->name('dashboard');
    Route::get('/citas',       [CitasController::class, 'citas'])->name('citas');
    Route::get('/clientes',    [CitasController::class, 'clients'])->name('clients');
    Route::get('/ajustes',     [CitasController::class, 'settings'])->name('settings');


    Route::get('/servicios', function () {
        return view('menu/servicios');
    })->name('servicios');
});

// routes/web.php
use App\Http\Controllers\ProfileBusinessLogoController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/user/business-logo',   [ProfileBusinessLogoController::class, 'update'])->name('profile.business-logo.update');
    Route::delete('/user/business-logo', [ProfileBusinessLogoController::class, 'destroy'])->name('profile.business-logo.destroy');
});


use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceCategoryController;

Route::middleware(['auth'])->group(function () {
    Route::get('/servicios', [ServiceController::class, 'index'])->name('services.index');

    Route::post('/servicios', [ServiceController::class, 'store'])->name('services.store');
    Route::put('/servicios/{service}', [ServiceController::class, 'update'])->name('services.update');
    Route::delete('/servicios/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');

    Route::post('/categorias', [ServiceCategoryController::class, 'store'])->name('categories.store');
    Route::put('/categorias/{category}', [ServiceCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categorias/{category}', [ServiceCategoryController::class, 'destroy'])->name('categories.destroy');
});