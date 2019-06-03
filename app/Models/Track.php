<?php

namespace App\Models;

use App\Events\Track\TrackCreatedEvent;
use App\Models\Traits\Itemable;
use App\Models\Traits\PictureField;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Track extends Model
{
    use SoftDeletes, Itemable, PictureField;

    const MIN_LISTENING = 50;

    protected $table = 'tracks';

    protected $fillable = [
        'track_name',
        'album_id',
        'genre_id',
        'user_id',
        'singer',
        'song_text',
        'filename',
        'track_date',
        'track_hash',
        'state',
    ];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected $dispatchesEvents = [
        'created' => TrackCreatedEvent::class,
    ];

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

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
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
        return $this->belongsToMany(Track::class, 'collection_track')->withTimestamps();
    }

    public function userFavourite()
    {
        return $this->morphMany(Favourite::class, 'favouriteable')->where('user_id', \Auth::user()->id);
    }

    public function userPlayLists()
    {
        return $this->belongsToMany(Collection::class, 'collection_track')
            ->where('collections.user_id', '=', \Auth::user()->id);
    }

    public function getUrl()
    {
        return Storage::disk('admin')->url($this->filename);
    }

    public function getName(): string
    {
        return $this->track_name;
    }

    public function genres(): MorphToMany
    {
        return $this->morphToMany(Genre::class, 'genreable', 'genres_bindings')->withTimestamps();
    }

    public function getImage(): ?string
    {
        $baseImage = $this->getOriginal('cover');

        if (null === $baseImage && null !== $this->album) {
            $baseImage = $this->album->image;
        }

        return $baseImage;
    }

    public function getImageUrl(): ?string
    {
        if (null === $this->getImage()) {
            $img = 'default.jpg';
        } else {
            $img = $this->getImage();
        }

        return '/storage/'.$img;
    }
}
