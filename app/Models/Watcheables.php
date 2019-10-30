<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 29.03.19
 * Time: 14:44.
 */

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property User user
 */
class Watcheables extends Model
{
    public const TYPE_USER = 'user';
    public const TYPE_MUSIC_GROUP = 'music_group';

    public const CLASS_NAME = [
        User::class => self::TYPE_USER,
        MusicGroup::class => self::TYPE_MUSIC_GROUP,
    ];
    protected $table = 'watcheables';

    protected $fillable = [
        'watcheable_type',
        'watcheable_id',
        'user_id',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function watcheable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'watcheable_id');
    }

    /**
     * тот кто следит
     *
     * @return BelongsTo
     */
    public function watcher()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(MusicGroup::class, 'watcheable_id');
    }
}
