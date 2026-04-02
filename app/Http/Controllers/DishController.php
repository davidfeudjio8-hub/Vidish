<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DishController extends Controller
{
    /**
     * Traite l'ajout d'un plat via l'overlay du dashboard.
     */
    public function store(Request $request)
    {
        // 1. Validation stricte des données entrantes
        $request->validate([
            'name' => 'required|string|max:255|min:4',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5000',
            'video' => 'nullable|mimes:mp4,mov,ogg|max:20000',
        ]);

        // 2. On récupère le restaurant de l'utilisateur (via sa relation définie dans User)
        $restaurant = Auth::user()->restaurant;

        if (!$restaurant) {
            return redirect()->back()->with('error', 'Aucun restaurant trouvé pour votre compte.');
        }

        // 3. Stockage de l'image (pour la colonne image_path)
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('dishes/images', 'public');
        }

        // 4. Création du Plat en DB
        $dish = Dish::create([
            'restaurant_id' => Auth::user()->restaurant->id,
            'user_id'       => Auth::id(),
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'image_path' => $imagePath, 
        ]);

        // 5. Gestion facultative du Clip Vidéo (L'essence de Vidish)
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('dishes/videos', 'public');

            Video::create([
                'dish_id' => $dish->id,
                'video_path' => $videoPath,
            ]);
        }

        return redirect()->back()->with('status', 'Le plat a été ajouté à votre carte !');
    }
}