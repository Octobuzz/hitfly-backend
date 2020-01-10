<?php

namespace App\Http\GraphQL\Mutations;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use App\Models\Social;
use App\Repositories\SocialRepository;
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
        return Type::listOf(\GraphQL::type('SocialConnectType'));
    }

    public function args()
    {
        return [
            'social' => [
                'type' => \GraphQL::type('SocialLinksTypeEnum'),
                'description' => 'Тип социальной сети',
            ],
            'filters' => [
                'type' => \GraphQL::type('SocialLinkFilterInput'),
                'description' => 'Фильтры',
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
        $social->forceDelete();

        $socialRepository = new SocialRepository();

        return $socialRepository->getSocialConnect($args);
    }
}
