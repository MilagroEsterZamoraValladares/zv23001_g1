<?php

use App\Http\Controllers\Api\AutorController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\LibroController;
use App\Http\Controllers\Api\PrestamoController;
use Illuminate\Support\Facades\Route;

Route::apiResource('autores', AutorController::class);
Route::apiResource('categorias', CategoriaController::class);
Route::apiResource('libros', LibroController::class);
Route::apiResource('prestamos', PrestamoController::class);
