<?php

use App\Models\Video;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

// --- ACCUEIL (Public) ---
Route::get('/', function () {
    $videos = Video::with('dish.restaurant')->latest()->get();
    return view('welcome', compact('videos'));
})->name('home');

// --- ROUTES AUTHENTIFIÉES (User standard) ---
Route::middleware('auth')->group(function () {
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Le Feed vertical pour les clients
    Route::get('/feed', [VideoController::class, 'index'])->name('video.feed');

    // Inscription Restaurant
    Route::get('/restaurants/create', [RestaurantController::class, 'create'])->name('restaurants.create');
    Route::post('/restaurants', [RestaurantController::class, 'store'])->name('restaurants.store');
});

// --- GROUPE VENDOR (Espace Restaurateur) ---
Route::middleware(['auth', 'verified', 'role:restaurateur'])
    ->prefix('vendor') 
    ->name('vendor.')   
    ->group(function () {
        
        // Dashboard Principal
        Route::get('/dashboard', [VendorController::class, 'index'])->name('dashboard');
        
        // Gestion des Plats
        Route::get('/plats', [VendorController::class, 'managePlats'])->name('plats');
        Route::resource('dishes', DishController::class); // CRUD complet pour les plats

        // --- GESTION DES CLIPS (Vidéos) ---
        // On utilise VideoController pour centraliser la logique des vidéos
        Route::get('/clips', [VideoController::class, 'vendorIndex'])->name('clips'); 
        Route::post('/clips/store', [VideoController::class, 'store'])->name('clips.store');
        Route::delete('/clips/{id}', [VideoController::class, 'destroy'])->name('clips.destroy');

        // Paramètres & Statut du restaurant
        Route::get('/settings', [VendorController::class, 'settings'])->name('settings');
        Route::put('/settings/update', [VendorController::class, 'updateSettings'])->name('settings.update');
        Route::post('/status-update', [VendorController::class, 'updateStatus'])->name('status.update');
    });

require __DIR__.'/auth.php';