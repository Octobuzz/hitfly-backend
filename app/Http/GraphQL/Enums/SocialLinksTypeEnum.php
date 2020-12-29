<?php

namespace App\Http\GraphQL\Enums;

use App\Models\GroupLinks;
use Rebing\GraphQL\Support\Type as GraphQLType;

class SocialLinksTypeEnum extends GraphQLType
{
    protected $enumObject = true;

    protected $attributes = [
        'name' => 'SocialLinksTypeEnum',
        'description' => 'типы избранного',
        'values' => [
            GroupLinks::TYPE_SOCIAL_VK => GroupLinks::TYPE_SOCIAL_VK,
            GroupLinks::TYPE_SOCIAL_FB => GroupLinks::TYPE_SOCIAL_FB,
            GroupLinks::TYPE_SOCIAL_IN => GroupLinks::TYPE_SOCIAL_IN,
            GroupLinks::TYPE_SOCIAL_OD => GroupLinks::TYPE_SOCIAL_OD,
        ],
    ];
}
