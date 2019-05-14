<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class GenresBinding extends Model
{
    const TYPE_ALBUM = 'album';
    const TYPE_TRACK = 'track';

    protected $table = 'genres_bindings';

    const CLASS_NAME = [
        Album::class => self::TYPE_ALBUM,
        Track::class => self::TYPE_TRACK,
    ];

    protected $fillable = [
        'genreable_type',
        'genreable_id',
        'genre_id',
    ];

    public function favouriteable(): MorphTo
    {
        return $this->morphTo();
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class, 'genre_id');
    }

    public function track(): BelongsTo
    {
        return $this->belongsTo(Track::class, 'genreable_id');
    }

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class, 'genreable_id');
    }
}
