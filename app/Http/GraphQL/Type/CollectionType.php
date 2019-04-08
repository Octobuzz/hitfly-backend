<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 05.03.19
 * Time: 14:03.
 */

namespace App\Http\GraphQL\Type;

use App\Models\Collection;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class CollectionType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Collection',
        'model' => Collection::class,
        'description' => 'Коллекции(Playlist)',
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Индетификатор',
            ],
            'image' => [
                'type' => Type::string(),
                'description' => 'Изображение (полный url к изображению)',
            ],
            'title' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Название коллекции',
            ],
            'user' => [
                'type' => GraphQL::type('User'),
                'description' => 'Пользователь',
            ],
            'is_admin' => [
                'type' => Type::boolean(),
                'description' => 'Создано админом',
            ],
            'tracks' => [
                'type' => Type::listOf(GraphQL::type('Track')),
                'description' => 'Треки',
            ],
        ];
    }
}
