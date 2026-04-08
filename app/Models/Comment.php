<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse.
     */
    protected $fillable = [
        'user_id',
        'video_id',
        'content',
    ];

    /**
     * Récupérer l'utilisateur qui a écrit le commentaire.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Récupérer la vidéo sur laquelle le commentaire a été posté.
     */
    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}