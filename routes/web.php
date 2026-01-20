<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\AuthController;

// Public booking form
Route::view('/', 'components.reservas.public-form')->name('home');
Route::view('/reservar', 'components.reservas.public-form')->name('reservas.public');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Admin routes
Route::middleware('auth')->group(function () {
    // Admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::view('/dashboard', 'pages.admin.dashboard')->name('dashboard');
    });

    // Clientes routes
    Route::view('/clientes', 'pages.clientes.index')->name('clientes.index');

    // Disponibilidad routes
    Route::view('/disponibilidad', 'pages.disponibilidad.index')->name('disponibilidad.index');
});
