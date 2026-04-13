<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::inertia('/', 'Welcome')->name('welcome');
Route::inertia('lowongan', 'Lowongan')->name('lowongan'); 
Route::inertia('carimitra', 'Carimitra')->name('carimitra'); 

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('/dashboard', 'Dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';                                                                