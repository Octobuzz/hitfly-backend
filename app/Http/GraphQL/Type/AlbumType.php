<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 28.02.19
 * Time: 13:15.
 */

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
                'alias' => 'id', // Use 'alias', if the database column is different from the type name
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
                'type' => Type::string(),
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
