<?php

use App\Models\Video;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Page d'accueil : Flux vidéo public
Route::get('/', function () {
    $videos = Video::with('dish.restaurant')->latest()->get();
    return view('welcome', compact('videos'));
})->name('home');

// ROUTES AUTHENTIFIÉES (Clients & Futurs Restaurateurs)
Route::middleware('auth')->group(function () {
    
    // Dashboard client par défaut
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Gestion du Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Flux Vidéo principal
    Route::get('/feed', [VideoController::class, 'index'])->name('video.feed');

    // CRÉATION DE RESTAURANT (C'est ici que se règle ton erreur)
    // Ces routes permettent à un utilisateur de s'enregistrer comme vendeur
    Route::get('/restaurants/create', [RestaurantController::class, 'create'])->name('restaurants.create');
    Route::post('/restaurants', [RestaurantController::class, 'store'])->name('restaurants.store');
});

// GROUPE RÉSERVÉ AUX RESTAURATEURS (VENDORS)
Route::middleware(['auth', 'verified', 'role:restaurateur'])
    ->prefix('vendor')
    ->name('vendor.') 
    ->group(function () {
        
        // Dashboard principal du vendeur
        Route::get('/dashboard', [VendorController::class, 'index'])->name('dashboard');
        
        // Paramètres et Statut (Toggle AJAX)
        Route::get('/settings', [VendorController::class, 'settings'])->name('settings');
        Route::post('/status/update', [VendorController::class, 'updateStatus'])->name('status.update');

        // Gestion complète des Plats (CRUD)
        Route::resource('dishes', DishController::class);
});

require __DIR__.'/auth.php';