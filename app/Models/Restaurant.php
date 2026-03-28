<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = ['user_id', 'name', 'address','latitude', 'longitude', 'description', 'logo_path','has_delivery'];

    public function dishes()
{
    return $this->hasMany(Dish::class);
}
}
