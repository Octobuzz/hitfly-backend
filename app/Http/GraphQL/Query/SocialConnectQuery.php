<?php

namespace App\Http\GraphQL\Query;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use App\Models\GroupLinks;
use App\User;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Eloquent\Collection;
use Rebing\GraphQL\Support\Query;

class SocialConnectQuery extends Query
{
    use GraphQLAuthTrait;
    public $authorize = true;
    protected $attributes = [
        'name' => 'SocialConnectQuery',
        'description' => 'Социальные ссылки',
    ];

    public function type()
    {
        return Type::listOf(\GraphQL::type('SocialConnectType'));
    }

    private function socialsTypes()
    {
        return  [
            GroupLinks::TYPE_SOCIAL_VK => GroupLinks::TYPE_SOCIAL_VK,
            GroupLinks::TYPE_SOCIAL_FB => GroupLinks::TYPE_SOCIAL_FB,
            GroupLinks::TYPE_SOCIAL_IN => GroupLinks::TYPE_SOCIAL_IN,
            GroupLinks::TYPE_SOCIAL_OD => GroupLinks::TYPE_SOCIAL_OD,
        ];
    }

    public function args()
    {
        return [
            'filters' => [
                'type' => \GraphQL::type('SocialLinkFilterInput'),
                'description' => 'Фильтры',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        /** @var User $User */
        $User = $this->getGuard()->user();
        if (null === $User) {
            $socialsLink = null;
        } else {
            /** @var Collection $socialsLink */
            $socialsLink = $User->socialsConnect;
        }

        $response = [];
        foreach ($this->socialsTypes() as $socialsType) {
            $link = (isset($args['filters']['mobile']) && true === $args['filters']['mobile'])
                ? route('link_socials', ['provider' => $socialsType])
                : route('social_auth', ['provider' => $socialsType]);
            $response[] = [
                'social_type' => $socialsType,
                'link' => $link,
                'connected' => null === $socialsLink ? null : $socialsLink->contains('social_driver', $socialsType),
            ];
        }

        return $response;
    }
}
