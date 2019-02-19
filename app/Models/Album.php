<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 08.02.19
 * Time: 11:27.
 */

namespace App\Models;

use App\Models\Traits\Itemable;
use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    use SoftDeletes, Itemable;

    protected $table = 'albums';

    protected $fillable = [
        'title',
        'author',
        'year',
        'cover',
    ];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function musicGroup(): BelongsTo
    {
        return $this->belongsTo(MusicGroup::class, 'music_group_id');
    }
}
