<?php

namespace App\Http\GraphQL\Type;

use App\Http\GraphQL\Privacy\UserPrivacy;
use App\Models\Purse;
use App\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use GraphQL;

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
//                'watchingUser' => [
//                    'type' => Type::listOf(\GraphQL::type('User')),
//                    'description' => 'Следит за пользователями',
//                ],
//                'watchingMusicGroup' => [
//                    'type' => Type::listOf(\GraphQL::type('MusicGroup')),
//                    'description' => 'Следит за группами',
//                ],
                'followersCount' => [
                    'type' => Type::int(),
                    'description' => 'Количество подписчиков',
                    'resolve' => function ($model) {
                        return $model->followers->count();
                    },
                    'selectable' => false,
                ],
                'bpLevelBonusProgram' => [
                    'type' => Type::nonNull(GraphQL::type('BonusProgramUserStatusEnum')),
                    'description' => 'Текущий уровень пользователя в бонусной программе',
                    'resolve' => function ($model) {
                        return (null === $model->level) ? User::LEVEL_NOVICE : $model->level;
                    },
                    'selectable' => false,
                ],
                'bpPoints' => [
                    'type' => Type::nonNull(Type::int()),
                    'description' => 'Количество накопленных баллов',
                    'resolve' => function (User $model) {
                        /** @var Purse $purse */
                        $purse = $model->purseBonus;

                        return (null === $purse) ? 0 : $purse->balance;
                    },
                    'selectable' => false,
                ],
                'bpProgressPercent' => [
                    'type' => Type::nonNull(Type::int()),
                    'description' => 'Процент заполнения для перехода на следующий уровень в бонусной программе',
                    'resolve' => function ($model) {
                        return 50; // todo доделать
                    },
                    'selectable' => false,
                ],

                'myTracksCount' => [
                    'type' => Type::int(),
                    'description' => 'Количество моих треков',
                    'resolve' => function ($model) {
                        return  $model->tracks->count();
                    },
                    'selectable' => false,
                ],
                'myTracksTime' => [
                    'type' => Type::float(),
                    'description' => 'Время моих треков',
                    'resolve' => function ($model) {
                        return  $model->myTracksTime();
                    },
                    'selectable' => false,
                ],
            ]
        );
    }
}
