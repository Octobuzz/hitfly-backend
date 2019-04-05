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

        $interface = \GraphQL::type('UserInterface');

        return array_merge(
            $interface->getFields(),
            [
                'email' => [
                    'type' => Type::string(),
                    'description' => 'Email пользователя',
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
                'watchingUser' => [
                    'type' => Type::listOf(\GraphQL::type('User')),
                    'description' => 'Следит за пользователями',
                ],
                'watchingMusicGroup' => [
                    'type' => Type::listOf(\GraphQL::type('MusicGroup')),
                    'description' => 'Следит за группами',
                ],
                'followersCount' => [
                    'type' => Type::int(),
                    'description' => 'Количество подписчиков',
                ],

            ]
        );
    }
}
