<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 08.02.19
 * Time: 11:27.
 */

namespace App\Models;

use App\Models\Traits\Itemable;
use App\Models\Traits\PictureField;
use App\User;
use Barryvdh\LaravelIdeHelper\Eloquent;
use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * Музыкальный альбом
 *
 * App\Models\Album
 *
 * @property int                             $id
 * @property string|null                     $type
 * @property string                          $title
 * @property string                          $author
 * @property int                             $year
 * @property string                          $cover
 * @property int                             $likes
 * @property int                             $dislikes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int                             $genre_id
 * @property \App\Models\MusicGroup          $musicGroup
 * @property int                             $user_id
 *
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Album newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Album newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Album onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Album query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Album whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Album whereCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Album whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Album whereDislikes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Album whereGenreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Album whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Album whereLikes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Album whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Album whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Album whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Album whereYear($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Album withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Album withoutTrashed()
 * @mixin Eloquent
 *
 * @property int|null $music_group_id
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Album whereMusicGroupId($value)
 */
class Album extends Model
{
    use SoftDeletes, Itemable, PictureField, CascadeSoftDeletes;

    protected $cascadeDeletes = ['tracks', 'comments', 'favourites'];

    public const TYPE_ALBUM = 'album';
    public const TYPE_EP = 'EP';
    public const TYPE_SINGLE = 'single';
    public const TYPE_COLLECTION = 'collection';

    protected $table = 'albums';

    protected $nameFolder = 'albums';
    protected $sizeTypePicture = 'album';

    protected $fillable = [
        'title',
        'author',
        'year',
        'cover',
        'album_img',
        'user_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'year' => 'date:Y',
    ];

    public function musicGroup(): BelongsTo
    {
        return $this->belongsTo(MusicGroup::class, 'music_group_id');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function genres(): MorphToMany
    {
        return $this->morphToMany(Genre::class, 'genreable', 'genres_bindings')->withTimestamps();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function userFavourite()
    {
        return $this->morphMany(Favourite::class, 'favouriteable')->where('user_id', \Auth::user()->id);
    }

    public function favourites()
    {
        return $this->morphMany(Favourite::class, 'favouriteable');
    }

    public function getCoverAttribute($image)
    {
        return Storage::disk('admin')->url($image);
    }

    public function getName(): string
    {
        return $this->title;
    }

    public function setUser(User $user)
    {
        $this->user_id = $user->id;
    }

    public function getPath(): string
    {
        return 'albums/'.$this->user_id.'/';
    }

    public function getImage(): ?string
    {
        return $this->getOriginal('cover');
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
        return $this->author;
    }

    public function tracks(): HasMany
    {
        return $this->hasMany(Track::class);
    }

    public function tracksTime(): float
    {

        return $this->tracks()->sum('length');
    }
}
