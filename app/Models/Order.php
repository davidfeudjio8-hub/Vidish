<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'restaurant_id',
        'customer_name',
        'total_price',
        'status', // 'pending', 'preparing', 'completed', 'cancelled'
    ];

    // Une commande appartient à un restaurant
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    // Une commande contient plusieurs articles (plats)
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}