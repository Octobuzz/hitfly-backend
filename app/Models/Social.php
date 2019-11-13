<?php

namespace App\Models;

use App\User;
use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Social.
 *
 * @property int         $id
 * @property string|null $avatar
 * @property int         $social_id
 * @property string      $social_driver
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int|null    $user_id
 * @property User|null   $user
 *
 * @method static Builder|Social newModelQuery()
 * @method static Builder|Social newQuery()
 * @method static Builder|Social query()
 * @method static Builder|Social whereAvatar($value)
 * @method static Builder|Social whereCreatedAt($value)
 * @method static Builder|Social whereDeletedAt($value)
 * @method static Builder|Social whereId($value)
 * @method static Builder|Social whereSocialDriver($value)
 * @method static Builder|Social whereSocialId($value)
 * @method static Builder|Social whereUpdatedAt($value)
 * @method static Builder|Social whereUserId($value)
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
        return $this->belongsTo(User::class);
    }
}
