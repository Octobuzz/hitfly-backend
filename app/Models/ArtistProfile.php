<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ArtistProfile extends Model
{

    protected $fillable = [
        'career_start',
        'description',

    ];
    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'artist_profiles_genre')->withTimestamps();
    }
}
