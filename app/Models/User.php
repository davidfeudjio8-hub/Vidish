<?php

namespace App\Models;

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
        'role', // <--- AJOUTE CECI ICI
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
     * Relation avec le restaurant (Un utilisateur possède un restaurant).
     */
    public function restaurant()
    {
        return $this->hasOne(Restaurant::class);
    }

    /**
     * Helper pour vérifier rapidement le rôle (optionnel mais utile)
     */
    public function isRestaurateur(): bool
    {
        return $this->role === 'restaurateur';
    }
}