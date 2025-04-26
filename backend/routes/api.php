<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\CocktailController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [LoginController::class, 'register']);

// Rutas protegidas que requieren autenticación
Route::middleware('auth:api')->group(function () {
    Route::post('/cocktails/save', [CocktailController::class, 'saveCocktail']);
    Route::get('/cocktails/saved', [CocktailController::class, 'getUserCocktails']);
    Route::delete('/cocktails/delete/{id}', [CocktailController::class, 'deleteCocktail']);
});
