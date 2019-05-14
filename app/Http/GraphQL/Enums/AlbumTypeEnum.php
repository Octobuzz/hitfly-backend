<?php

namespace App\Http\GraphQL\Enums;

use App\Models\Album;
use Rebing\GraphQL\Support\Type as GraphQLType;

class AlbumTypeEnum extends GraphQLType
{
    protected $enumObject = true;

    protected $attributes = [
        'name' => 'AlbumTypeEnum',
        'description' => 'Тип альбома',
        'values' => [
            Album::TYPE_ALBUM => Album::TYPE_ALBUM,
            Album::TYPE_COLLECTION => Album::TYPE_COLLECTION,
            Album::TYPE_EP => Album::TYPE_EP,
            Album::TYPE_SINGLE => Album::TYPE_SINGLE,
            ],
    ];
}
