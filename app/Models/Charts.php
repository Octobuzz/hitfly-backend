<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Charts extends Model
{
    protected $fillable = [
        'track_id',
        'weekly_rate',
        'rating',
    ];

    public function track()
    {
        return $this->belongsTo(Track::class);
    }
}
