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

    /**
     * Relation Polymorphe Many-to-Many
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function views()
    {
        return $this->hasMany(View::class);
    }

    // Un utilisateur peut aimer cette vidéo
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // Cette vidéo peut recevoir plusieurs commentaires
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}