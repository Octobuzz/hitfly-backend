<?php

namespace App\Http\GraphQL\Type;

use App\Models\MusicGroup;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class MusicGroupType extends GraphQLType
{
    protected $attributes = [
        'name' => 'MusicGroup',
        'description' => 'MusicGroup',
        'model' => MusicGroup::class,
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of the ',
                'alias' => 'id', // Use 'alias', if the database column is different from the type name
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The id of the ',
            ],
            'avatarGroup' => [
                'type' => Type::string(),
                'description' => 'The id of the ',
                'alias' => 'avatar_group', // Use 'alias', if the database column is different from the type name
            ],
            'careerStartYear' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The id of the ',
                'alias' => 'career_start_year', // Use 'alias', if the database column is different from the type name
                'resolve' => function ($root) { // As a workaround
                    return $root->career_start_year;
                },
            ],
            'genre' => [
                'type' => GraphQL::type('Genre'),
                'description' => 'The id of the ',
                'alias' => 'genre', // Use 'alias', if the database column is different from the type name
            ],
            'creatorGroup' => [
                'type' => GraphQL::type('User'),
                'description' => 'The id of the ',
                'alias' => 'creator_group', // Use 'alias', if the database column is different from the type name
            ],
            'description' => [
                'type' => Type::string(),
                'description' => 'The id of the ',
                'alias' => 'description', // Use 'alias', if the database column is different from the type name
            ],
        ];
    }
}
