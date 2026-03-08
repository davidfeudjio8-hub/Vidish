<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function create()
    {
        return view('restaurants.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|min:4',
            'description' => 'nullable|string',
            'address' => 'required|string',
        ]);

        // This creates the restaurant and automatically sets the owner
    \App\Models\Restaurant::create([
        'user_id' => auth()->id(),
        'name' => $request->name,
        'address' => $request->address,
        'description' => $request->description,
    ]);

        return redirect()->route('dashboard')->with('success', 'Restaurant created successfully!');
    }
}