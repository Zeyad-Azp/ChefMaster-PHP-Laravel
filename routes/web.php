<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecipeController;

/*
|--------------------------------------------------------------------------
| Web Routes — ChefMaster
|--------------------------------------------------------------------------
| T3: Routes & Controllers (Member 3)
|
| Resource routes map to RecipeController methods:
|   GET    /recipes               → index()
|   GET    /recipes/create        → create()
|   POST   /recipes               → store()
|   GET    /recipes/{recipe}      → show()
|   GET    /recipes/{recipe}/edit → edit()
|   PUT    /recipes/{recipe}      → update()
|   DELETE /recipes/{recipe}      → destroy()
|
| Extra AJAX routes (for M7 — client-side JS):
|   PATCH  /recipes/{recipe}/favorite → toggleFavorite()
|   GET    /recipes/api/list          → apiList()
|   GET    /recipes/api/favorites     → apiFavorites()
*/

// Home page
Route::get('/', function () {
    return view('index');
})->name('home');

// AJAX JSON endpoints — defined BEFORE resource route so "api"
// is not captured as a {recipe} parameter
Route::get('/recipes/api/list',      [RecipeController::class, 'apiList'])->name('recipes.api.list');
Route::get('/recipes/api/favorites', [RecipeController::class, 'apiFavorites'])->name('recipes.api.favorites');

// Favorite toggle (AJAX — PATCH)
Route::patch('/recipes/{recipe}/favorite', [RecipeController::class, 'toggleFavorite'])->name('recipes.favorite');

// Full CRUD resource routes
Route::resource('recipes', RecipeController::class);


Route::post('/recipes/upload-image', [RecipeController::class, 'uploadImage']);