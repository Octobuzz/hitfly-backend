<?php

namespace App\Http\GraphQL\Query;

use App\Models\GroupLinks;
use App\User;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Query;

class SocialConnectQuery extends Query
{
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

    public function resolve($root, $args)
    {
        /** @var User $User */
        $User = Auth::user();
        /** @var Collection $socialsLink */
        $socialsLink = $User->socialsConnect;
        $response = [];
        foreach ($this->socialsTypes() as $socialsType) {
            $response[] = [
                'social_type' => $socialsType,
                'link' => route('link_socials', ['provider' => $socialsType]),
                'connected' => $socialsLink->contains('social_driver', $socialsType),
            ];
        }

        return $response;
    }
}
