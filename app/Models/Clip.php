<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Clip extends Model
{
    /**
     * The attributes that are mass assignable.
     * * These must match the columns you defined in your 
     * 2026_04_01_233510_create_clips_table migration.
     */
    protected $fillable = [
        'user_id',
        'title',
        'video_path',
        'description',
        'thumbnail_path',
    ];

    /**
     * Get the user (restaurateur) that owns the clip.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the views associated with this clip.
     */
    public function views(): HasMany
    {
        return $this->hasMany(View::class);
    }

    /**
     * Get the likes associated with this clip.
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }
}