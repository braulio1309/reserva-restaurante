<?php

use Illuminate\Support\Facades\Route;

// Public booking form
Route::get('/', function () {
    return view('components.reservas.public-form');
})->name('home');

Route::get('/reservar', function () {
    return view('components.reservas.public-form');
})->name('reservas.public');

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('pages.admin.dashboard');
    })->name('dashboard');
});

// Clientes routes
Route::get('/clientes', function () {
    return view('pages.clientes.index');
})->name('clientes.index');

// Disponibilidad routes
Route::get('/disponibilidad', function () {
    return view('pages.disponibilidad.index');
})->name('disponibilidad.index');
