<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Отзывы критиков
 * Class Comment.
 */
class Comment extends Model
{
    protected $table = 'comments';

    protected $fillable = [
        'track_id',
        'album_id',
        'user_id',
        'comment',
        'comment_date',
        'estimation',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function track(): BelongsTo
    {
        return $this->belongsTo(Track::class, 'track_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class, 'album_id');
    }
}
