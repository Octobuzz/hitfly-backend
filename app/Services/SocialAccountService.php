<?php

namespace App\Services;

use App\BuisnessLogic\Emails\Notification;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as ProviderUser;
use App\Models\Social;
use App\Models\Traits\ApiResponseTrait;

class SocialAccountService
{
    use ApiResponseTrait;

    public function createOrGetUser(ProviderUser $providerUser, $provider, $authSocial = null)
    {
        $pass = false;
        $currentUser = \Auth::user();
        if (null !== $currentUser) {
            $authSocial->user()->associate($currentUser);
            $authSocial->save();

            return $currentUser;
        }
        $account = Social::whereSocialDriver($provider)
            ->whereSocialId($providerUser->getId())
            ->first();

        if ($account->user) {
            return $account->user;
        } else {
            $user = User::query()->withTrashed()->whereEmail($providerUser->getEmail())->first();

            if (!$user) {
                $pass = Str::random(10);
                $tmpUser = [
                    'email' => $providerUser->getEmail(),
                    'username' => $providerUser->getName(),
                    'password' => Hash::make($pass),
                ];

                switch ($provider) {
                    case 'vkontakte':
                        if (!empty($providerUser->user['bdate'])) {
                            $bdate = \DateTime::createFromFormat('j.n.Y', $providerUser->user['bdate']);
                            if ($bdate) {
                                $tmpUser['birthday'] = $bdate->format('Y-m-d');
                            }
                        }
                        if (!empty($providerUser->user['sex'])) {
                            switch ($providerUser->user['sex']) {
                                case 1:
                                    $tmpUser['gender'] = 'F';
                                    break;
                                case 2:
                                    $tmpUser['gender'] = 'M';
                                    break;
                            }
                        }

                        break;
                    case 'odnoklassniki':
                        if (!empty($providerUser->user['birthday'])) {
                            $bdate = \DateTime::createFromFormat('Y-m-d', $providerUser->user['birthday']);
                            if ($bdate) {
                                $tmpUser['birthday'] = $bdate->format('Y-m-d');
                            }
                        }
                        break;
                }

                if ('vkontakte' === $provider && !empty($providerUser->user['bdate'])) {
                    $bdate = \DateTime::createFromFormat('j.n.Y', $providerUser->user['bdate']);
                    if ($bdate) {
                        $tmpUser['birthday'] = $bdate->format('Y-m-d');
                    }
                }

                if ('vkontakte' === $provider && !empty($providerUser->user['sex'])) {
                    switch ($providerUser->user['sex']) {
                        case 1:
                            $tmpUser['gender'] = 'F';
                            break;
                        case 2:
                            $tmpUser['gender'] = 'M';
                            break;
                    }
                }
                $user = User::create($tmpUser);

                if (null !== $user->email) {
                    Notification::socialRegisterPassword($user, $pass);
                }
            }
            if (null != $authSocial) {
                $user->deleted_at = null;
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
               // 'user' => $providerUser->getName(),
                'avatar' => $providerUser->getAvatar(),
            ]);

        try {
            //$user = $authSocial->user();

            if (null !== $authSocial) {
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
