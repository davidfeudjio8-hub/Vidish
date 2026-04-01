<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Order; 
use App\Models\Dish;  
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    /**
     * Affiche le Dashboard principal du vendeur.
     */
    public function index()
    {
        // 1. Récupérer le restaurant de l'utilisateur connecté
        $restaurant = Auth::user()->restaurant;

        // 2. Sécurité : si l'utilisateur n'a pas encore de restaurant, on le redirige
        if (!$restaurant) {
            return redirect()->route('restaurants.create');
        }

        // 3. Récupérer les commandes en cours (en attente ou en préparation)
        $orders = Order::where('restaurant_id', $restaurant->id)
            ->whereIn('status', ['pending', 'preparing'])
            ->orderBy('created_at', 'desc')
            ->get();

        // 4. Statistiques : Total des vues sur tous les plats du restaurant
        // Assure-toi que la colonne 'views_count' existe dans ta table 'dishes'
        $totalViews = Dish::where('restaurant_id', $restaurant->id)->sum('views_count');

        // 5. Récupérer les 5 derniers plats ajoutés pour l'affichage rapide
        $dishes = Dish::where('restaurant_id', $restaurant->id)
            ->latest()
            ->take(5)
            ->get();

        // 6. Données pour le graphique (Ventes ou Vues)
        $chartData = [12, 45, 30, 70, 100, 80, 45];

        // 7. UN SEUL RETURN qui envoie TOUTES les variables nécessaires à la vue
        return view('vendor.dashboard', compact(
            'restaurant', 
            'orders', 
            'totalViews', 
            'dishes', 
            'chartData'
        ));
    }

    /**
     * Affiche la page des paramètres du restaurant.
     */
    public function settings()
    {
        $restaurant = Auth::user()->restaurant;
        
        if (!$restaurant) {
            return redirect()->route('restaurants.create');
        }

        return view('vendor.settings', compact('restaurant'));
    }

    /**
     * Met à jour le statut d'ouverture (Ouvert/Fermé) via AJAX.
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'is_open' => 'required|boolean'
        ]);

        $restaurant = Auth::user()->restaurant;

        if ($restaurant) {
            $restaurant->update(['is_open' => $request->is_open]);
            return response()->json([
                'status' => 'success',
                'message' => 'Statut de l\'établissement mis à jour !',
                'is_open' => $restaurant->is_open
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Restaurant non trouvé.'], 404);
    }

    public function updateSettings(Request $request)
{
    $restaurant = Auth::user()->restaurant; // Ou ta logique de récupération

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'address' => 'required|string|max:500',
        'description' => 'nullable|string',
        'has_delivery' => 'boolean',
    ]);

    // Petit hack pour le checkbox (car non envoyé si décoché)
    $validated['has_delivery'] = $request->has('has_delivery');

    $restaurant->update($validated);

    return redirect()->back()->with('success', 'Kitchen updated successfully!');
}
}