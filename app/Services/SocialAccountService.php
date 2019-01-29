<?php

namespace App\Services;

use App\User;

use Laravel\Socialite\Contracts\User as ProviderUser;
use App\Models\Social;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class SocialAccountService
{
    public function createOrGetUser(ProviderUser $providerUser, $provider)
    {
        $account = Social::whereSocialDriver($provider)
            ->whereSocialId($providerUser->getId())
            ->first();

        if ($account) {
            return $account->user;
        } else {

            $user = User::whereEmail($providerUser->getEmail())->first();

            if (!$user) {
                return

                $user = User::create([
                    'email' => $providerUser->getEmail(),
                    'name' => $providerUser->getName(),
                    'password' => md5(rand(1,10000)),
                ]);
            }

            $account = new Social([
                'social_id' => $providerUser->getId(),
                'social_driver' => $provider,
                'avatar' => $providerUser->getAvatar(),
            ]);

            $account->user()->associate($user);
            $account->save();

            return $user;
        }
    }

    /**
     * @param Social $socialUser
     * @param string $provider
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
                'name' => $providerUser->getName(),
                'avatar' => $providerUser->getAvatar(),
            ]);

        try {
            $user = $authSocial->user();
            $token = JWTAuth::fromUser($user);

        } catch (JWTException $e) {
            return $this->sendFailedResponse('Could not create token', 302);
        }

        return $user ? $this->returnAccessData($token, $user) : $this->redirectToRegisterForm($authSocial);

    }

    public function redirectToRegisterForm($socialUser)
    {
        return view('auth.registerBySocials', [ 'socialUser' => $socialUser]);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function returnAccessData($token, $user)
    {
        return [
            'digicoapp_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL()*10,
            'name' => $user->name,
            'avatar' => $user->avatar,
        ];
    }

}