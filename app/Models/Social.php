<?php

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Social.
 *
 * @property int                             $id
 * @property string|null                     $avatar
 * @property int                             $social_id
 * @property string                          $social_driver
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null                     $deleted_at
 * @property int|null                        $user_id
 * @property \App\User|null                  $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Social newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Social newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Social query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Social whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Social whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Social whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Social whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Social whereSocialDriver($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Social whereSocialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Social whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Social whereUserId($value)
 * @mixin Eloquent
 */
class Social extends Model
{
    protected $fillable = [
        'social_id',
        'social_driver',
        'avatar',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}
