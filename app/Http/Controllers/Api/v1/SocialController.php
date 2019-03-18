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
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;


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
    public function redirectToProvider($driver)
    {
        if (!(config()->has("services.{$driver}"))) {
            return $this->sendFailedResponse("Драйвер {$driver} не поддерживается", 204);
        }

        try {
            return Socialite::with($driver)->redirect();
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
    public function handleProviderCallback($provider, SocialAccountService $service,Request $request)
    {
        try {
            switch ($provider){
                case 'vkontakte':
                    $socialUser = Socialite::driver($provider)->fields( ['id', 'email', 'first_name', 'last_name', 'screen_name', 'photo','bdate','sex'])->stateless()->user();
                    break;
                default:
                    $socialUser = Socialite::driver($provider)->stateless()->user();
            }

        } catch (Exception $e) {
            if($request->header()['accept']==='application/json')
                return $this->sendFailedResponse($e->getMessage());
            else
                return view('auth.error')->with('message',$e->getMessage());
        }

        $user = $service->loginOrRegisterBySocials($socialUser, $provider);

        Auth::login($user);


        return redirect()->to('/home');
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

        return $user ? $this->returnAccessData( $user) : $this->redirectToRegisterForm($authSocial);
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
}
