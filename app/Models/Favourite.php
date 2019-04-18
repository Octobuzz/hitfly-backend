<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Favourite extends Model
{
    const TYPE_ALBUM = 'album';
    const TYPE_TRACK = 'track';
    const TYPE_GENRE = 'genre';

    const CLASS_NAME = [
        Album::class => self::TYPE_ALBUM,
        Track::class => self::TYPE_TRACK,
        Genre::class => self::TYPE_GENRE,
    ];
    protected $fillable = [
        'favouriteable_type',
        'favouriteable_id',
        'user_id',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function favouriteable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function track(): BelongsTo
    {
        return $this->belongsTo(Track::class, 'favouriteable_id');
    }

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class, 'favouriteable_id');
    }

    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class, 'favouriteable_id');
    }
}
