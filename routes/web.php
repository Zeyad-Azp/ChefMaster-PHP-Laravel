<?php

use Illuminate\Support\Facades\Route;
<<<<<<< HEAD
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\ProfileController;
=======
use App\Http\Controllers\RecipeController;
>>>>>>> e1b21b8101c145ef6af786483709267652d41b6a
use App\Http\Controllers\Api\SpoonacularController;

/*
|--------------------------------------------------------------------------
| Web Routes — ChefMaster
|--------------------------------------------------------------------------
<<<<<<< HEAD
*/

// ── Home ──────────────────────────────────────────────────────────────────
=======
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
>>>>>>> e1b21b8101c145ef6af786483709267652d41b6a
Route::get('/', function () {
    return view('index');
})->name('home');

<<<<<<< HEAD
// ── Authentication (guest only) ───────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// ── Spoonacular API (public — key stays server-side) ─────────────────────
Route::get('/api/spoonacular/search',  [SpoonacularController::class, 'search'])->name('spoonacular.search');
Route::get('/api/spoonacular/detail',  [SpoonacularController::class, 'detail']);
Route::post('/api/spoonacular/save',   [SpoonacularController::class, 'save'])->middleware('auth');

// ── Profile & Recipes (auth required) ─────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile',         [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile',         [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.avatar');

    // AJAX JSON endpoints — must be BEFORE resource route
    Route::get('/recipes/api/list',      [RecipeController::class, 'apiList'])->name('recipes.api.list');
    Route::get('/recipes/api/favorites', [RecipeController::class, 'apiFavorites'])->name('recipes.api.favorites');
    Route::patch('/recipes/{recipe}/favorite', [RecipeController::class, 'toggleFavorite'])->name('recipes.favorite');

    // Image upload (before resource route to avoid {recipe} capture)
    Route::post('/recipes/upload-image', [RecipeController::class, 'uploadImage']);

    // Full CRUD resource routes
    Route::resource('recipes', RecipeController::class);
});

=======

Route::get('/api/spoonacular/search', [SpoonacularController::class, 'search'])
    ->name('spoonacular.search');


// AJAX JSON endpoints — defined BEFORE resource route so "api"
// is not captured as a {recipe} parameter
Route::get('/recipes/api/list',      [RecipeController::class, 'apiList'])->name('recipes.api.list');
Route::get('/recipes/api/favorites', [RecipeController::class, 'apiFavorites'])->name('recipes.api.favorites');
Route::get('/api/spoonacular/detail', [SpoonacularController::class, 'detail']);
Route::post('/api/spoonacular/save',  [SpoonacularController::class, 'save']);
// Favorite toggle (AJAX — PATCH)
Route::patch('/recipes/{recipe}/favorite', [RecipeController::class, 'toggleFavorite'])->name('recipes.favorite');

// Full CRUD resource routes
Route::resource('recipes', RecipeController::class);


Route::post('/recipes/upload-image', [RecipeController::class, 'uploadImage']);
>>>>>>> e1b21b8101c145ef6af786483709267652d41b6a
