<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        $lat = $request->query('lat');
        $lng = $request->query('lng');

        $query = Video::with(['dish.restaurant']);

        if ($lat && $lng) {
            $query->select('videos.*')
                ->join('dishes', 'videos.dish_id', '=', 'dishes.id')
                ->join('restaurants', 'dishes.restaurant_id', '=', 'restaurants.id')
                ->selectRaw(
                    "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) 
                    * cos(radians(longitude) - radians(?)) + sin(radians(?)) 
                    * sin(radians(latitude)))) AS distance", [$lat, $lng, $lat]
                )
                ->orderBy('distance', 'asc');
        } else {
            $query->latest();
        }

        $videos = $query->get();
        return view('video.feed', compact('videos'));
    }

    /**
     * Gère l'upload des clips depuis le Dashboard Vendor.
     */
    public function store(Request $request)
    {
        $request->validate([
            'video' => 'required|mimes:mp4,mov,ogg|max:40000', // Limite à 40MB
            'caption' => 'nullable|string|max:255',
            'dish_id' => 'nullable|exists:dishes,id',
        ]);

        if ($request->hasFile('video')) {
            $path = $request->file('video')->store('clips', 'public');

            Video::create([
                'video_path' => $path,
                'description' => $request->caption,
                'dish_id' => $request->dish_id,
            ]);
        }

        return redirect()->back()->with('status', 'Le clip a été publié avec succès !');
    }
}