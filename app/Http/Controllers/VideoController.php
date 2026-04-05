<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function index()
    {
        // On récupère tout, avec les relations pour éviter le N+1
        // Variable changed to $clips to match your feed.blade.php
        $clips = Video::with(['dish.restaurant', 'restaurant', 'tags'])->latest()->get();

        // Target the 'client' folder where your feed.blade.php is located
        return view('client.feed', compact('clips'));
    }

    public function vendorIndex()
    {
        $user = Auth::user();
        
        if (!$user->restaurant) {
            return redirect()->route('vendor.dashboard')->with('error', 'Aucun restaurant associé.');
        }

        $clips = Video::where('restaurant_id', $user->restaurant->id)
            ->with(['tags', 'dish'])
            ->latest()
            ->get();

        $allTags = Tag::pluck('name');

        return view('vendor.clips', compact('clips', 'allTags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'video' => 'required|mimes:mp4,mov,ogg,qt|max:40000',
            'description' => 'required|string|max:255',
            'dish_id' => 'nullable|exists:dishes,id',
            'tags' => 'nullable|string'
        ]);

        $path = $request->file('video')->store('clips', 'public');

        $clip = Video::create([
            'title' => $request->title,
            'dish_id' => $request->dish_id,
            'video_path' => $path,
            'description' => $request->description,
            'user_id' => auth()->id(),
            'restaurant_id' => auth()->user()->restaurant->id,
        ]);

        $this->syncTags($clip, $request->tags);

        return redirect()->back()->with('success', 'Clip publié !');
    }

    public function update(Request $request, Video $clip)
    {
        if ($clip->restaurant_id !== Auth::user()->restaurant->id) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:100',
            'video' => 'nullable|mimes:mp4,mov,ogg,qt|max:40000',
            'description' => 'required|string|max:255',
            'dish_id' => 'nullable|exists:dishes,id',
            'tags' => 'nullable|string'
        ]);

        if ($request->hasFile('video')) {
            Storage::disk('public')->delete($clip->video_path);
            $clip->video_path = $request->file('video')->store('clips', 'public');
        }

        $clip->update([
            'title' => $request->title,
            'dish_id' => $request->dish_id,
            'description' => $request->description,
        ]);

        $this->syncTags($clip, $request->tags);

        return redirect()->back()->with('success', 'Clip mis à jour !');
    }

    public function destroy(Video $clip)
    {
        if ($clip->restaurant_id !== auth()->user()->restaurant->id) {
            abort(403, 'Action non autorisée.');
        }

        if (Storage::disk('public')->exists($clip->video_path)) {
            Storage::disk('public')->delete($clip->video_path);
        }

        $clip->delete();

        return redirect()->back()->with('success', 'Le clip a été supprimé avec succès.');
    }

    private function syncTags(Video $video, ?string $tagsString)
    {
        if (!$tagsString) {
            $video->tags()->detach();
            return;
        }

        $tagNames = array_filter(array_map('trim', explode(',', $tagsString)));
        $tagIds = [];

        foreach ($tagNames as $name) {
            $cleanName = ltrim($name, '#');
            if (!empty($cleanName)) {
                $tag = Tag::firstOrCreate(['name' => $cleanName]);
                $tagIds[] = $tag->id;
                
                if (!$video->tags()->where('tags.id', $tag->id)->exists()) {
                    $tag->increment('use_count');
                }
            }
        }
        $video->tags()->sync($tagIds);
    }
}