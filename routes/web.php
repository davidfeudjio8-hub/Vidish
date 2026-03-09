<?php
use App\Models\Video;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $videos = Video::with('dish.restaurant')->latest()->get();
    return view('welcome', compact('videos'));
})->middleware('guest')->name('home');

Route::get('/dashboard', function () {
    if (!auth()->check()) {
        return redirect('/?auth_trigger=login');
    }
    return view('dashboard');
})->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/restaurants/create', [RestaurantController::class, 'create'])->name('restaurants.create');
    Route::post('/restaurants', [RestaurantController::class, 'store'])->name('restaurants.store');
    Route::post('/dishes', [DishController::class, 'store'])->name('dishes.store');
});

require __DIR__.'/auth.php';
