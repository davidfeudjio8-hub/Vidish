<?php

use App\Models\Video;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

// --- ACCUEIL ---
Route::get('/', function () {
    $videos = Video::with('dish.restaurant')->latest()->get();
    return view('welcome', compact('videos'));
})->name('home');

// --- ROUTES AUTHENTIFIÉES ---
Route::middleware('auth')->group(function () {
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/feed', [VideoController::class, 'index'])->name('video.feed');

    // Inscription Restaurant
    Route::get('/restaurants/create', [RestaurantController::class, 'create'])->name('restaurants.create');
    Route::post('/restaurants', [RestaurantController::class, 'store'])->name('restaurants.store');
});

// --- GROUPE VENDOR (Restaurateurs) ---
Route::middleware(['auth', 'verified', 'role:restaurateur'])
    ->prefix('vendor') 
    ->name('vendor.')   
    ->group(function () {
        
        // Dashboard : URL = /vendor/dashboard
        Route::get('/dashboard', [VendorController::class, 'index'])->name('dashboard');
        
        // Settings : URL = /vendor/settings
        Route::get('/settings', [VendorController::class, 'settings'])->name('settings');
        Route::put('/settings/update', [VendorController::class, 'updateSettings'])->name('settings.update');

        // Statut : URL = /vendor/status-update (Corrigé ici)
        Route::post('/status-update', [VendorController::class, 'updateStatus'])->name('status.update');

        // CRUD Plats : URL = /vendor/dishes
        Route::resource('dishes', DishController::class);
});

require __DIR__.'/auth.php';