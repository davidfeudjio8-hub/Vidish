<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    /**
     * Affiche le feed pour les clients
     */
    public function index()
    {
        // On récupère les clips avec les relations pour éviter le problème N+1
        $clips = Video::with(['dish.restaurant', 'restaurant', 'tags'])->latest()->get();

        return view('client.feed', compact('clips'));
    }

    /**
     * Affiche la gestion des clips pour le restaurateur
     */
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

    /**
     * Enregistre un nouveau clip
     */
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

    /**
     * Met à jour un clip existant
     */
    public function update(Request $request, Video $clip)
    {
        // Sécurité : Vérifier que le clip appartient bien au restaurant de l'utilisateur
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
            // Supprimer l'ancienne vidéo physiquement
            if (Storage::disk('public')->exists($clip->video_path)) {
                Storage::disk('public')->delete($clip->video_path);
            }
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

    /**
     * Supprime un clip
     */
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

    /**
     * Gère la synchronisation des tags (Relation Polymorphe)
     */
    private function syncTags(Video $video, ?string $tagsString)
    {
        if (!$tagsString) {
            $video->tags()->detach();
            return;
        }

        // Nettoyage de la chaîne de caractères (virgules, espaces)
        $tagNames = array_filter(array_map('trim', explode(',', $tagsString)));
        $tagIds = [];

        foreach ($tagNames as $name) {
            $cleanName = ltrim($name, '#'); // Enlever le # si l'utilisateur l'a tapé
            
            if (!empty($cleanName)) {
                $tag = Tag::firstOrCreate(['name' => $cleanName]);
                $tagIds[] = $tag->id;
                
                // Correction ici : Utilisation de la relation polymorphe
                // On vérifie si le tag est déjà lié à cette vidéo précise
                if (!$video->tags->contains($tag->id)) {
                    $tag->increment('use_count');
                }
            }
        }

        // Met à jour la table 'taggables' automatiquement avec taggable_id et taggable_type
        $video->tags()->sync($tagIds);
    }
}