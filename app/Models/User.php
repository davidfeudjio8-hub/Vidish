<?php

namespace App\Models;

use App\Models\Dish;
use App\Models\Clip;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Les attributs qui peuvent être assignés en masse.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Les attributs à cacher pour la sérialisation.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les types de cast pour les attributs.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * --- RELATIONS VIDISH ---
     */

    // Un utilisateur (restaurateur) possède un restaurant
    public function restaurant()
    {
        return $this->hasOne(Restaurant::class);
    }

    // Un utilisateur peut publier plusieurs vidéos (si c'est un créateur/chef)
    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    // Un utilisateur peut avoir plusieurs likes (historique de ses likes)
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // Un utilisateur peut poster plusieurs commentaires
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Tes autres relations existantes
    public function dishes() { return $this->hasMany(Dish::class); }
    public function clips() { return $this->hasMany(Clip::class); }

    /**
     * --- HELPERS ---
     */

    public function isRestaurateur(): bool
    {
        return $this->role === 'restaurateur';
    }
}