<?php

namespace App\Services;

use App\User;
use Laravel\Socialite\Contracts\User as ProviderUser;
use App\Models\Social;
use App\Models\Traits\ApiResponseTrait;

class SocialAccountService
{
    use ApiResponseTrait;

    public function createOrGetUser(ProviderUser $providerUser, $provider, $authSocial = null)
    {
        $account = Social::whereSocialDriver($provider)
            ->whereSocialId($providerUser->getId())
            ->first();

        if ($account->user) {
            return $account->user;
        } else {
            $user = User::whereEmail($providerUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'email' => $providerUser->getEmail(),
                    'username' => $providerUser->getName(),
                    'password' => md5(rand(1, 10000)),
                ]);
            }
            if (null != $authSocial) {
                $authSocial->user()->associate($user);
                $authSocial->save();
                /*$account = new Social([
                    'social_id' => $providerUser->getId(),
                    'social_driver' => $provider,
                    'avatar' => $providerUser->getAvatar(),
                    'user_id' => $user->id,
                ]);*/
            }

            return $user;
        }
    }

    /**
     * @param Social $socialUser
     * @param string $provider
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginOrRegisterBySocials(ProviderUser $providerUser, $provider)
    {
        $authSocial = Social::updateOrCreate(
            [
                'social_id' => $providerUser->getId(),
                'social_driver' => $provider,
            ],
            [
                'user' => $providerUser->getName(),
                'avatar' => $providerUser->getAvatar(),
            ]);

        try {
            $user = $authSocial->user();

            if (null !== $user) {
                $user = $this->createOrGetUser($providerUser, $provider, $authSocial);
            }
        } catch (\Exception $e) {
            return $this->sendFailedResponse('Could not create token', 302);
        }

        return $user ? $user : $this->redirectToRegisterForm($authSocial);
    }

    public function redirectToRegisterForm($socialUser)
    {
        return view('auth.registerBySocials', ['socialUser' => $socialUser]);
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function returnAccessData($user)
    {
        return [
            'token_type' => 'bearer',
            'user' => $user->username,
            'avatar' => $user->avatar,
        ];
    }
}
