<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * App\Models\GenresBinding.
 *
 * @property int                                           $id
 * @property string                                        $genreable_type
 * @property int                                           $genreable_id
 * @property int                                           $genre_id
 * @property \Illuminate\Support\Carbon|null               $created_at
 * @property \Illuminate\Support\Carbon|null               $updated_at
 * @property \App\Models\Album                             $album
 * @property \Illuminate\Database\Eloquent\Model|\Eloquent $favouriteable
 * @property \App\Models\Genre|null                        $genre
 * @property \App\Models\Track                             $track
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GenresBinding newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GenresBinding newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GenresBinding query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GenresBinding whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GenresBinding whereGenreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GenresBinding whereGenreableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GenresBinding whereGenreableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GenresBinding whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GenresBinding whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
