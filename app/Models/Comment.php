<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Отзывы критиков
 * Class Comment.
 */
class Comment extends Model
{
    const TYPE_ALBUM = 'album';
    const TYPE_TRACK = 'track';

    const CLASS_NAME = [
        Track::class => 'track',
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
