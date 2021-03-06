<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 05.04.19
 * Time: 17:22.
 */

namespace App\Http\GraphQL\Interfaces;

use App\Http\GraphQL\Fields\AvatarSizesField;
use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use App\User;
use Carbon\Carbon;
use Rebing\GraphQL\Support\InterfaceType;
use GraphQL\Type\Definition\Type;
use App\Http\GraphQL\Privacy\IsAuthPrivacy;
use App\Dictionaries\RoleDictionary;

class UserInterface extends InterfaceType
{
    use GraphQLAuthTrait;
    protected $attributes = [
        'name' => 'UserInterface',
        'description' => 'Пользовательский инетерфейс',
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'id пользователя',
            ],
            'username' => [
                'type' => Type::string(),
                'description' => 'Имя пользователя',
            ],
            'dateRegister' => [
                'type' => Type::string(),
                'description' => 'Дата регистрации пользователя',
                'alias' => 'created_at',
                'resolve' => function ($model) {
                    return $model->created_at;
                },
            ],
            'gender' => [
                'type' => \GraphQL::type('GenderType'),
                'description' => 'Пол пользователя',
            ],
            'musicGroups' => [
                'type' => Type::listOf(\GraphQL::type('MusicGroup')),
                'description' => 'Музыкальные группы пользователя',
            ],
            'followersCount' => [
                'type' => Type::int(),
                'description' => 'Количество подписчиков',
                'resolve' => function ($model) {
                    return $model->followers->count();
                },
                'selectable' => false,
            ],
            'location' => [
                'type' => \GraphQL::type('CityType'),
                'description' => 'Локация пользователя',
                'alias' => 'city_id',
                //'selectable' => false,
                'resolve' => function ($model) {
                    return $model->location;
                },
            ],
            'avatar' => AvatarSizesField::class,
            'favouriteGenres' => [
                'type' => Type::listOf(\GraphQL::type('Genre')),
                'description' => 'Любимые жанры пользователя',
            ],
            'roles' => [
                'type' => Type::listOf(\GraphQL::type('RoleType')),
                'description' => 'мои роли',
                'resolve' => function (User $model) {
                    return $model->roles;
                },
            ],
            'careerStart' => [
                'name' => 'careerStart',
                'description' => 'Начало карьеры',
                'type' => Type::string(),
                'resolve' => function (User $model) {
                    if (null !== $model->artistProfile) {
                        return $model->artistProfile->career_start;
                    } else {
                        return null;
                    }
                },
            ],
            'description' => [
                'name' => 'description',
                'description' => 'Описание деятельности',
                'type' => Type::string(),
                'resolve' => function (User $model) {
                    if (null !== $model->artistProfile) {
                        return $model->artistProfile->description;
                    } else {
                        return null;
                    }
                },
                'selectable' => false,
            ],
            'genresPlay' => [
                'name' => 'genresPlay',
                'description' => 'жанры в которых играет',
                'type' => Type::listOf(\GraphQL::type('Genre')),
                'resolve' => function (User $model) {
                    if (null !== $model->artistProfile) {
                        return $model->artistProfile->genres;
                    }

                    return [];
                },
                'selectable' => false,
            ],
            'bpDaysInProgram' => [
                'type' => Type::int(),
                'description' => 'Количество дней в бонусной программе',
                'selectable' => false,
                'resolve' => function (User $model) {
                    $dateCreate = $model->created_at;
                    $carbon = Carbon::now();

                    return $carbon->diffInDays($dateCreate);
                },
            ],
            'iWatch' => [
                'type' => Type::boolean(),
                'description' => 'Я слежу за пользователем',
                'selectable' => false,
                'resolve' => function (User $model) {
                    return $model->iWatch();
                },
                'privacy' => IsAuthPrivacy::class,
            ],
            'favouritesTrackCount' => [
                'type' => Type::int(),
                'description' => 'Количество добавленых треков в избранное',
                'selectable' => false,
                'resolve' => function (User $model) {
                    return $model->likesTrack()->count();
                },
            ],
            'favouritesTrackTime' => [
                'type' => Type::float(),
                'description' => 'Время моих избранных треков',
                'resolve' => function (User $model) {
                    return $model->favouritesTracks()->sum('length');
                },
                'selectable' => false,
            ],
            'countListenedTracks' => [
                'type' => Type::int(),
                'description' => 'Количество прослушаных треков',
                'resolve' => function (User $model) {
                    return $model->listenedTracks()->count();
                },
                'selectable' => false,
            ],

            'watchAvaliable' => [
                'type' => Type::boolean(),
                'description' => 'Можно ли следить за пользователем',
                'resolve' => function ($model) {
                    return !$model->roles->contains('slug', RoleDictionary::ROLE_ADMIN);
                },
                'selectable' => false,
            ],
        ];
    }

    public function resolveType($root)
    {
        // Use the resolveType to resolve the Type which is implemented trough this interface
        $type = $root['type'];
        if ('User' === $type) {
            return \GraphQL::type('User');
        } elseif ('MyProfile' === $type) {
            return \GraphQL::type('MyProfile');
        }
    }
}
