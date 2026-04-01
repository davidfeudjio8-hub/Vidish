<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Order; 
use App\Models\Dish;  
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VendorController extends Controller
{
    public function index()
    {
        $restaurant = Auth::user()->restaurant;

        if (!$restaurant) {
            return redirect()->route('restaurants.create');
        }

        $orders = Order::where('restaurant_id', $restaurant->id)
            ->whereIn('status', ['pending', 'preparing'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalViews = Dish::where('restaurant_id', $restaurant->id)->sum('views_count');

        $dishes = Dish::where('restaurant_id', $restaurant->id)
            ->latest()
            ->take(5)
            ->get();

        $chartLabels = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
        $chartData = [12, 45, 30, 70, 100, 80, 45];

        return view('vendor.dashboard', compact(
            'restaurant', 
            'orders', 
            'totalViews', 
            'dishes', 
            'chartLabels',
            'chartData'
        ));
    }

    public function managePlats()
    {
        $plats = Auth::user()->dishes()
            ->withCount('orders')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('vendor.plats.index', compact('plats'));
    }

    public function manageClips()
    {
        $clips = Auth::user()->clips()
            ->withCount(['views', 'likes'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('vendor.clips.index', compact('clips'));
    }

    public function settings()
    {
        $restaurant = Auth::user()->restaurant;
        if (!$restaurant) return redirect()->route('restaurants.create');
        return view('vendor.settings', compact('restaurant'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate(['is_open' => 'required|boolean']);
        $restaurant = Auth::user()->restaurant;

        if ($restaurant) {
            $restaurant->update(['is_open' => $request->is_open]);
            return response()->json([
                'success' => true,
                'message' => 'Statut mis à jour !',
                'is_open' => $restaurant->is_open
            ]);
        }
        return response()->json(['success' => false], 404);
    }

    public function updateSettings(Request $request)
    {
        $restaurant = Auth::user()->restaurant;
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'description' => 'nullable|string',
            'logo_path' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $validated['has_delivery'] = $request->has('has_delivery');

        if ($request->hasFile('logo_path')) {
            if ($restaurant->image_path) {
                Storage::disk('public')->delete($restaurant->image_path);
            }
            $validated['image_path'] = $request->file('logo_path')->store('logos', 'public');
        }

        unset($validated['logo_path']);
        $restaurant->update($validated);

        return redirect()->back()->with('success', 'Kitchen updated successfully!');
    }
}