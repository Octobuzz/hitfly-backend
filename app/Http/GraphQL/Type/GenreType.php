<?php

namespace App\Http\GraphQL\Type;

use App\BuisnessLogic\BonusProgram\UserLevels;
use App\Http\GraphQL\Privacy\IsAuthPrivacy;
use App\Models\Genre;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
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
            'haveSubGenres' => [
                'type' => Type::boolean(),
                'description' => 'Есть вложенные жанры',
                'resolve' => function ($model) {
                    return $model->children()->count();
                },
                'selectable' => false,
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
                'privacy' => IsAuthPrivacy::class,
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

            'countListenedByUser' => [
                'type' => Type::int(),
                'description' => 'Количество прослушиваний жанра текущим пользователем(вернет NULL если не попадает в топ прослушивания)',
                'resolve' => function ($model) {
                    $user = Auth::guard('json')->user();
                    $userLevels = new UserLevels();
                    $keyCache = $user->id.'_getCountListenedTracksByGenres';
                    $listenGenres = Cache::tags(['countListenedTracksByGenres'])->get($keyCache, null);
                    if (null === $listenGenres) {
                        $listenGenres = $userLevels->getCountListenedTracksByGenres($user, 5);
                        Cache::tags(['countListenedTracksByGenres'])->put($keyCache, $listenGenres, now()->addHours(24));
                    }

                    if (array_key_exists($model->id, $listenGenres)) {
                        return $listenGenres[$model->id];
                    } else {
                        return null;
                    }
                },
                'selectable' => false,
                'privacy' => IsAuthPrivacy::class,
            ],
        ];
    }
}
