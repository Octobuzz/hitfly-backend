<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 05.04.19
 * Time: 17:22.
 */

namespace App\Http\GraphQL\Interfaces;

use Rebing\GraphQL\Support\InterfaceType;
use GraphQL\Type\Definition\Type;

class UserInterface extends InterfaceType
{
    protected $attributes = [
        'name' => 'UserInterface',
        'description' => 'Пользовательский инетерфейс',
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of the character.',
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
            ],
            'location' => [
                'type' => \GraphQL::type('CityType'),
                'description' => 'Локация пользователя',
                'alias' => 'city_id',
            ],
            'avatar' => [
                'type' => Type::string(),
                'description' => 'Аватар пользователя',
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
