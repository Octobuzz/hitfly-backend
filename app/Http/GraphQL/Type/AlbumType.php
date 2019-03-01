<?php

namespace App\Http\GraphQL\Type;

use App\Models\Album;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class AlbumType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Album',
        'model' => Album::class,
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
            ],
            'title' => [
                'type' => Type::string(),
            ],
            'genre' => [
                'type' => GraphQL::type('Genre'),
            ],
            'user' => [
                'type' => GraphQL::type('User'),
            ],
            'author' => [
                'type' => Type::string(),
            ],
            'year' => [
                'type' => Type::int(),
            ],
            'cover' => [
                'type' => Type::string(),
                'alias' => 'song_text',
            ],
            'musicGroup' => [
                'type' => GraphQL::type('MusicGroup'),
                'alias' => 'filename',
            ],
        ];
    }
}
