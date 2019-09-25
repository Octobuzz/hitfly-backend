<?php

namespace App\Http\GraphQL\Query;

use App\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class UsersQuery extends Query
{
    protected $attributes = [
        'name' => 'Users query',
        'description' => 'Получение списка пользователей',
    ];

    public function args()
    {
        return [
            'role' => [
                    'name' => 'role',
                    'type' => \GraphQL::type('UserRoleEnum'),
                ],
            'limit' => ['name' => 'limit', 'type' => Type::int()],
            'page' => ['name' => 'page', 'type' => Type::int()],
        ];
    }

    public function type()
    {
        return\GraphQL::paginate('User');
    }

    public function resolve($root, $args, SelectFields $fields)
    {
        $query = User::with($fields->getRelations());

        $query->select('users.*');
        if (false === empty($args['role'])) {
            $query
                ->leftJoin('admin_role_users', 'users.id', '=', 'admin_role_users.user_id')
                ->leftJoin('admin_roles', 'admin_role_users.role_id', '=', 'admin_roles.id')
                ->where('admin_roles.slug', '=', $args['role'])
            ;
        }
        $response = $query->paginate($args['limit'], ['*'], 'page', $args['page']);

        return $response;
    }
}
