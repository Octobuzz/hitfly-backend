<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 28.02.19
 * Time: 15:43.
 */

namespace App\Http\GraphQL\Type;

use App\Models\Comment;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class CommentAlbumType extends GraphQLType
{
    protected $attributes = [
        'name' => 'CommentAlbum',
        'model' => Comment::class,
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
            'album' => [
                'type' => GraphQL::type('Album'),
            ],
            'comment' => [
                'type' => Type::string(),
            ],
            'created_at' => [
                'type' => Type::string(),
            ],
            'estimation' => [
                'type' => Type::string(),
            ],
        ];
    }
}
