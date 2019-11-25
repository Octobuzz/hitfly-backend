<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 05.03.19
 * Time: 14:03.
 */

namespace App\Http\GraphQL\Type;

use App\Http\GraphQL\Fields\PictureField;
use App\Http\GraphQL\Privacy\IsAuthPrivacy;
use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use App\Models\Collection;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class CollectionType extends GraphQLType
{
    use GraphQLAuthTrait;
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
                'type' => Type::boolean(),
                'description' => 'флаг избранного коллекции',
                'resolve' => function ($model) {
                    if ($model->userFavourite->count()) {
                        return true;
                    } else {
                        return false;
                    }
                },
                'selectable' => false,
                'privacy' => IsAuthPrivacy::class,
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
                'privacy' => IsAuthPrivacy::class,
            ],
            'isSet' => [
                'type' => Type::boolean(),
                'description' => 'Является коллекцией',
                'resolve' => function ($model) {
                    return 1 === $model->is_admin ? true : false;
                },
                'selectable' => false,
            ],
            'my' => [
                'type' => Type::boolean(),
                'description' => 'мой альбом',
                'resolve' => function ($model) {
                    return $model->user_id === $this->getGuard()->user()->id ? true : false;
                },
                'selectable' => false,
                'privacy' => IsAuthPrivacy::class,
            ],
            'tracksTime' => [
                'type' => Type::float(),
                'description' => 'Время треков коллекции(плейлисте)',
                'resolve' => function ($model) {
                    return $model->tracks()->sum('length');
                },
                'selectable' => false,
            ],
            'tracksCount' => [
                'type' => Type::float(),
                'description' => 'Количество треков коллекции(плейлисте)',
                'resolve' => function ($model) {
                    return $model->tracks()->count();
                },
                'selectable' => false,
            ],
        ];
    }
}
