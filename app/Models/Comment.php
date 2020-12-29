<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Отзывы критиков
 * Class Comment.
 *
 * @property int                                           $id
 * @property int|null                                      $commentable_id
 * @property string|null                                   $commentable_type
 * @property int|null                                      $user_id
 * @property string                                        $comment
 * @property int|null                                      $estimation
 * @property \Illuminate\Support\Carbon|null               $created_at
 * @property \Illuminate\Support\Carbon|null               $updated_at
 * @property \App\Models\Album|null                        $album
 * @property \Illuminate\Database\Eloquent\Model|\Eloquent $commentable
 * @property \App\Models\Track|null                        $track
 * @property \App\User|null                                $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereCommentableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereCommentableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereEstimation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereUserId($value)
 * @mixin \Eloquent
 */
class Comment extends Model
{
    const TYPE_ALBUM = 'album';
    const TYPE_TRACK = 'track';

    const CLASS_NAME = [
        Track::class => self::TYPE_TRACK,
        Album::class => self::TYPE_ALBUM,
    ];

    protected $table = 'comments';

    const FILLABLE = [
        'commentable_id',
        'commentable_type',
        'user_id',
        'comment',
    ];

    protected $fillable = [
        'commentable_id',
        'commentable_type',
        'user_id',
        'comment',
        'estimation',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function track(): BelongsTo
    {
        return $this->belongsTo(Track::class, 'commentable_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class, 'commentable_id');
    }
}
