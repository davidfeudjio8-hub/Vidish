<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        // Récupération des coordonnées (Yaoundé par défaut ou via JS)
        $lat = $request->query('lat');
        $lng = $request->query('lng');

        $query = Video::with(['dish.restaurant']);

        if ($lat && $lng) {
            // Formule de Haversine pour trier par proximité
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
            // Sinon, on affiche les plus récents
            $query->latest();
        }

        $videos = $query->get();

        return view('video.feed', compact('videos'));
    }
}