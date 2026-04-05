<?php

namespace App\Http\Controllers;

use App\Models\Clip;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClipController extends Controller
{
    public function index()
    {
        $vendor = Auth::user();
        // On récupère les clips du restaurant de l'utilisateur avec leurs tags et plat associé
        $clips = Clip::where('restaurant_id', $vendor->restaurant->id)
            ->with(['tags', 'dish'])
            ->latest()
            ->get();

        // On récupère TOUS les tags existants pour les suggestions AlpineJS
        $allTags = Tag::pluck('name')->toArray();

        return view('vendor.clips', compact('clips', 'allTags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'video' => 'required|mimes:mp4,mov,ogg,qt|max:20000',
            'description' => 'required|string|max:255',
            'dish_id' => 'nullable|exists:dishes,id',
            'tags' => 'nullable|string' // Reçu comme "Tag1,Tag2,Tag3"
        ]);

        $path = $request->file('video')->store('clips', 'public');

        $clip = Clip::create([
            'restaurant_id' => Auth::user()->restaurant->id,
            'dish_id' => $request->dish_id,
            'video_path' => $path,
            'description' => $request->description,
        ]);

        $this->syncTags($clip, $request->tags);

        return redirect()->back()->with('success', 'Clip publié avec succès !');
    }

    public function update(Request $request, Clip $clip)
    {
        $request->validate([
            'video' => 'nullable|mimes:mp4,mov,ogg,qt|max:20000',
            'description' => 'required|string|max:255',
            'dish_id' => 'nullable|exists:dishes,id',
            'tags' => 'nullable|string'
        ]);

        if ($request->hasFile('video')) {
            Storage::disk('public')->delete($clip->video_path);
            $clip->video_path = $request->file('video')->store('clips', 'public');
        }

        $clip->update([
            'dish_id' => $request->dish_id,
            'description' => $request->description,
        ]);

        $this->syncTags($clip, $request->tags);

        return redirect()->back()->with('success', 'Clip mis à jour !');
    }

    public function destroy(Clip $clip)
    {
        Storage::disk('public')->delete($clip->video_path);
        $clip->delete();
        return redirect()->back()->with('success', 'Clip supprimé.');
    }

    /**
     * Logique de synchronisation des tags
     */
    private function syncTags(Clip $clip, ?string $tagsString)
    {
        if (!$tagsString) {
            $clip->tags()->detach();
            return;
        }

        $tagNames = explode(',', $tagsString);
        $tagIds = [];

        foreach ($tagNames as $name) {
            $cleanName = trim($name, '# '); // On enlève le # et les espaces
            if (!empty($cleanName)) {
                $tag = Tag::firstOrCreate(['name' => $cleanName]);
                $tagIds[] = $tag->id;
            }
        }

        $clip->tags()->sync($tagIds);
    }
}