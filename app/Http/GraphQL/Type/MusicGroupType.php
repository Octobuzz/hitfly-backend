<?php

namespace App\Http\GraphQL\Type;

use App\Http\GraphQL\Fields\PictureField;
use App\Http\GraphQL\Privacy\IsAuthPrivacy;
use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use App\Models\MusicGroup;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class MusicGroupType extends GraphQLType
{
    use GraphQLAuthTrait;
    protected $attributes = [
        'name' => 'MusicGroup',
        'description' => 'Музыкальная группа',
        'model' => MusicGroup::class,
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'ID Группы',
                'alias' => 'id', // Use 'alias', if the database column is different from the type name
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Имя группы',
            ],
            'avatarGroup' => PictureField::class,
            'careerStartYear' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Год начала карьеры',
                'alias' => 'career_start_year', // Use 'alias', if the database column is different from the type name
                'resolve' => function ($root) { // As a workaround
                    return $root->career_start_year;
                },
            ],
            'genres' => [
                'type' => Type::listOf(GraphQL::type('Genre')),
                'description' => 'Жанр группы',
            ],
            'creatorGroup' => [
                'type' => Type::nonNull(\GraphQL::type('User')),
                'description' => 'Создатель группы',
                'alias' => 'creator_group_id', // Use 'alias', if the database column is different from the type name
                'resolve' => function ($model) {
                    return $model->user;
                },
                //'selectable' => false,
            ],
            'description' => [
                'type' => Type::string(),
                'description' => 'Описание группы',
                'alias' => 'description', // Use 'alias', if the database column is different from the type name
            ],
            'location' => [
                'type' => \GraphQL::type('CityType'),
                'description' => 'Локация группы',
                'alias' => 'city_id',
                'resolve' => function ($model) {
                    return $model->location;
                },
                'selectable' => false,
            ],
            'followersCount' => [
                'type' => Type::int(),
                'description' => 'Количество подписчиков',
                'selectable' => false,
                'resolve' => function ($model) {
                    return $model->followers->count();
                },
            ],
            'activeMembers' => [
                'type' => Type::listOf(\GraphQL::type('User')),
                'description' => 'Активные участники группы',
            ],
            'socialLinks' => [
                'type' => Type::listOf(\GraphQL::type('SocialLinks')),
                'description' => 'Соцсети группы',
            ],
            'isCreator' => [
                'type' => Type::boolean(),
                'description' => 'Владелец группы (создатель) текущий пользователь',
                'resolve' => function ($model) {
                    if (null === $this->getGuard()->user() || $this->getGuard()->user()->id !== $model->creator_group_id) {
                        return false;
                    }

                    return true;
                },
                'privacy' => IsAuthPrivacy::class,
                'selectable' => false,
            ],
            'iWatch' => [
                'type' => Type::boolean(),
                'description' => 'Я слежу за группой',
                'selectable' => false,
                'resolve' => function (MusicGroup $model) {
                    return $model->iWatch();
                },
            ],
        ];
    }
}
