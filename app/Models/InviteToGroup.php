<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
