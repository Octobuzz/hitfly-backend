<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property User user
 */
class ArtistProfile extends Model
{
    protected $fillable = [
        'user_id',
        'career_start',
        'description',
    ];

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'artist_profiles_genre')->withTimestamps();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function setCareerStartAttribute($attr)
    {
        $this->attributes['career_start'] = Carbon::parse($attr)->format('Y-m-d');
    }
}
