<?php

namespace App\Models;

use App\Models\Traits\Itemable;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function tracks(): BelongsToMany
    {
        return $this->belongsToMany(Track::class, 'collection_track')->withTimestamps();
    }

    public function getImageAttribute($nameAttribute)
    {
        return  url('/').'/storage/collection/'.$this->user_id.DIRECTORY_SEPARATOR.$nameAttribute;
    }
}
