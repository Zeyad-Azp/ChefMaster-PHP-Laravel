<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\SpoonacularController;

/*
|--------------------------------------------------------------------------
| Web Routes — ChefMaster
|--------------------------------------------------------------------------
*/

// ── Home ──────────────────────────────────────────────────────────────────
Route::get('/', function () {
    return view('index');
})->name('home');

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

