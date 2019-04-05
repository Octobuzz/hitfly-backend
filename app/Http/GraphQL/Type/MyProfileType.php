<?php

namespace App\Http\GraphQL\Type;

use App\Http\GraphQL\Privacy\UserPrivacy;
use App\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class MyProfileType extends GraphQLType
{
    protected $attributes = [
        'name' => 'MyProfile',
        'description' => 'Получение самого себя',
        'model' => User::class,
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Id пользователя',
                'alias' => 'id', // Use 'alias', if the database column is different from the type name
            ],
            'email' => [
                'type' => Type::string(),
                'description' => 'Email пользователя',
            ],
            'username' => [
                'type' => Type::string(),
                'description' => 'Имя пользователя',
            ],
            'accessToken' => [
                'type' => Type::string(),
                'description' => 'The access token',
                'alias' => 'access_token',
                'resolve' => function (User $model) {
                    return $model->access_token;
                },
                'privacy' => UserPrivacy::class,
            ],
            'gender' => [
                'type' => \GraphQL::type('GenderType'),
                'description' => 'Пол пользователя',
            ],
            'musicGroups' => [
                'type' => Type::listOf(\GraphQL::type('MusicGroup')),
                'description' => 'Музыкальные группы пользователя',
            ],
            'dateRegister' => [
                'type' => Type::string(),
                'description' => 'Дата регистрации пользователя',
                'alias' => 'created_at',
                'resolve' => function ($model) {
                    return $model->created_at;
                },
            ],
            'favoriteSongsCount' => [
                'type' => Type::int(),
                'description' => 'Количество любимых песен',
                'resolve' => function (User $model) {
                    return $model->likesTrack()->count();
                },
            ],
            'favoriteAlbumCount' => [
                'type' => Type::int(),
                'description' => 'Количество любимых альбомов',
                'resolve' => function (User $model) {
                    return $model->likesAlbum()->count();
                },
            ],
            'followersCount' => [
                'type' => Type::int(),
                'description' => 'Количество подписчиков',
            ],
            'watchingUser' => [
                'type' => Type::listOf(\GraphQL::type('User')),
                'description' => 'Следит за пользователями',
            ],
            'watchingMusicGroup' => [
                'type' => Type::listOf(\GraphQL::type('MusicGroup')),
                'description' => 'Следит за группами',
            ],
            'location' => [
                'type' => \GraphQL::type('CityType'),
                'description' => 'Локация пользователя',
                'alias' => 'city_id',
            ],
        ];
    }
}
