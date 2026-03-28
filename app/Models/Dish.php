<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    protected $fillable = ['restaurant_id', 'name', 'description', 'price', 'image_path', 'views_count'];

    public function videos()
{
    return $this->hasMany(Video::class);
}
public function restaurant()
{
    return $this->belongsTo(Restaurant::class);
}
// Relation avec le restaurateur
    public function vendor() {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scope pour récupérer les stats de la semaine (pour le widget)
    public function scopePopular($query) {
        return $query->orderBy('views_count', 'desc');
    }
}
