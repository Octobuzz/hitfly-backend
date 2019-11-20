<?php

namespace App\Http\GraphQL\Mutations;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use App\Models\Social;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class RemoveSocialConnect extends Mutation
{
    use  GraphQLAuthTrait;
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

    public function resolve($root, $args)
    {
        /** @var Social $social */
        $social = Social::query()
            ->where('social_driver', '=', $args['social'])
            ->where('user_id', '=', $this->getGuard()->user()->id)
            ->first()
        ;
        if (empty($social)) {
            return null;
        }
        $social->forceDelete();

        return $this->getGuard()->user();
    }
}
