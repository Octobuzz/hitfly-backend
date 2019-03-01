<?php

namespace App\Http\GraphQL\Enums;

use App\Models\Comment;
use Rebing\GraphQL\Support\Type as GraphQLType;

class CommentTypeEnum extends GraphQLType
{
    protected $enumObject = true;

    protected $attributes = [
        'name' => 'CommentType',
        'description' => 'The types of demographic elements',
        'values' => [
            Comment::TYPE_ALBUM => Comment::TYPE_ALBUM,
            Comment::TYPE_TRACK => Comment::TYPE_TRACK,
        ],
    ];
}
