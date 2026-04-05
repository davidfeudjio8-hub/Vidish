<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Tag extends Model {
    protected $fillable = ['name', 'use_count'];

    // Pour récupérer les vidéos ou plats liés à ce tag
    public function videos()
{
    return $this->belongsToMany(Video::class, 'taggable');
}

    public function dishes() {
        return $this->morphedByMany(Dish::class, 'taggable');
    }
}
