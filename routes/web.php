<?php

use Livewire\Volt\Volt;
use App\Http\Controllers\InteractionController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

// --- ACCUEIL (Public) ---
Route::get('/', function () {
    return view('welcome');
})->name('home');

// --- ROUTES AUTHENTIFIÉES ---
Route::middleware('auth')->group(function () {
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profil Utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Feed & Interactions Sociales
    Volt::route('/feed', 'video-feed')->name('video.feed');
    Route::post('/video/{video}/like', [VideoController::class, 'toggleLike'])->name('video.like');
    Route::post('/video/{video}/comment', [VideoController::class, 'storeComment'])->name('video.comment');
    Route::get('/video/{video}/comments', [VideoController::class, 'getComments']);

    // Explore & Recherche (API pour Alpine.js)
    Volt::route('/explore', 'video-explore')->name('video.explore');
    Route::get('/api/tags/search', [VideoController::class, 'searchTags']);

    // Commandes (Réservé aux clients)
    Route::get('/mes-commandes', [OrderController::class, 'index'])
        ->name('client.orders')
        ->middleware('role:client');

    // Inscription Restaurant
    Route::get('/restaurants/create', [RestaurantController::class, 'create'])->name('restaurants.create');
    Route::post('/restaurants', [RestaurantController::class, 'store'])->name('restaurants.store');
});

// --- GROUPE VENDOR (Restaurateurs) ---
Route::middleware(['auth', 'verified', 'role:restaurateur'])
    ->prefix('vendor') 
    ->name('vendor.')   
    ->group(function () {
        
        // Dashboard & Plats
        Route::get('/dashboard', [VendorController::class, 'index'])->name('dashboard');
        Route::get('/plats', [VendorController::class, 'managePlats'])->name('plats');
        Route::resource('dishes', DishController::class)->except(['edit', 'show', 'create']);

        // Gestion des Clips Vidéos
        Route::get('/clips', [VideoController::class, 'vendorIndex'])->name('clips');
        Route::resource('clips', VideoController::class)->except(['index']);

        // Paramètres Boutique
        Route::get('/settings', [VendorController::class, 'settings'])->name('settings');
        Route::put('/settings/update', [VendorController::class, 'updateSettings'])->name('settings.update');
        Route::post('/status-update', [VendorController::class, 'updateStatus'])->name('status.update');
    });

require __DIR__.'/auth.php';