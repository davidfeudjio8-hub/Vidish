<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DishController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'restaurant_id' => 'required|exists:restaurants,id',
        'name' => 'required|string|max:255|min:4',
        'price' => 'required|numeric',
        'video' => 'required|mimes:mp4,mov,ogg|max:20000', // Max 20MB for now
    ]);

    // 1. Create the Dish
    $dish = \App\Models\Dish::create([
        'restaurant_id' => $request->restaurant_id,
        'name' => $request->name,
        'price' => $request->price,
        'description' => $request->description,
    ]);

    // 2. Handle Video Upload
    if ($request->hasFile('video')) {
        $path = $request->file('video')->store('dishes/videos', 'public');

        \App\Models\Video::create([
            'dish_id' => $dish->id,
            'video_path' => $path,
        ]);
    }

    return redirect()->back()->with('status', 'Dish and Video uploaded successfully!');
}
}
