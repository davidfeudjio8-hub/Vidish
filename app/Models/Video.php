<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = ['dish_id', 'video_path', 'thumbnail_path'];
    
    public function dish()
{
    return $this->belongsTo(Dish::class);
}
}
