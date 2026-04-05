<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Video;
use Illuminate\Http\Request;

class FeedController extends Controller {
    public function index() {
        $clips = Video::with(['tags', 'user.restaurant', 'dish'])->latest()->get();
        // On récupère aussi les tags populaires pour le mode Explore
        $trendingTags = Tag::orderBy('use_count', 'desc')->take(10)->get();
        return view('client.feed', compact('clips', 'trendingTags'));
    }

    // API pour l'autocomplétion (Appelée par Alpine.js)
    public function searchTags(Request $request) {
        $query = $request->get('q');
        return Tag::where('name', 'LIKE', "%{$query}%")
                  ->limit(5)
                  ->get(['name']);
    }
}