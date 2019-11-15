<?php

namespace App\Http\GraphQL\Mutations;

use App\Models\Social;
use App\User;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Mutation;

class RemoveSocialConnect extends Mutation
{
    protected $attributes = [
        'name' => 'RemoveSocialConnect',
        'description' => 'Удаление привязки к социальной сети',
    ];

    public function type()
    {
        return GraphQL::type('User');
    }

    public function args()
    {
        return [
            'social' => [
                'type' => \GraphQL::type('SocialLinksTypeEnum'),
                'description' => 'Тип социальной сети',
            ],
        ];
    }

    public function authorize(array $args)
    {
        return Auth::check();
    }

    public function resolve($root, $args)
    {
        /** @var Social $social */
        $social = Social::query()
            ->where('social_driver', '=', $args['social'])
            ->where('user_id', '=', Auth::user()->id)
            ->first()
        ;
        if (empty($social)) {
            return null;
        }
        $social->forceDelete();

        return Auth::user();
    }
}
