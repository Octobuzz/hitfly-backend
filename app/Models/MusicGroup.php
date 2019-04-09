<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Музыкальная группа.
 *
 * Class Group
 *
 * @property int                                                          $id
 * @property int                                                          $creator_group_id
 * @property string|null                                                  $avatar_group
 * @property string                                                       $name
 * @property string                                                       $career_start_year
 * @property int|null                                                     $type_music_group_id
 * @property int|null                                                     $genre_id
 * @property string|null                                                  $description
 * @property \Illuminate\Support\Carbon|null                              $created_at
 * @property \Illuminate\Support\Carbon|null                              $updated_at
 * @property string|null                                                  $deleted_at
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Album[] $albums
 * @property \App\Models\Genre|null                                       $genre
 *
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MusicGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MusicGroup newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\MusicGroup onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MusicGroup query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MusicGroup whereAvatarGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MusicGroup whereCareerStartYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MusicGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MusicGroup whereCreatorGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MusicGroup whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MusicGroup whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MusicGroup whereGenreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MusicGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MusicGroup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MusicGroup whereTypeMusicGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MusicGroup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\MusicGroup withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\MusicGroup withoutTrashed()
 * @mixin \Eloquent
 */
class MusicGroup extends Model
{
    use SoftDeletes;

    protected $table = 'music_group';

    protected $fillable = [
        'name',
        'avatar_group',
        'career_start_year',
        'creator_group_id',
        'description',
    ];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class, 'genre_id');
    }

    public function albums(): HasMany
    {
        return $this->hasMany(Album::class);
    }

    public function creatorGroup()
    {
        return $this->belongsTo(User::class, 'creator_group_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'creator_group_id');
    }

    public function setUser(User $user)
    {
        $this->creator_group_id = $user->id;
    }

    public function socialLinks()
    {
        return $this->hasMany(GroupLinks::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }
}
