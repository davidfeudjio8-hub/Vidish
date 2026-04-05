<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'dish_id', 
        'restaurant_id',
        'video_path', 
        'thumbnail_path', 
        'description', 
        'user_id'
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function dish()
    {
        return $this->belongsTo(Dish::class);
    }

    public function tags()
    {
        // Vérifie bien que ta table pivot est 'taggables' dans ta DB
        return $this->belongsToMany(Tag::class, 'taggables');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function views()
    {
        return $this->hasMany(View::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}