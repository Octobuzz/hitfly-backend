<?php

namespace App\Http\GraphQL\Enums;

use App\Models\Favourite;
use Rebing\GraphQL\Support\Type as GraphQLType;

class FavouriteTypeEnum extends GraphQLType
{
    protected $enumObject = true;

    protected $attributes = [
        'name' => 'FavouriteTypeEnum',
        'description' => 'типы избранного',
        'values' => [
            Favourite::TYPE_ALBUM => Favourite::TYPE_ALBUM,
            Favourite::TYPE_TRACK => Favourite::TYPE_TRACK,
            Favourite::TYPE_COLLECTION => Favourite::TYPE_COLLECTION,
        ],
    ];
}
