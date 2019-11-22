<?php

namespace App\Models;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use App\Models\Traits\Itemable;
use App\Models\Traits\PictureField;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * @property User user
 * @property int id
 */
class Track extends Model
{
    use SoftDeletes, Itemable, PictureField, GraphQLAuthTrait;

    const FILE_UPLOAD = 'file_upload';
    const CREATE_WAVE = 'create_wave';
    const PENDING = 'pending';
    const CONVERT_TRACK = 'convert_track';
    const REMOVE = 'remove';
    const PUBLISHED = 'published';

    const MIN_LISTENING = 50;

    protected $table = 'tracks';

    /**
     * Атрибуты, которые должны быть преобразованы к базовым типам.
     *
     * @var array
     */
    protected $casts = [
        'music_wave' => 'array',
    ];

    protected $attributes = [
        'state' => self::FILE_UPLOAD,
        'track_name' => 'unknown',
        'song_text' => null,
    ];

    protected $fillable = [
        'track_name',
        'album_id',
        'genre_id',
        'user_id',
        'song_text',
        'filename',
        'track_date',
        'track_hash',
        'state',
        'music_group_id',
        'music_wave',
        'count_listen',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('state', function (Builder $builder) {
            $builder->where('state', '=', self::PUBLISHED);
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

    public function getUser(): BelongsTo //при совпадении названия поля в GraphQL и метода в модели, на выходе получается мешанина
    {
        return $this->user();
    }

    public function musicGroup(): BelongsTo
    {
        return $this->belongsTo(MusicGroup::class, 'music_group_id');
    }

    public function getMusicGroup(): BelongsTo //при совпадении названия поля в GraphQL и метода в модели, на выходе получается мешанина
    {
        return $this->musicGroup();
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
        return $this->belongsToMany(Collection::class, 'collection_track')->withTimestamps();
    }

    public function userFavourite()
    {
        $user = $this->getGuard()->user();

        return $this->morphMany(Favourite::class, 'favouriteable')->where('user_id', null === $user ? null : $user->id);
    }

    public function favourites()
    {
        return $this->morphMany(Favourite::class, 'favouriteable');
    }

    public function userPlayLists()
    {
        return $this->belongsToMany(Collection::class, 'collection_track')
            ->where('collections.user_id', '=', $this->getGuard()->user()->id);
    }

    public function getUrl()
    {
        if (null !== $this->getGuard()->user() && null !== $this->bitrate_hight) {
            return Storage::disk('admin')->url($this->bitrate_hight);
        }

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

    public function getAuthor(): ?string
    {
        if (null !== $this->getMusicGroup) {
            return $this->getMusicGroup->name;
        }
        if (null !== $this->getUser) {
            return $this->getUser->username;
        }

        return 'unknown';
    }

    public function getPathTrack(): string
    {
        $user_id = $this->user_id;

        return "tracks/$user_id";
    }
}
