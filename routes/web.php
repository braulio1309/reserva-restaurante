<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\AuthController;

// Public booking form - using Livewire Volt component
Volt::route('/', 'reservas.public-form')->name('home');
Volt::route('/reservar', 'reservas.public-form')->name('reservas.public');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Admin routes
Route::middleware('auth')->group(function () {
    // Admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Volt::route('/dashboard', 'admin.dashboard')->name('dashboard');
    });

    // Clientes routes
    Volt::route('/clientes', 'clientes.index')->name('clientes.index');

    // Disponibilidad routes
    Volt::route('/disponibilidad', 'disponibilidad.index')->name('disponibilidad.index');
});
