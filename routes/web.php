<?php

use App\Models\Video;
use App\Http\Controllers\Vendor\DashboardController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Page d'accueil : Flux vidéo pour les visiteurs
Route::get('/', function () {
    $videos = Video::with('dish.restaurant')->latest()->get();
    return view('welcome', compact('videos'));
})->name('home');

// Routes accessibles à tous les utilisateurs connectés (Clients + Restaurateurs)
Route::middleware('auth')->group(function () {
    
    // Dashboard par défaut (souvent pour les clients)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Gestion du profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Inscription d'un nouveau restaurant
    Route::get('/restaurants/create', [RestaurantController::class, 'create'])->name('restaurants.create');
    Route::post('/restaurants', [RestaurantController::class, 'store'])->name('restaurants.store');
});

// Groupe de routes STRICTEMENT réservé aux Restaurateurs
// On utilise le middleware 'role:restaurateur' que nous avons créé
Route::middleware(['auth', 'verified', 'role:restaurateur'])->prefix('vendor')->group(function () {
    
    // Dashboard principal du restaurateur
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('vendor.dashboard');
    
    // Gestion des plats (Ajout, Modification)
    Route::post('/dishes', [DishController::class, 'store'])->name('dishes.store');
    Route::resource('dishes', DishController::class)->except(['store']);
});

require __DIR__.'/auth.php';