<?php

namespace App\Models;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use App\Models\Traits\Itemable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Kalnoy\Nestedset\NodeTrait;

/**
 * Жанры музыки.
 *
 * Class Genre
 *
 * @property int                             $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null                     $deleted_at
 * @property string                          $name
 * @property string|null                     $image
 *
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Genre newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Genre newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Genre onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Genre query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Genre whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Genre whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Genre whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Genre whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Genre whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Genre whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Genre withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Genre withoutTrashed()
 * @mixin \Eloquent
 */
class Genre extends Model
{
    use SoftDeletes, Itemable, NodeTrait, GraphQLAuthTrait;

    protected $table = 'genres';

    protected $fillable = [
        'name',
        'image',
    ];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function userFavourite()
    {
        $user = $this->getGuard()->user();

        return $this->morphMany(Favourite::class, 'favouriteable')->where('user_id', null === $user ? null : $user->id);
    }

    public function group(): BelongsToMany
    {
        return $this->belongsToMany(MusicGroup::class, 'music_group_genre');
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getImageAttribute($image)
    {
        return Storage::disk('admin')->url($image);
    }

    public function artistGenres(): BelongsToMany
    {
        return $this->belongsToMany(ArtistProfile::class, 'music_group_genre')->withTimestamps();
    }

    public function tracks()
    {
        return $this->morphedByMany(Track::class, 'genreable', 'genres_bindings');
    }
}
