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
        'description' => 'Музыкальная группа',
        'model' => MusicGroup::class,
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'ID Группы',
                'alias' => 'id', // Use 'alias', if the database column is different from the type name
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Имя группы',
            ],
            'avatarGroup' => [
                'type' => Type::string(),
                'description' => 'Аватарка группы',
                'alias' => 'avatar_group', // Use 'alias', if the database column is different from the type name
            ],
            'careerStartYear' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Год начала карьеры',
                'alias' => 'career_start_year', // Use 'alias', if the database column is different from the type name
                'resolve' => function ($root) { // As a workaround
                    return $root->career_start_year;
                },
            ],
            'genre' => [
                'type' => GraphQL::type('Genre'),
                'description' => 'Жанр группы',
                'alias' => 'genre', // Use 'alias', if the database column is different from the type name
            ],
            'creatorGroup' => [
                'type' => GraphQL::type('User'),
                'description' => 'Создатель группы',
                'alias' => 'creator_group_id', // Use 'alias', if the database column is different from the type name
            ],
            'description' => [
                'type' => Type::string(),
                'description' => 'Описание группы',
                'alias' => 'description', // Use 'alias', if the database column is different from the type name
            ],
            'location' => [
                'type' => \GraphQL::type('CityType'),
                'description' => 'Локация группы',
                'alias' => 'city_id',
            ],
        ];
    }
}
