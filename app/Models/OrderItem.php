<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'dish_id',
        'quantity',
        'price', // Prix au moment de l'achat
    ];

    // L'article appartient à une commande
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // L'article fait référence à un plat
    public function dish(): BelongsTo
    {
        return $this->belongsTo(Dish::class);
    }
}