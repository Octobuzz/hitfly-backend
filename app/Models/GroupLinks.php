<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class GroupLinks extends Model
{
    const TYPE_SOCIAL_VK = 'vkontakte';
    const TYPE_SOCIAL_FB = 'facebook';
    const TYPE_SOCIAL_IN = 'instagram';
    const TYPE_SOCIAL_OD = 'odnoklassniki';
    protected $table = 'group_links';

    protected $fillable = [
        'music_group_id',
        'social_type',
        'link',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
