<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Social;
use App\Services\SocialAccountService;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Socialite;

class SocialController extends Controller
{
    use ApiResponseTrait;

    /**
     * Redirect to provider for authentication.
     *
     * @param $driver
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToProvider($driver,Request $request)
    {
        if (!(config()->has("services.{$driver}"))) {
            return $this->sendFailedResponse("Драйвер {$driver} не поддерживается", 204);
        }

        try {
            return Socialite::with($driver)->with(['redirect_uri' => config('app.url')."/api/v1/login/{$driver}/callback"])->redirect();
        } catch (Exception $e) {
            return $this->sendFailedResponse($e->getMessage());
        }
    }


    /**
     * Login social user.
     *
     * @param string $provider
     *
     * @return
     */
    public function handleProviderCallback($provider, SocialAccountService $service, Request $request)
    {
        try {
            switch ($provider) {
                case 'vkontakte':
                    //dd(Socialite::with($provider)->with(['redirect_uri' => config('app.url')."/api/v1/login/{$provider}/callback"]));
                    $socialUser = Socialite::with($provider)
                        ->redirectUrl(config('app.url')."/api/v1/login/{$provider}/callback")
                        ->fields(['id', 'email', 'first_name', 'last_name', 'screen_name', 'photo', 'bdate', 'sex'])->stateless()->user();
                    break;
                default:
                    $socialUser = Socialite::driver($provider)->stateless()->user();
            }
        } catch (Exception $e) {
            if ('application/json' === $request->header()['accept']) {
                return $this->sendFailedResponse($e->getMessage());
            } else {
                return redirect()->to('/register-error')->with('message-reg', $e->getMessage());
            }
        }

        $user = $service->loginOrRegisterBySocials($socialUser, $provider);

        Auth::login($user);

        return redirect()->to('/register-success?token='.$user->access_token);
    }

    /**
     * @param Social $socialUser
     * @param string $provider
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginOrRegisterBySocials($socialUser, $provider)
    {
        $authSocial = Social::updateOrCreate(
            [
                'social_id' => $socialUser->id,
                'social_driver' => $provider,
                ],
            [
                'name' => $socialUser->name,
                'avatar' => $socialUser->avatar,
            ]);

        try {
            $user = $authSocial->user();
        } catch (Exception $e) {
            return $this->sendFailedResponse('Could not create token', 302);
        }

        return $user ? $this->returnAccessData($user) : $this->redirectToRegisterForm($authSocial);
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
            'expires_in' => auth('api')->factory()->getTTL() * 10,
            'name' => $user->name,
            'avatar' => $user->avatar,
        ];
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return $this->sendSuccessResponse('Successfully logged out', 200);
    }

    public function getProvidersList()
    {
        $providers = [
            'facebook' => [
                'url' => '/api/v1/login/facebook',
                'icon' => '/images/icons/fb.png',
            ],
            'vkontakte' => [
                'url' => '/api/v1/login/vkontakte',
                'icon' => '/images/icons/vk.png',
            ],
            'instagram' => [
                'url' => '/api/v1/login/instagram',
                'icon' => '/images/icons/inst.png',
            ],
            'odnoklassniki' =>  [
                'url' => '/api/v1/login/odnoklassniki',
                'icon' => '/images/icons/ok.png',
            ],
        ];

        return response()->json($providers)->header('Content-Type',"application/json");
    }
    public function registerSuccess(Request $request)
    {
        return view('auth.success');
    }
}
