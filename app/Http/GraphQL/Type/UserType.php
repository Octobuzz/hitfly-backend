<?php

namespace App\Http\GraphQL\Type;

use App\Http\GraphQL\Privacy\UserPrivacy;
use App\User;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Eloquent\Collection;
use Rebing\GraphQL\Support\Type as GraphQLType;

class UserType extends GraphQLType
{
    protected $attributes = [
        'name' => 'User',
        'description' => 'Пользователь системы',
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
                'privacy' => UserPrivacy::class,
            ],
            'username' => [
                'type' => Type::string(),
                'description' => 'Имя пользователя',
            ],
            'accessToken' => [
                'type' => Type::string(),
                'description' => 'The access token',
                'alias' => 'access_token',
                'resolve' => function ($model) {
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
            ],
            'followersCount' => [
                'type' => Type::int(),
                'description' => 'Количество подписчиков',
            ],
            'watchingUsers' => [
                'type' => Type::listOf(\GraphQL::type('User')),
                'description' => 'За кем следит пользователь',
                'resolve' => function ($model) {
                    return new Collection([]); //todo
                },
            ],
        ];
    }
}
