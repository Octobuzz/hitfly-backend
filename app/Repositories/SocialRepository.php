<?php

namespace App\Repositories;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use App\Models\GroupLinks;
use App\Models\Social;
use App\User;
use Illuminate\Database\Eloquent\Collection;

class SocialRepository extends BaseRepository
{
    use  GraphQLAuthTrait;

    protected $model = Social::class;

    public function getSocialConnect($args)
    {
        /** @var User $user */
        $user = $this->getGuard()->user();
        if (null === $user) {
            $socialsLink = null;
        } else {
            /** @var Collection $socialsLink */
            $socialsLink = $user->socialsConnect;
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

    private function socialsTypes()
    {
        return  [
            GroupLinks::TYPE_SOCIAL_VK => GroupLinks::TYPE_SOCIAL_VK,
            GroupLinks::TYPE_SOCIAL_FB => GroupLinks::TYPE_SOCIAL_FB,
            GroupLinks::TYPE_SOCIAL_IN => GroupLinks::TYPE_SOCIAL_IN,
            GroupLinks::TYPE_SOCIAL_OD => GroupLinks::TYPE_SOCIAL_OD,
        ];
    }
}
