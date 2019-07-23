<?php

namespace App\Http\GraphQL\Type;

use App\Models\Genre;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Cache;
use Rebing\GraphQL\Support\Type as GraphQLType;

class GenreType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Genre',
        'model' => Genre::class,
        'description' => 'Жанры',
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'id жанра',
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'Имя жанра',
            ],
            'image' => [
                'type' => Type::string(),
                'description' => 'Логотип жанра',
            ],
            'userFavourite' => [
                'type' => Type::boolean(),
                'description' => 'флаг избранного жанра',
                'resolve' => function ($model) {
                    if ($model->userFavourite->count()) {
                        return true;
                    } else {
                        return false;
                    }
                },
                'selectable' => false,
                'privacy' => function (array $args) {
                    return \Auth::check();
                },
            ],
            'countTracks' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Кол-во треков',
                'selectable' => false,
                'resolve' => function (Genre $model) {
                    $keyCache = md5($model);
                    $result = Cache::get($keyCache, null);
                    if (null !== $result) {
                        return $result;
                    }
                    $result = $model->tracks()->count();
                    Cache::add($keyCache, $result, now()->addMinutes(10));

                    return $result;
                },
            ],
        ];
    }
}
