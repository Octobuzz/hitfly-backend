<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property Collection collection
 * @property Track track
 * @property Album album
 * @property User user
 */
class Favourite extends Model
{
    use SoftDeletes;
    public const TYPE_ALBUM = 'album';
    public const TYPE_TRACK = 'track';
    public const TYPE_GENRE = 'genre';
    public const TYPE_COLLECTION = 'collection';
    public const TYPE_LIFE_HACK = 'life_hack';

    public const CLASS_NAME = [
        Album::class => self::TYPE_ALBUM,
        Track::class => self::TYPE_TRACK,
        Genre::class => self::TYPE_GENRE,
        Collection::class => self::TYPE_COLLECTION,
        Lifehack::class => self::TYPE_LIFE_HACK,
    ];
    protected $fillable = [
        'favouriteable_type',
        'favouriteable_id',
        'user_id',
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

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class, 'favouriteable_id');
    }

    public function lifehack(): BelongsTo
    {
        return $this->belongsTo(Lifehack::class, 'favouriteable_id');
    }
}
