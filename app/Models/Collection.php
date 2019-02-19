<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $fillable = [
        'image',
        'title',
        'user_id',
        'is_admin',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
