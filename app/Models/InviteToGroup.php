<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\InviteToGroup.
 *
 * @property int                             $id
 * @property int                             $music_group_id
 * @property string|null                     $email
 * @property int|null                        $user_id
 * @property int|null                        $accept
 * @property string|null                     $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InviteToGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InviteToGroup newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\InviteToGroup onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InviteToGroup query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InviteToGroup whereAccept($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InviteToGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InviteToGroup whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InviteToGroup whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InviteToGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InviteToGroup whereMusicGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InviteToGroup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InviteToGroup whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\InviteToGroup withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\InviteToGroup withoutTrashed()
 * @mixin \Eloquent
 */
class InviteToGroup extends Model
{
    use SoftDeletes;
    protected $table = 'invite_to_groups';

    protected $fillable = [
        'music_group_id',
        'email',
        'user_id',
        'accept',
        'estimation',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
