<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestaurantController extends Controller
{
    public function create()
    {
        return view('restaurants.create');
    }

    public function store(Request $request)
    {
        // 1. Validation stricte des données (incluant les coordonnées et la livraison)
        $validated = $request->validate([
    'name' => 'required|string|max:255|min:4',
    'description' => 'nullable|string',
    'address' => 'nullable|string', // Change 'required' en 'nullable'
    'latitude' => 'nullable|numeric',
    'longitude' => 'nullable|numeric',
    'has_delivery' => 'nullable|boolean',
]);

        // 2. Création du restaurant avec les nouvelles colonnes
        Restaurant::create([
            'user_id'      => Auth::id(),
            'name'         => $validated['name'],
            'address'      => $validated['address']?? null,
            'description'  => $validated['description'],
            'latitude'     => $validated['latitude'] ?? null,
            'longitude'    => $validated['longitude'] ?? null,
            // Si la checkbox n'est pas cochée, $request->has_delivery sera absent, on met false par défaut
            'has_delivery' => $request->boolean('has_delivery'), 
        ]);

        // 3. Redirection vers le dashboard du vendeur avec un message de succès
        return redirect()->route('vendor.dashboard')
            ->with('success', 'Bienvenue dans l\'écosystème Vidish ! Ton restaurant est prêt.');
    }
}