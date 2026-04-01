<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Video;
use Illuminate\Http\Request;

class DishController extends Controller
{
    public function store(Request $request)
    {
        // Validation stricte
        $request->validate([
            'name' => 'required|string|max:255|min:4',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5000',
            'video' => 'nullable|mimes:mp4,mov,ogg|max:20000',
        ]);

        // On récupère le restaurant de l'utilisateur authentifié
        $restaurant = auth()->user()->restaurant;

        if (!$restaurant) {
            return redirect()->back()->with('error', 'Aucun restaurant associé à votre compte.');
        }

        // 1. Gérer l'image du plat
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('dishes/images', 'public');
        }

        // 2. Création du Plat
        $dish = Dish::create([
            'restaurant_id' => $restaurant->id,
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'image_path' => $imagePath, // Assure-toi que cette colonne existe dans dishes
        ]);

        // 3. Gérer la Vidéo associée (si présente lors de la création du plat)
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('dishes/videos', 'public');

            Video::create([
                'dish_id' => $dish->id,
                'video_path' => $videoPath,
            ]);
        }

        return redirect()->back()->with('status', 'Le plat a été ajouté avec succès !');
    }
}