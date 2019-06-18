<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 05.03.19
 * Time: 14:03.
 */

namespace App\Http\GraphQL\Type;

use App\Http\GraphQL\Fields\PictureField;
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
            'image' => PictureField::class,
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
            'userFavourite' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'флаг избранного коллекции',
                'resolve' => function ($model) {
                    if ($model->userFavourite->count()) {
                        return true;
                    } else {
                        return false;
                    }
                },
                'selectable' => false,
            ],
            'countTracks' => [
                'type' => Type::int(),
                'description' => 'количество треков в коллекции',
                'resolve' => function ($model) {
                    return $model->tracks->count();
                },
                'selectable' => false,
            ],
            'favouritesCount' => [
                'type' => Type::int(),
                'description' => 'Количество добавлений коллекции в избранное',
                'resolve' => function ($model) {
                    return $model->favourites->count();
                },
                'selectable' => false,
            ],
            'isCollection' => [
                'type' => Type::boolean(),
                'description' => 'Является коллекцией',
                'resolve' => function ($model) {
                    return 1 === $model->is_admin ? true : false;
                },
                'selectable' => false,
            ],
        ];
    }
}
