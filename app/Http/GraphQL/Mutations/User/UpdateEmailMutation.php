<?php

namespace App\Http\GraphQL\Mutations\User;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Error\ValidationError;
use Rebing\GraphQL\Support\Mutation;

class UpdateEmailMutation extends Mutation
{
    use GraphQLAuthTrait;
    protected $attributes = [
        'name' => 'UpdateMyEmail',
        'description' => 'Обновление email текущего пользователя',
    ];

    public function type()
    {
        return \GraphQL::type('MyProfile');
    }

    public function args()
    {
        return [
            'email' => [
                'description' => 'Пользовательский email',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['required', 'max:255', 'email', 'unique:users,email'],
            ],
            'redirect' => [
                'name' => 'redirect',
                'description' => 'Редирект при смене email',
                'type' => \GraphQL::type('RedirectEnum'),
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $user = $this->getGuard()->user();

        if (null === $user) {
            return new ValidationError(trans('validation.emailIsBad'));
        }

        if (!empty($args['redirect'])) {
            $user->redirect = $args['redirect'];
            $user->save();
        }

        if (!empty($args['email']) && $args['email']) {
            $user->email = $args['email'];

            if (!empty($args['redirect'])) {
                $user->redirect = $args['redirect'];
            }

            $user->save();

            return $user;
        }

        return new ValidationError(trans('validation.emailIsBad'));
    }
}
