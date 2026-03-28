<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Order; 
use App\Models\Dish;  
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    public function index()
    {
        // 1. Récupérer le restaurant de l'utilisateur connecté
        $restaurant = Auth::user()->restaurant;

        // 2. Sécurité : si pas de restaurant, redirection vers la création
        if (!$restaurant) {
            return redirect()->route('restaurants.create');
        }

        // 3. Récupérer les données nécessaires
        // On regroupe tout proprement pour ne faire qu'UN SEUL return à la fin
        $orders = Order::where('restaurant_id', $restaurant->id)
            ->whereIn('status', ['pending', 'preparing'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalViews = Dish::where('restaurant_id', $restaurant->id)->sum('views_count');

        $dishes = Dish::where('restaurant_id', $restaurant->id)
            ->latest()
            ->take(5)
            ->get();

        // Données fictives pour ton graphique (à dynamiser plus tard)
        $chartData = [12, 45, 30, 70, 100, 80, 45];

        // 4. Envoyer TOUTES les variables à la vue en une seule fois
        return view('vendor.dashboard', compact(
            'restaurant', 
            'orders', 
            'totalViews', 
            'dishes', 
            'chartData'
        ));
    }

    public function settings()
    {
        $restaurant = Auth::user()->restaurant;
        return view('vendor.settings', compact('restaurant'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'is_open' => 'required|boolean'
        ]);

        $restaurant = Auth::user()->restaurant;
        $restaurant->update(['is_open' => $request->is_open]);

        return response()->json(['success' => 'Statut mis à jour avec succès !']);
    }
}