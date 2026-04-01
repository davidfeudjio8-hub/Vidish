<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = ['user_id', 'name', 'address','latitude', 'longitude', 'description', 'image_path','has_delivery','is_open'];

    public function dishes()
{
    return $this->hasMany(Dish::class);
}
public function indexPlats()
{
    // Récupère les plats liés au restaurant de l'utilisateur connecté
    $plats = auth()->user()->restaurant->plats; 
    return view('restaurant.plats.index', compact('plats'));
}

public function indexClips()
{
    // Récupère les vidéos (clips) liées au restaurant
    $clips = auth()->user()->restaurant->clips;
    return view('restaurant.clips.index', compact('clips'));
}
protected $casts = [
    'is_open' => 'boolean',
    'has_delivery' => 'boolean',
];
}
