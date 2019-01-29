<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    protected $fillable = [
        'social_id',
        'social_driver',
        'avatar',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

}
