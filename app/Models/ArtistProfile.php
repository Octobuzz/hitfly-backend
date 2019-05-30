<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    public function user(): BelongsTo
    {
        $this->belongsTo(User::class);
    }

    public function setCareerStartAttribute($attr){

        $this->attributes['career_start'] = Carbon::parse($attr)->format("Y-m-d");
    }
}
