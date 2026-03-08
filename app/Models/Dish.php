<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    protected $fillable = ['restaurant_id', 'name', 'price', 'description'];

    public function videos()
{
    return $this->hasMany(Video::class);
}
public function restaurant()
{
    return $this->belongsTo(Restaurant::class);
}

}
