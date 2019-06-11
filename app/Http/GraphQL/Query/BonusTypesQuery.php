<?php

namespace App\Http\GraphQL\Query;

use App\Models\BonusType;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use GraphQL;
use Rebing\GraphQL\Support\SelectFields;

/**
 * Class AlbumQuery.
 */
class BonusTypesQuery extends Query
{
    protected $attributes = [
        'name' => 'BonusTypesQuery',
        'description' => 'Бонусная программа. список на что потратить балы',
    ];

    public function type()
    {
        return  Type::listOf(GraphQL::type('BonusTypes'));
    }

    public function resolve($root, $args, SelectFields $fields)
    {
        return BonusType::all();
    }
}
