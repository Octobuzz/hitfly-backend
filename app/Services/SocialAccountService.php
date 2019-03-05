<?php

namespace App\Services;

use App\User;
use Laravel\Socialite\Contracts\User as ProviderUser;
use App\Models\Social;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Traits\ApiResponseTrait;
use Carbon\Carbon;

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
                $tmpUser = [
                    'email' => $providerUser->getEmail(),
                    'username' => $providerUser->getName(),
                    'password' => md5(rand(1, 10000)),
                ];

                if($provider==="vkontakte" && !empty($providerUser->user['bdate'])){
                    $bdate = \DateTime::createFromFormat('j.n.Y',$providerUser->user['bdate']);
                    if($bdate){
                        $tmpUser['birthday'] = $bdate->format('Y-m-d');
                    }

                }

                if($provider==="vkontakte" && !empty($providerUser->user['sex'])){
                    switch ($providerUser->user['sex']){
                        case 1:
                            $tmpUser['gender'] = 'F';
                            break;
                        case 2:
                            $tmpUser['gender'] = 'M';
                            break;
                    }
                }
                $user = User::create($tmpUser);
            }
            if($authSocial != null){

                $authSocial->user()->associate($user);
                $authSocial->save();

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

            if($user!==null){
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
    protected function returnAccessData( $user)
    {
        return [
            'token_type' => 'bearer',
            'user' => $user->username,
            'avatar' => $user->avatar,
        ];
    }
}
