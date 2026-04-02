<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Dish;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    /**
     * Affiche la page "Mes Clips" pour le Restaurateur (Vendor)
     */
    public function vendorIndex()
    {
        // On récupère uniquement les vidéos de l'utilisateur connecté
        // On charge la relation 'dish' pour afficher le nom du plat sur la carte
        $clips = Video::where('user_id', Auth::id())
                      ->with('dish')
                      ->latest()
                      ->get();

        // On retourne la vue spécifique au dashboard vendor
        return view('vendor.clips.index', compact('clips'));
    }

    /**
     * Gère l'upload des clips depuis le Dashboard Vendor.
     */
    public function store(Request $request)
    {
        $request->validate([
            // Augmentation légère de la limite si nécessaire (50MB)
            'video' => 'required|mimes:mp4,mov,ogg,quicktime|max:51200', 
            'description' => 'nullable|string|max:255',
            'dish_id' => 'nullable|exists:dishes,id',
        ]);

        if ($request->hasFile('video')) {
            // Stockage dans storage/app/public/clips
            $path = $request->file('video')->store('clips', 'public');

            Video::create([
                'video_path'  => $path,
                'dish_id'     => $request->dish_id ?: null,
                'description' => $request->description,
                'user_id'     => Auth::id(), // ID de l'utilisateur qui upload
            ]);
        }

        return redirect()->back()->with('status', 'Le clip a été publié avec succès !');
    }

    /**
     * Supprimer un clip
     */
    public function destroy($id)
    {
        $video = Video::where('user_id', Auth::id())->findOrFail($id);
        $video->delete();

        return redirect()->back()->with('status', 'Clip supprimé.');
    }
}