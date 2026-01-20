<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
// Public booking form
Route::view('/', 'components.reservas.public-form')->name('home');
Route::view('/reservar', 'components.reservas.public-form')->name('reservas.public');

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::view('/dashboard', 'pages.admin.dashboard')->name('dashboard');
});

// Clientes routes
Route::view('/clientes', 'pages.clientes.index')->name('clientes.index');

// Disponibilidad routes
Route::view('/disponibilidad', 'pages.disponibilidad.index')->name('disponibilidad.index');
