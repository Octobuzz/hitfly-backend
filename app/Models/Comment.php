<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Отзывы критиков
 * Class Comment
 * @package App\Models
 */
class Comment extends Model
{
    protected $table = 'comments';

    protected $fillable = [
        'track_id',
        'album_id',
        'user_id',
        'comment',
        'estimation',

    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /*public static function boot()
    {
        parent::boot();

        static::creating(function ($comment) {
            //$comment->test =!($comment->track_id&&$comment->album_id)&&($comment->track_id||$comment->album_id);
            //echo json_encode($comment);die();

            //return !($comment->track_id&&$comment->album_id)&&($comment->track_id||$comment->album_id);
            return false;
        });



        static::updating(function ($track) {
            // do stuff
        });
    }*/

    public function commentable()
    {
        return $this->morphTo();
    }





    public function track(): BelongsTo
    {
        return $this->belongsTo(Track::class, 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class, 'id');
    }
}
