<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DishController extends Controller
{
    /**
     * Traite l'ajout d'un plat via l'overlay.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|min:4',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5000',
            'video' => 'nullable|mimes:mp4,mov,ogg|max:50000',
        ]);

        $restaurant = Auth::user()->restaurant;

        $imagePath = $request->file('image')->store('dishes/images', 'public');

        $dish = Dish::create([
            'restaurant_id' => $restaurant->id,
            'user_id'       => Auth::id(),
            'name'          => $request->name,
            'price'         => $request->price,
            'description'   => $request->description,
            'image_path'    => $imagePath,
        ]);

        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('clips', 'public');
            Video::create([
                'dish_id'    => $dish->id,
                'video_path' => $videoPath,
                'user_id'    => Auth::id(),
                'description' => $request->description,
            ]);
        }

        return redirect()->back()->with('status', 'Plat ajouté !');
    }

    /**
     * Met à jour un plat via l'overlay (Route PUT).
     */
    public function update(Request $request, $id)
    {
        $dish = Dish::where('restaurant_id', Auth::user()->restaurant->id)->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:5000',
        ]);

        $dish->name = $request->name;
        $dish->price = $request->price;
        $dish->description = $request->description;

        if ($request->hasFile('image')) {
            if ($dish->image_path) Storage::disk('public')->delete($dish->image_path);
            $dish->image_path = $request->file('image')->store('dishes/images', 'public');
        }

        $dish->save();

        return redirect()->back()->with('status', 'Plat mis à jour !');
    }

    public function destroy($id)
{
    // On sécurise : seul le propriétaire du restaurant peut supprimer son plat
    $dish = Dish::where('restaurant_id', Auth::user()->restaurant->id)->findOrFail($id);
    
    // Supprimer l'image du stockage si elle existe
    if ($dish->image_path) {
        Storage::disk('public')->delete($dish->image_path);
    }

    $dish->delete();

    return redirect()->back()->with('status', 'Le plat a été retiré de la carte.');
}
}