<?php

use App\Http\Controllers\VendorController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

// --- ACCUEIL (Public) ---
// This route now returns the Landing Page view exclusively
Route::get('/', function () {
    return view('welcome');
})->name('home');

// --- ROUTES AUTHENTIFIÉES ---
Route::middleware('auth')->group(function () {
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Le Feed Vidish - Points to the VideoController which now returns 'client.feed'
    Route::get('/feed', [VideoController::class, 'index'])->name('video.feed');
    
    Route::get('/api/tags/search', [VideoController::class, 'searchTags']);

    // Inscription Restaurant
    Route::get('/restaurants/create', [RestaurantController::class, 'create'])->name('restaurants.create');
    Route::post('/restaurants', [RestaurantController::class, 'store'])->name('restaurants.store');
});

// --- GROUPE VENDOR ---
Route::middleware(['auth', 'verified', 'role:restaurateur'])
    ->prefix('vendor') 
    ->name('vendor.')   
    ->group(function () {
        Route::get('/dashboard', [VendorController::class, 'index'])->name('dashboard');
        Route::get('/plats', [VendorController::class, 'managePlats'])->name('plats');
        Route::resource('dishes', DishController::class)->except(['edit', 'show', 'create']);

        // GESTION DES CLIPS
        Route::get('/clips', [VideoController::class, 'vendorIndex'])->name('clips');
        Route::resource('clips', VideoController::class)->except(['index']);

        Route::get('/settings', [VendorController::class, 'settings'])->name('settings');
        Route::put('/settings/update', [VendorController::class, 'updateSettings'])->name('settings.update');
        Route::post('/status-update', [VendorController::class, 'updateStatus'])->name('status.update');
    });

require __DIR__.'/auth.php';