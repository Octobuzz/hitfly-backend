<?php

namespace App\Models;

use App\Models\Traits\Itemable;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Track extends Model
{
    use SoftDeletes, Itemable;

    protected $table = 'tracks';

    protected $fillable = [
        'track_name',
        'album_id',
        'genre_id',
        'singer',
        'song_text',
        'filename',
        'track_date',
    ];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public static function boot()
    {
        parent::boot();

        /** @var Track $track % */
        static::creating(function ($track) {
            //todo create hash file, and uploads
        });

        static::updating(function ($track) {
            // do stuff
        });
    }

    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class, 'genre_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class, 'album_id');
    }

    public function charts(): HasMany
    {
        return $this->hasMany(Track::class);
    }

    public function userTracks(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_track', 'track_id', 'user_id')->withPivot('listen_counts')->withTimestamps();
    }

    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(Collection::class, 'collection_track')->withTimestamps();
    }
}
