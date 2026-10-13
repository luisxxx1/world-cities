<?php

use App\Http\Controllers\CountryController;
use Illuminate\Support\Facades\Route;

// pantalla principal
Route::get('/', [CountryController::class, 'index'])
    ->name('countries.index');

// ciudades de un país, solo acepta códigos de 3 letras
Route::get('/countries/{code}/cities', [CountryController::class, 'cities'])
    ->where('code', '[A-Za-z]{3}')
    ->name('countries.cities');