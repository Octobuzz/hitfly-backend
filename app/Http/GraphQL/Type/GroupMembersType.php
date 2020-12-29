<?php

namespace App\Http\GraphQL\Type;

use App\Models\InviteToGroup;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class GroupMembersType extends GraphQLType
{
    protected $attributes = [
        'name' => 'GroupMembersType',
        'description' => 'Приглашенные члены группы',
        'model' => InviteToGroup::class,
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'alias' => 'id', // Use 'alias', if the database column is different from the type name
            ],
            'music_group_id' => [
                'type' => \GraphQL::type('MusicGroup'),
                'description' => 'Музыкальная группа',
            ],
            'email' => [
                'type' => Type::string(),
                'description' => 'email',
            ],
            'accept' => [
                'type' => Type::boolean(),
                'description' => 'принял заявку',
            ],
            'user' => [
                'type' => GraphQL::type('User'),
                'description' => 'id пользователя(приглашаемого)',
            ],
            'createdAt' => [
                'type' => Type::string(),
                'alias' => 'created_at',
                'resolve' => function ($model) {
                    return $model->created_at;
                },
                'description' => 'дата создания',
            ],
        ];
    }
}
