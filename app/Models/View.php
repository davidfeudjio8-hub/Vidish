<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class View extends Model
{
    protected $fillable = ['clip_id', 'ip_address'];

    public function clip(): BelongsTo
    {
        return $this->belongsTo(Clip::class);
    }
    public function video()
{
    return $this->belongsTo(Video::class);
}
}
