<?php

namespace App\Models;

use App\Models\Traits\Itemable;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use Itemable;

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

    public function tracks()
    {
        return $this->belongsToMany(Track::class, 'collection_track')->withTimestamps();
    }
}
